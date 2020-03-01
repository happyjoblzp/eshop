<?php
/**
 * @copyright (c) 2011 aircheng.com
 * @file seller.php
 * @brief 商家API
 * @author chendeshan
 * @date 2014/10/12 13:59:44
 * @version 2.7
 */
class APISeller
{
	//商户信息
	public function getSellerInfo($id)
	{
		$id    = IFilter::act($id,'int');
		$query = new IModel('seller');
		$info  = $query->getObj("id=".$id." and is_del = 0 and is_lock = 0");
		return $info;
	}

	//获取店内分类的某个子分类或兄弟分类
	public function catTreeSeller($sellerId,$catId = 0)
	{
		if(!$sellerId)
		{
			return array();
		}

		$sellerId  = IFilter::act($sellerId,'int');
		$catId     = IFilter::act($catId,'int');
		$result    = array();
		$catDB     = new IModel('category_seller');
		$childList = $catDB->query("seller_id = '{$sellerId}' and parent_id = '{$catId}'","*","sort asc");
		if(!$childList && $catRow = $catDB->getObj("id = '{$catId}'"))
		{
			$childList = $catDB->query('seller_id = '.$sellerId.' and parent_id = '.$catRow['parent_id'],"*","sort asc");
		}
		return $childList;
	}
}