<?php
/**
 * @copyright (c) 2015 aircheng.com
 * @file haiyan.php
 * @brief **短信发送接口
 * @author nswe
 * @date 2015/5/30 15:29:38
 * @version 3.3
 */

 /**
 * @class haiyan
 * @brief 短信发送接口 http://www.duanxin10086.com/logins.html
 */
class haiyan extends hsmsBase
{
	private $submitUrl = "http://www.duanxin10086.com/sms.aspx?action=send";

	/**
	 * @brief 获取config用户配置
	 * @return array
	 */
	private function getConfig()
	{
		//如果后台没有设置的话，这里手动配置也可以
		$siteConfigObj = new Config("site_config");

		return array(
			'userid'   => $siteConfigObj->sms_userid,
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

		$post_data = array(
			'userid'   => $config['userid'],
			'account'  => $config['username'],
			'password' => $config['userpwd'],
			'content'  => $content,
			'mobile'   => $mobile,
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
		return $this->response($result);
	}

	/**
	 * @brief 解析结果
	 * @param $result 发送结果
	 * @return string success or fail
	 */
	public function response($result)
	{
		if(strpos($result,'<returnstatus>Success</returnstatus>'))
		{
			return 'success';
		}
		else
		{
			return 'fail';
		}
	}

	/**
	 * @brief 配置文件
	 */
	public function getParam()
	{
		return array(
			"sms_userid"   => "商户ID",
			"sms_username" => "用户名",
			"sms_pwd"      => "密码",
		);
	}
}