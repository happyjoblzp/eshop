<?php
/**
 * @copyright (c) 2011 aircheng.com
 * @file zhutong.php
 * @brief 短信发送接口
 * @author nswe
 * @date 2015/5/30 15:46:38
 * @version 3.3
 */

 /**
 * @class zhutong
 * @brief 短信发送接口 短信后台地址 http://www.ztsms.cn/home
 */
class zhutong extends hsmsBase
{
	private $submitUrl  = "http://www.ztsms.cn:8800/sendSms.do";
	private static $productCode= array(
		1 => '676767',//验证码
		2 => '48661',//通知
		3 => '435227',//营销广告
	);

	/**
	 * @brief 获取config用户配置
	 * @return array
	 */
	public function getConfig()
	{
		$siteConfigObj = new Config("site_config");

		return array(
			'username' => $siteConfigObj->sms_username,
			'userpwd'  => $siteConfigObj->sms_pwd,
		);
	}

	/**
	 * @brief 发送短信
	 * @param string $mobile
	 * @param string $content
	 * @return
	 */
	public function send($mobile,$content)
	{
		$config = self::getConfig();
		$codeId = $this->getProductCode($content);

		$post_data = array(
			'username' => $config['username'],
			'password' => md5($config['userpwd']),
			'content'  => $codeId == '435227' ? $content.' 退订回复N' : $content,
			'mobile'   => $mobile,
			'productid'=> $codeId,
		);

		$url    = $this->submitUrl;
		$string = '';
		foreach ($post_data as $k => $v)
		{
		   $string .="$k=".urlencode($v).'&';
		}

		$post_string = substr($string,0,-1);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$result = curl_exec($ch);
		if($result === false)
		{
            $error = curl_error($ch);
            curl_close($ch);
			return "CURL错误：".$error;
		}
		return $this->response($result);
	}

	/**
	 * @brief 解析结果
	 * @param $result 发送结果
	 * @return string success or fail
	 */
	public function response($result)
	{
		if(strpos($result,'1,') === 0)
		{
			return 'success';
		}
		else
		{
			return $this->getMessage($result);
		}
	}

	/**
	 * @brief 获取参数
	 */
	public function getParam()
	{
		return array(
			"username" => "用户名",
			"userpwd"  => "密码",
			"usersign" => "短信签名",
		);
	}

	/**
	 * @brief 根据短信内容返回产品ID
	 * @param $content
	 */
	public function getProductCode($content)
	{
		$codeWord = array(
			1 => array('验证码'),
			2 => array('通知','自提码','消费码','成功','订单','发货'),
			3 => array('营销','活动','购买','广告','打折','降价','促销','机会')
		);

		$resultCode = 2;
		foreach($codeWord as $codeNum => $wordArray)
		{
			if(is_array($wordArray) && $wordArray)
			{
				foreach($wordArray as $word)
				{
					if(strpos($content,$word) !== false)
					{
						$resultCode = $codeNum;
						break 2;
					}
				}
			}
		}
		return self::$productCode[$resultCode];
	}

	//返回消息提示
	public function getMessage($code)
	{
		$messageArray = array(
			-1 =>"用户名或者密码不正确或用户禁用",
			2  =>"余额不够或扣费错误",
			3  =>"扣费失败异常（请联系客服）",
			6  =>"有效号码为空",
			7  =>"短信内容为空",
			8  =>"无签名，必须，格式：【签名】",
			9  =>"没有Url提交权限",
			10 =>"发送号码过多,最多支持200个号码",
			11 =>"产品ID异常或产品禁用",
			12 =>"参数异常",
			13 =>"30分种重复提交",
			14 =>"用户名或密码不正确，产品余额为0，禁止提交，联系客服",
			15 =>"Ip验证失败",
			19 =>"短信内容过长，最多支持500个",
			20 =>"定时时间不正确：格式：20130202120212(14位数字)",
		);
		return isset($messageArray[$code]) ? $messageArray[$code] : $code;
	}
}