<?php
/**
 * @brief 优惠券类库
 */
class ticket
{
	/**
	 * @brief 获取优惠券状态数值
	 * @param array $ticketRow 优惠券数据
	 * @return int 状态码 -1:已使用;-2:已禁用;-3:临时锁定;-4:已过期;1:可使用;
	 */
	public static function status($ticketRow)
	{
    	if($ticketRow['is_userd']==1)
    	{
    		return -1;
    	}

    	if($ticketRow['is_close']==1)
    	{
			return -2;
    	}

    	if($ticketRow['is_close']==2)
    	{
    		return -3;
    	}

    	if(ITime::getDateTime() > $ticketRow['end_time'])
    	{
    		return -4;
    	}
    	return 1;
	}

	/**
	 * @brief 获取优惠券的状态文字
	 * @param int $status 状态码
	 * @return string 状态文字
	 */
	public static function statusText($status)
	{
		$mapping = array(
			"-1" => "已使用",
			"-2" => "已禁用",
			"-3" => "临时锁定",
			"-4" => "已过期",
			"1"  => "可使用",
		);
        return isset($mapping[$status]) ? $mapping[$status] : "未知";
	}

    /*
     * 获取拼接后的优惠券使用范围说明信息
     * @param $id 优惠券ID
     * @return string
     */
    public static function noteFull($id)
    {
        $note      = self::note($id,'platform').'-'.self::note($id,'category');
        $limit_sum = self::note($id,'limit_sum');
        if($limit_sum)
        {
             $note .= '-'.$limit_sum;
        }
        return $note;
    }

    /*
     * 获取优惠券使用范围说明信息
     * @param $id 优惠券ID
     * @param $type platform:限平台 category:限品类 limit_sum:满减额度
     * @return string
     */
	public static function note($id,$type='platform')
	{
        $ticketDB  = new IModel('ticket');
        $ticketRow = $ticketDB->getObj($id);
        if(!$ticketRow)
        {
            return "未查询到优惠券信息";
        }

        $note = '';
        switch ($type)
        {
            case 'platform':
            {
                $note = '全平台';
                if($ticketRow['seller_id'] != 0 )
                {
                    $sellerRow = Api::run('getSellerInfo',$ticketRow['seller_id']);
                    $note      = '【'.$sellerRow['true_name'].'】';
                }
            }
            break;

            case 'category':
            {
                switch ($ticketRow['type'])
                {
                    case 0:
                    {
                        $note = '全品类';
                    }
                    break;

                    case 1 :
                    {
                        $goodsDB   = new IModel('goods');
                        $goodsRow  = $goodsDB->getObj("id = ".$ticketRow['condition'],"name");
                        if(!$goodsRow)
                        {
                            return "商品信息不存在";
                        }
                        $note = '限【'.$goodsRow['name'].'】';
                    }
                    break;

                    case 2 :
                    {
                        $categoryDB   = new IModel('category');
                        $categoryRow  = $categoryDB->getObj("id = ".$ticketRow['condition'],"name");
                        if(!$categoryRow)
                        {
                            return "分类不存在";
                        }
                        $note = '限【'.$categoryRow['name'].'】';
                    }
                    break;
                }
            }
            break;

            case 'limit_sum' :
            {
                $note = '满￥'.$ticketRow['limit_sum'];
                if($ticketRow['limit_sum'] <= 0)
                {
                    $note = '';
                }
            }
            break;
        }
        return $note;
    }

    /**
     * 统一验证优惠券使用范围
     * @param $prop_id
     * @param $goodsCount classes/countsum.php 中 goodsCount 方法结果集
     * @return array ['result' => true,'error' => 错误信息, 'price' => 金额, 'seller_id' => 适用商家ID]
     */
    public static function verify($prop_id,$goodsCount)
    {
        if(!$prop_id)
        {
            return ['result' => false,'error' => '优惠券不存在'];
        }

        if(!is_array($goodsCount))
        {
            return ['result' => false,'error' => '商品信息不存在'];
        }

        //查询有效期内的优惠券信息
        $propObj = new IModel('prop');
        $propRow = $propObj->getObj('id = '.$prop_id.' and type = 0 and is_close = 0 and is_userd = 0 and is_send = 1 and NOW() between start_time and end_time','`condition`,value');
        if(!$propRow)
        {
            return ['result' => false,'error' => '优惠券已失效'];
        }

        $ticketObj   = new IModel('ticket');
        $ticketRow   = $ticketObj->getObj($propRow['condition'],'value,point,seller_id,type,`condition`,limit_sum');
        if(!$ticketRow)
        {
            return ['result' => false,'error' => '优惠券已作废'];
        }

        //平台通用优惠券
        $ticketValue = $ticketRow['value'];
        if($ticketRow['seller_id'] == 0)
        {
            //消费限额判断 【平台通用优惠券只需要全部订单总额大于限额就可以使用】
            if($ticketRow['limit_sum'] > 0 && array_sum($goodsCount['seller']) < $ticketRow['limit_sum'])
            {
                return ['result' => false,'error' => '订单总额未满足'];
            }
            $maxValue     = current($goodsCount['seller']);
            $userSellerId = key($goodsCount['seller']);
        }
        //商家专属优惠券
        else
        {
            if(!isset($goodsCount['seller'][$ticketRow['seller_id']]))
            {
                return ['result' => false,'error' => '优惠券不适用该商家'];
            }

            //消费限额判断 【商家专属优惠券必须要求专属商家订单总额大于限额才可以使用】
            if($ticketRow['limit_sum'] > 0 && $goodsCount['seller'][$ticketRow['seller_id']] < $ticketRow['limit_sum'])
            {
                return ['result' => false,'error' => '订单金额未满足'];
            }
            $maxValue     = $goodsCount['seller'][$ticketRow['seller_id']];
            $userSellerId = $ticketRow['seller_id'];
        }

        //商品限定的判断
        switch ($ticketRow['type'])
        {
            //商品单品优惠
            case 1 :
            {
                $maxValue = 0;
                $isMatch  = false;
                foreach($goodsCount['goodsList'] as $item)
                {
                    //匹配到单品ID
                    if($item['goods_id'] == $ticketRow['condition'])
                    {
                        $isMatch      = true;
                        $maxValue     = $item['sum'];
                        $userSellerId = $item['seller_id'];
                        break;
                    }
                }

                if($isMatch == false)
                {
                    return ['result' => false,'error' => '优惠券仅限特定商品'];
                }
            }
            break;

            //商品分类优惠
            case 2 :
            {
                $maxValue = 0;
                $isMatch  = false;
                $cateDB   = new IModel('category_extend');
                foreach($goodsCount['goodsList'] as $key => $item)
                {
                    $cateData = $cateDB->query('goods_id = '.$item['goods_id']);
                    if($cateData)
                    {
                        foreach($cateData as $catItem)
                        {
                            //匹配到分类ID
                            if($catItem['category_id'] == $ticketRow['condition'])
                            {
                                $isMatch      = true;
                                $maxValue    += $item['sum'];
                                $userSellerId = $item['seller_id'];
                            }
                        }
                    }
                }

                if($isMatch == false)
                {
                    return ['result' => false,'error' => '优惠券仅限特定分类'];
                }
            }
            break;
        }

        $ticketValue = $maxValue <= $ticketValue ? $maxValue : $ticketValue;
        return ['result' => true,'price' => $ticketValue,'seller_id' => $userSellerId];
    }

    //根据特殊商品信息生成goodscount数据结构
    public static function createGoodscount($goodsInfo)
    {
        $goodsArray   = [];
        $productArray = [];

        $goodsInfoArray = explode('@',$goodsInfo);
        foreach($goodsInfoArray as $temp)
        {
            $item = explode('_',$temp);
            if(count($item) == 3)
            {
                //商品
                if($item[1] == 0)
                {
                    $goodsArray[$item[0]] = $item[2];
                }
                //货品
                else
                {
                    $productArray[$item[1]] = $item[2];
                }
            }
        }

        $cartObj     = new Cart();
        $countSumObj = new CountSum();
        return $countSumObj->goodsCount($cartObj->cartFormat(array("goods" => $goodsArray,"product" => $productArray)));
    }

    /**
     * @brief 创建生成具体优惠券
     * @param array $ticketRow 优惠券DB数组
     * @return boolean or int
     */
    public static function create($ticketRow)
    {
        $propObj   = new IModel('prop');
    	$dataArray = array(
    	    'seller_id' => $ticketRow['seller_id'],
    		'condition' => $ticketRow['id'],
    		'name'      => $ticketRow['name'],
    		'card_name' => 'T'.IHash::random(8),
    		'card_pwd'  => IHash::random(8),
    		'value'     => $ticketRow['value'],
    		'start_time'=> $ticketRow['start_time'],
    		'end_time'  => $ticketRow['end_time'],
    		'is_send'   => 1,
    	);

		//判断card_name唯一性
		$where = 'card_name = "'.$dataArray['card_name'].'"';
		$isSet = $propObj->getObj($where);
		if(!$isSet)
		{
        	$propObj->setData($dataArray);
        	return $propObj->add();
		}
		return false;
    }

    /**
     * @brief 为用户绑定道具
     * @param int $propId 道具ID
     * @param int $userId 用户ID
     * @return boolean or string
     */
    public static function bindByUser($propId,$userId)
    {
        $memberObj = new IModel('member');
        $memberArray = ['prop' => "CONCAT(IFNULL(prop,''),'{$propId},')"];
        $memberObj->setData($memberArray);
        return $memberObj->update('user_id = '.$userId,'prop');
    }
}