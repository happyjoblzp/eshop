<?php
/**
 * @brief 营销模块
 * @class Market
 * @note  后台
 */
class Market extends IController implements adminAuthorization
{
	public $checkRight  = 'all';
	public $layout = 'admin';

	function init()
	{

	}

	//修改优惠券状态is_close和is_send
	function ticket_status()
	{
		$status    = IFilter::act(IReq::get('status'));
		$id        = IFilter::act(IReq::get('id'),'int');
		$ticket_id = IFilter::act(IReq::get('ticket_id'));

		if($id && $status != null && $ticket_id != null)
		{
			$ticketObj = new IModel('prop');
			if(is_array($id))
			{
				foreach($id as $val)
				{
					$where = 'id = '.$val;
					$ticketRow = $ticketObj->getObj($where,$status);
					if($ticketRow[$status]==1)
					{
						$ticketObj->setData(array($status => 0));
					}
					else
					{
						$ticketObj->setData(array($status => 1));
					}
					$ticketObj->update($where);
				}
			}
			else
			{
				$where = 'id = '.$id;
				$ticketRow = $ticketObj->getObj($where,$status);
				if($ticketRow[$status]==1)
				{
					$ticketObj->setData(array($status => 0));
				}
				else
				{
					$ticketObj->setData(array($status => 1));
				}
				$ticketObj->update($where);
			}
			$this->redirect('ticket_more_list/ticket_id/'.$ticket_id);
		}
		else
		{
			$this->ticket_id = $ticket_id;
			$this->redirect('ticket_more_list',false);
			Util::showMessage('请选择要修改的id值');
		}
	}

	//[优惠券]添加,修改[单页]
	function ticket_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$ticketObj       = new IModel('ticket');
			$where           = 'id = '.$id;
			$this->ticketRow = $ticketObj->getObj($where);
		}
		$this->redirect('ticket_edit');
	}

	//[优惠券]添加,修改[动作]
	function ticket_edit_act()
	{
		$id        = IFilter::act(IReq::get('id'),'int');
        $type      = IFilter::act(IReq::get('type'));
        $ticketObj = new IModel('ticket');
        switch($type)
        {
            case 0:
            {
                $condition = '';
            }
            break;

            case 1:
            {
                $gid = IFilter::act(IReq::get('goods_id'),'int');
                if(!$gid || !is_array($gid))
                {
                    IError::show(403,'商品信息没有设置');
                }
                $condition = $gid[0];
            }
            break;

            case 2:
            {
                $category = IFilter::act(IReq::get('category'),'int');
                if(!$category || !is_array($category))
                {
                    IError::show(403,'商品分类信息没有设置');
                }
                $condition = $category[0];
            }
            break;

            default:
                IError::show(403,'优惠券类型错误!');
        }

		$dataArray = array(
			'name'      => IFilter::act(IReq::get('name','post')),
			'start_time'=> IFilter::act(IReq::get('start_time','post'),'datetime'),
			'end_time'  => IFilter::act(IReq::get('end_time','post'),'datetime'),
			'point'     => IFilter::act(IReq::get('point','post'),'int'),
		);

        //修改优惠券 则类型 条件 优惠券面额值 不能修改
        if(!$id)
        {
            $dataArray['type']       = $type;
            $dataArray['condition']  = $condition;
            $dataArray['value']      = IFilter::act(IReq::get('value','post'));
            $dataArray['limit_sum']  = IFilter::act(IReq::get('limit_sum','post'),'float');
        }

		$ticketObj->setData($dataArray);
		if($id)
		{
            $ticketObj->update($id);
        }
		else
		{
			$ticketObj->add();
		}
		$this->redirect('ticket_list');
	}

	//[优惠券]生成[动作]
	function ticket_create()
	{
		$propObj   = new IModel('prop');
		$prop_num  = intval(IReq::get('num'));
		$ticket_id = intval(IReq::get('ticket_id'));

		if($prop_num && $ticket_id)
		{
			$prop_num  = ($prop_num > 5000) ? 5000 : $prop_num;
			$ticketObj = new IModel('ticket');
			$where     = 'id = '.$ticket_id;
			$ticketRow = $ticketObj->getObj($where);

			for($item = 0; $item < intval($prop_num); $item++)
			{
			    $insertId = ticket::create($ticketRow);
				if(!$insertId)
				{
					$item--;
					continue;
				}
			}
			$logObj = new Log('db');
			$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"生成了优惠券","面值：".$ticketRow['value']."元，数量：".$prop_num."张"));
		}
		$this->redirect('ticket_list');
	}

	//[优惠券]删除
	function ticket_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$ticketObj = new IModel('ticket');
			$propObj   = new IModel('prop');
			$propRow   = $propObj->getObj(" `type` = 0 and `condition` = {$id} and (is_close = 2 or (is_userd = 0 and is_send = 1)) ");

			if($propRow)
			{
				$this->redirect('ticket_list',false);
				Util::showMessage('无法删除优惠券，其下还有正在使用的优惠券');
				exit;
			}

			$where = "id = {$id} ";
			$ticketRow = $ticketObj->getObj($where);
			if($ticketObj->del($where))
			{
				$where = " `type` = 0 and `condition` = {$id} ";
				$propObj->del($where);

				$logObj = new Log('db');
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了一种优惠券","优惠券名称：".$ticketRow['name']));
			}
			$this->redirect('ticket_list');
		}
		else
		{
			$this->redirect('ticket_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//[优惠券详细]删除
	function ticket_more_del()
	{
		$id        = IFilter::act(IReq::get('id'),'int');
		$ticket_id = IFilter::act(IReq::get('ticket_id'),'int');
		if($id)
		{
			$ticketObj = new IModel('ticket');
			$ticketRow = $ticketObj->getObj('id = '.$ticket_id);
			$logObj    = new Log('db');
			$propObj   = new IModel('prop');
			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"批量删除了实体优惠券","优惠券名称：".$ticketRow['name']."，数量：".count($id)));
			}
			else
			{
				$where = 'id = '.$id;
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了1张实体优惠券","优惠券名称：".$ticketRow['name']));
			}
			$propObj->del($where);
			$this->redirect('ticket_more_list/ticket_id/'.$ticket_id);
		}
		else
		{
			$this->ticket_id = $ticket_id;
			$this->redirect('ticket_more_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//[优惠券详细] 列表
	function ticket_more_list()
	{
		$this->ticket_id = IFilter::act(IReq::get('ticket_id'),'int');
		$this->redirect('ticket_more_list');
	}

	//[优惠券] 输出excel表格
	function ticket_excel()
	{
		//优惠券excel表存放地址
		$ticket_id = IFilter::act(IReq::get('id'));

		if($ticket_id)
		{
			$propObj = new IModel('prop');
			$where   = 'type = 0';
			$ticket_id_array = is_array($ticket_id) ? $ticket_id : array($ticket_id);

			//当优惠券数量没有时不允许备份excel
			foreach($ticket_id_array as $key => $tid)
			{
				if(statistics::getTicketCount($tid) == 0)
				{
					unset($ticket_id_array[$key]);
				}
			}

			if($ticket_id_array)
			{
				$id_num_str = join('","',$ticket_id_array);
			}
			else
			{
				$this->redirect('ticket_list',false);
				Util::showMessage('实体优惠券数量为0张，无法备份');
				exit;
			}

			$where.= ' and `condition` in("'.$id_num_str.'")';
			$propList = $propObj->query($where,'*','`condition` asc',10000);

			$ticketFile = "ticket_".join("_",$ticket_id_array);
			$reportObj = new report($ticketFile);
			$reportObj->setTitle(array("名称","卡号","密码","面值","已被使用","是否关闭","是否发送","开始时间","结束时间"));
			foreach($propList as $key => $val)
			{
				$is_userd = ($val['is_userd']=='1') ? '是':'否';
				$is_close = ($val['is_close']=='1') ? '是':'否';
				$is_send  = ($val['is_send']=='1') ? '是':'否';

				$insertData = array(
					$val['name'],
					$val['card_name'],
					$val['card_pwd'],
					$val['value'].'元',
					$is_userd,
					$is_close,
					$is_send,
					$val['start_time'],
					$val['end_time'],
				);
				$reportObj->setData($insertData);
			}
			$reportObj->toDownload();
		}
		else
		{
			$this->redirect('ticket_list',false);
			Util::showMessage('请选择要操作的文件');
		}
	}

	//[优惠券]获取优惠券数据
	function getTicketList()
	{
		$ticketObj  = new IModel('ticket');
		$ticketList = $ticketObj->query('seller_id = 0');
		echo JSON::encode($ticketList);
	}

	//[促销活动] 添加修改 [单页]
	function pro_rule_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$promotionObj = new IModel('promotion');
			$where = 'id = '.$id;
			$this->promotionRow = $promotionObj->getObj($where);
		}
		$this->redirect('pro_rule_edit');
	}

	//[促销活动] 添加修改 [动作]
	function pro_rule_edit_act()
	{
		$id          = IFilter::act(IReq::get('id'),'int');
		$user_group  = IFilter::act(IReq::get('user_group','post'));
		$user_group  = is_array($user_group) ? join(",",$user_group) : "";
		$promotionObj= new IModel('promotion');

		$dataArray = array(
			'name'       => IFilter::act(IReq::get('name','post')),
			'condition'  => IFilter::act(IReq::get('condition','post')),
			'is_close'   => IFilter::act(IReq::get('is_close','post')),
			'start_time' => IFilter::act(IReq::get('start_time','post')),
			'end_time'   => IFilter::act(IReq::get('end_time','post')),
			'intro'      => IFilter::act(IReq::get('intro','post')),
			'award_type' => IFilter::act(IReq::get('award_type','post')),
			'type'       => IFilter::act(IReq::get('type','post')),
			'user_group' => $user_group,
			'award_value'=> IFilter::act(IReq::get('award_value','post')),
		);

		$promotionObj->setData($dataArray);

		if($id)
		{
			$where = 'id = '.$id;
			$promotionObj->update($where);
		}
		else
		{
			$promotionObj->add();
		}
		$this->redirect('pro_rule_list');
	}

	//[促销活动] 删除
	function pro_rule_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$promotionObj = new IModel('promotion');
			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
			}
			else
			{
				$where = 'id = '.$id;
			}
			$promotionObj->del($where);
			$this->redirect('pro_rule_list');
		}
		else
		{
			$this->redirect('pro_rule_list',false);
			Util::showMessage('请选择要删除的促销活动');
		}
	}

	//[限时抢购]添加,修改[单页]
	function pro_speed_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$promotionObj = new IModel('promotion');
			$where = 'id = '.$id;
			$promotionRow = $promotionObj->getObj($where);
			if(!$promotionRow)
			{
				$this->redirect('pro_speed_list');
				return;
			}

			//促销商品
			$goodsObj = new IModel('goods');
			$goodsRow = $goodsObj->getObj('id = '.$promotionRow['condition'],'id,name,sell_price,img');
			if($goodsRow)
			{
				$result = array(
					'isError' => false,
					'data'    => $goodsRow,
				);
			}
			else
			{
				$result = array(
					'isError' => true,
					'message' => '关联商品被删除，请重新选择要抢购的商品',
				);
			}

			$promotionRow['goodsRow'] = JSON::encode($result);
			$this->promotionRow = $promotionRow;
		}
		$this->redirect('pro_speed_edit');
	}

	//[限时抢购]添加,修改[动作]
	function pro_speed_edit_act()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		$condition   = IFilter::act(IReq::get('condition','post'));
		$award_value = IFilter::act(IReq::get('award_value','post'));
		$user_group  = IFilter::act(IReq::get('user_group','post'));
		$user_group  = is_array($user_group) ? join(",",$user_group): "";

		$dataArray = array(
			'id'         => $id,
			'name'       => IFilter::act(IReq::get('name','post')),
			'condition'  => $condition,
			'award_value'=> $award_value,
			'is_close'   => IFilter::act(IReq::get('is_close','post')),
			'start_time' => IFilter::act(IReq::get('start_time','post')),
			'end_time'   => IFilter::act(IReq::get('end_time','post')),
			'intro'      => IFilter::act(IReq::get('intro','post')),
			'type'       => 1,
			'award_type' => 0,
			'user_group' => $user_group,
		);

		if(!$condition || !$award_value)
		{
			$this->promotionRow = $dataArray;
			$this->redirect('pro_speed_edit',false);
			Util::showMessage('请添加促销的商品，并为商品填写价格');
		}

		$proObj = new IModel('promotion');
		$proObj->setData($dataArray);
		if($id)
		{
			$where = 'id = '.$id;
			$proObj->update($where);
		}
		else
		{
            $id = $proObj->add();
		}
		$result = Active::goodsActiveEdit($id,'time');
		if($result && is_string($result))
		{
		    $proObj->rollback();
		    IError::show($result);
		}
		$this->redirect('pro_speed_list');
	}

	//[限时抢购]删除
	function pro_speed_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			if(is_array($id))
			{
				$id = join(',',$id);
			}
			Active::goodsActiveDel($id,'time');
			$propObj = new IModel('promotion');
			$propObj->del('id in ('.$id.') and type = 1');
			$this->redirect('pro_speed_list');
		}
		else
		{
			$this->redirect('pro_speed_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//[团购]添加修改[单页]
	function regiment_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		if($id)
		{
			$regimentObj = new IModel('regiment');
			$where       = 'id = '.$id;
			$regimentRow = $regimentObj->getObj($where);
			if(!$regimentRow)
			{
				$this->redirect('regiment_list');
				return;
			}

			//促销商品
			$goodsObj = new IModel('goods');
			$goodsRow = $goodsObj->getObj('id = '.$regimentRow['goods_id']);

			$result = array(
				'isError' => false,
				'data'    => $goodsRow,
			);
			$regimentRow['goodsRow'] = JSON::encode($result);
			$this->regimentRow = $regimentRow;
		}
		$this->redirect('regiment_edit');
	}

	//[团购]添加修改[动作]
	function regiment_edit_act()
	{
		$id      = IFilter::act(IReq::get('id'),'int');
		$goodsId = IFilter::act(IReq::get('goods_id'),'int');

		$dataArray = array(
			'id'        	=> $id,
			'title'     	=> IFilter::act(IReq::get('title','post')),
			'start_time'	=> IFilter::act(IReq::get('start_time','post')),
			'end_time'  	=> IFilter::act(IReq::get('end_time','post')),
			'is_close'      => IFilter::act(IReq::get('is_close','post')),
			'intro'     	=> IFilter::act(IReq::get('intro','post')),
			'goods_id'      => $goodsId,
			'store_nums'    => IFilter::act(IReq::get('store_nums','post')),
			'limit_min_count' => IFilter::act(IReq::get('limit_min_count','post'),'int'),
			'limit_max_count' => IFilter::act(IReq::get('limit_max_count','post'),'int'),
			'regiment_price'=> IFilter::act(IReq::get('regiment_price','post')),
			'sort'          => IFilter::act(IReq::get('sort','post')),
		);

		$dataArray['limit_min_count'] = $dataArray['limit_min_count'] <= 0 ? 1 : $dataArray['limit_min_count'];
		$dataArray['limit_max_count'] = $dataArray['limit_max_count'] <= 0 ? $dataArray['store_nums'] : $dataArray['limit_max_count'];

		if($goodsId)
		{
			$goodsObj = new IModel('goods');
			$where    = 'id = '.$goodsId;
			$goodsRow = $goodsObj->getObj($where);

			//处理上传图片
			if(isset($_FILES['img']['name']) && $_FILES['img']['name'] != '')
			{
			    $uploadDir = IWeb::$app->config['upload'].'/regiment';
				$uploadObj = new PhotoUpload($uploadDir);
				$photoInfo = $uploadObj->run();
				$dataArray['img'] = $photoInfo['img']['img'];
			}
			else
			{
				$dataArray['img'] = $goodsRow['img'];
			}

			$dataArray['sell_price'] = $goodsRow['sell_price'];
		}
		else
		{
			$this->regimentRow = $dataArray;
			$this->redirect('regiment_edit',false);
			Util::showMessage('请选择要关联的商品');
		}

		$regimentObj = new IModel('regiment');
		$regimentObj->setData($dataArray);

		if($id)
		{
			$where = 'id = '.$id;
			$regimentObj->update($where);
		}
		else
		{
            $id = $regimentObj->add();
		}
		$result = Active::goodsActiveEdit($id,'groupon');
		if($result && is_string($result))
		{
		    $regimentObj->rollback();
		    IError::show($result);
		}
		$this->redirect('regiment_list');
	}

	//[团购]删除
	function regiment_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			if(is_array($id))
			{
				$id = join(',',$id);
			}
			Active::goodsActiveDel($id,'groupon');
			$regObj = new IModel('regiment');
			$regObj->del('id in ('.$id.')');
			$this->redirect('regiment_list');
		}
		else
		{
			$this->redirect('regiment_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//账户余额记录
	function account_list()
	{
		$page   = IReq::get('page') ? intval(IReq::get('page')) : 1;
		$where  = Util::search(IReq::get('search'));
		$where .= $where ? " and event != 3 " : "";

		$accountObj = new IQuery('account_log');
		$accountObj->where = $where;
		$accountObj->order = 'id desc';
		$accountObj->page  = $page;

		$this->accountObj  = $accountObj;
		$this->accountList = $accountObj->find();

		$this->redirect('account_list');
	}

	//后台操作记录
	function operation_list()
	{
		$page  = IReq::get('page') ? intval(IReq::get('page')) : 1;
		$where = Util::search(IReq::get('search'));

		$operationObj = new IQuery('log_operation');
		$operationObj->where = $where;
		$operationObj->order = 'id desc';
		$operationObj->page  = $page;

		$this->operationObj  = $operationObj;
		$this->operationList = $operationObj->find();

		$this->redirect('operation_list');
	}

	//清理后台管理员操作日志
	function clear_log()
	{
		$type  = IReq::get('type');
		$month = intval(IReq::get('month'));
		if(!$month)
		{
			die('请填写要清理日志的月份');
		}

		switch($type)
		{
			case "account":
			{
				$logObj = new IModel('account_log');
				$logObj->del("event = 1 and TIMESTAMPDIFF(MONTH,time,NOW()) >= '{$month}'");
				$this->redirect('account_list');
				break;
			}
			case "operation":
			{
				$logObj = new IModel('log_operation');
				$logObj->del("TIMESTAMPDIFF(MONTH,datetime,NOW()) >= '{$month}'");
				$this->redirect('operation_list');
				break;
			}
			default:
				die('缺少类别参数');
		}
	}

	//修改结算账单
	public function bill_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		$billDB  = new IModel('bill');
		$billRow = $billDB->getObj('id = '.$id);
		if(!$billRow)
		{
			IError::show(403,'收款单信息不存在');
		}
		$sellerRow = Api::run('getSellerInfo',$billRow['seller_id']);
		if(!$sellerRow)
		{
			IError::show(403,'商家信息不存在');
		}
		$billRow['seller_name'] = $sellerRow['seller_name'];
		$this->billRow = $billRow;
		$this->redirect('bill_edit');
	}

	//结算单修改
	public function bill_update()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		$pay_content = IFilter::act(IReq::get('pay_content'));
		$is_pay = IFilter::act(IReq::get('is_pay'),'int');

		if($id)
		{
			$data = array(
				'admin_id' => $this->admin['admin_id'],
				'pay_content' => $pay_content,
				'is_pay' => $is_pay,
			);

			$billDB = new IModel('bill');

			$data['pay_time'] = ($is_pay == 1) ? ITime::getDateTime() : "";

			$billRow= $billDB->getObj('id = '.$id);
			if(isset($billRow['order_ids']) && $billRow['order_ids'])
			{
				//更新订单商品关系表中的结算字段
				$orderDB = new IModel('order');
				$orderIdArray = explode(',',$billRow['order_ids']);
				foreach($orderIdArray as $key => $val)
				{
					$orderDB->setData(array('is_checkout' => $is_pay));
					$orderDB->update('id = '.$val);
				}
			}

			$billDB->setData($data);
			$billDB->update('id = '.$id);
		}
		$this->redirect('bill_list');
	}

	//结算单删除
	public function bill_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		if($id)
		{
			$billDB = new IModel('bill');
			$billDB->del('id = '.$id.' and is_pay = 0');
		}

		$this->redirect('bill_list');
	}

	//导出用户统计数据
	public function user_report()
	{
		$start = IFilter::act(IReq::get('start'));
		$end   = IFilter::act(IReq::get('end'));

		$memberQuery = new IQuery('member as m');
		$memberQuery->join   = "left join user as u on m.user_id=u.id";
		$memberQuery->fields = "u.username,m.time,m.email,m.mobile";
		$memberQuery->where  = "m.time between '".$start."' and '".$end." 23:59:59'";
		$memberList          = $memberQuery->find();

		$reportObj = new report('user');
		$reportObj->setTitle(array("日期","用户名","邮箱","手机号"));
		foreach($memberList as $k => $val)
		{
			$insertData = array($val['time'],$val['username'],$val['email'],$val['mobile']);
			$reportObj->setData($insertData);
		}
		$reportObj->toDownload();
	}

	//导出人均消费数据
	public function spanding_report()
	{
		$start = IFilter::act(IReq::get('start'));
		$end   = IFilter::act(IReq::get('end'));

		$reportObj = new report('spanding');
		$reportObj->setTitle(array("日期","人均消费金额"));

		$db = new IQuery('collection_doc');
		$db->fields   = "sum(amount)/count(*) as count,`time`,DATE_FORMAT(`time`,'%Y-%m-%d') as `timeData`";
		$db->where    = "pay_status = 1";
		$db->group    = "DATE_FORMAT(`time`,'%Y-%m-%d') having `time` >= '{$start}' and `time` < '{$end} 23:59:59'";
		$spandingList = $db->find();
		foreach($spandingList as $k => $val)
		{
			$insertData = array($val['timeData'],$val['count']);
			$reportObj->setData($insertData);
		}
		$reportObj->toDownload();
	}

	//导出销售数据
	public function amount_report()
	{
		$start = IFilter::act(IReq::get('start'));
		$end   = IFilter::act(IReq::get('end'));

		$reportObj = new report('amount');
		$reportObj->setTitle(array("订单生成日期","订单数量","商品销售额","商品成本","商品毛利"));

		$orderDB   = new IModel('order');
		$orderList = $orderDB->query(" pay_status = 1 and `create_time` between '{$start}' and '{$end} 23:59:59' "," DATE_FORMAT(`create_time`,'%Y-%m-%d') as ctime,id ","id asc");
		if($orderList)
		{
			//按照订单时间组合订单ID
			$ids = array();
			foreach($orderList as $key => $val)
			{
				if(!isset($ids[$val['ctime']]))
				{
					$ids[$val['ctime']] = array();
				}
				$ids[$val['ctime']][] = $val['id'];
			}

			//获取订单数据
			$db        = new IQuery('order_goods as og');
			$db->join  = "left join goods as go on go.id = og.goods_id left join products as p on p.id = og.product_id ";
			$db->fields= "og.*,go.cost_price as go_cost,p.cost_price as p_cost";
			$db->order = "og.order_id asc";
			$result    = array();
			foreach($ids as $ctime => $idArray)
			{
				$db->where = "og.order_id in (".join(',',$idArray).") and og.is_send != 2";
				$orderList = $db->find();

				$result[$ctime] = array("orderNum" => count($idArray),"goods_sum" => 0,"goods_cost" => 0,"goods_diff" => 0);
				foreach($orderList as $key => $val)
				{
					$result[$ctime]['goods_sum']  += $val['real_price'] * $val['goods_nums'];
					$cost                          = $val['p_cost'] ? $val['p_cost'] : $val['go_cost'];
					$result[$ctime]['goods_cost'] += $cost * $val['goods_nums'];
				}
				$result[$ctime]['goods_diff'] += $result[$ctime]['goods_sum'] - $result[$ctime]['goods_cost'];
			}

			foreach($result as $ctime => $val)
			{
				$insertData = array(
					$ctime,
					$val['orderNum'],
					$val['goods_sum'],
					$val['goods_cost'],
					$val['goods_diff'],
				);
				$reportObj->setData($insertData);
			}
		}
		$reportObj->toDownload();
	}

	//[特价商品]添加,修改[单页]
	function sale_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$promotionObj = new IModel('promotion');
			$where = 'id = '.$id.' and award_type = 7';
			$this->promotionRow = $promotionObj->getObj($where);
			if(!$this->promotionRow)
			{
				IError::show("信息不存在");
			}
		}
		$this->redirect('sale_edit');
	}

	//[特价商品]添加,修改[动作]
	function sale_edit_act()
	{
		$id           = IFilter::act(IReq::get('id'),'int');
		$award_value  = IFilter::act(IReq::get('award_value'),'int');
		$type         = IFilter::act(IReq::get('type'));
		$is_close     = IFilter::act(IReq::get('is_close','post'));
		$intro        = array();//特价商品ID

		$proObj = new IModel('promotion');
		if($id)
		{
			//获取旧数据和原始价格
			$proRow = $proObj->getObj("id = ".$id);
			if(!$proRow)
			{
				IError::show('特价活动不存在');
			}

			if($proRow['is_close'] == 0 && $proRow['intro'])
			{
				goods_class::goodsDiscount($proRow['intro'],$proRow['award_value'],"percent","add");
			}
		}

		switch($type)
		{
			case 2:
			{
				$category = IFilter::act(IReq::get('category'),'int');
				if(!$category)
				{
					IError::show(403,'商品分类信息没有设置');
				}
				$condition = join(",",$category);
				$goodsData = Api::run("getCategoryExtendList",array("#categroy_id#",$condition),500);
				foreach($goodsData as $key => $val)
				{
					$intro[] = $val['id'];
				}
			}
			break;

			case 3:
			{
				$gid = IFilter::act(IReq::get('goods_id'),'int');
				if(!$gid || !is_array($gid))
				{
					IError::show(403,'商品信息没有设置');
				}
				$condition= join(",",$gid);
				$intro    = $gid;
			}
			break;

			case 4:
			{
				$condition = IFilter::act(IReq::get('brand_id'),'int');
				if(!$condition)
				{
					IError::show(403,'品牌信息没有设置');
				}
				$goodsDB   = new IModel('goods');
				$goodsData = $goodsDB->query("brand_id = ".$condition,"*","sort asc",500);
				foreach($goodsData as $key => $val)
				{
					$intro[] = $val['id'];
				}
			}
			break;
		}

		if(!$intro)
		{
			IError::show(403,'商品信息不存在，请确定你选择的条件有商品');
		}

		//去掉重复促销的商品,剩余的ID进行价格修改
		$proData = $proObj->query("award_type = 7 and id != ".$id);
		foreach($proData as $key => $val)
		{
			$temp  = explode(",",$val['intro']);
			$intro = array_diff($intro,$temp);
		}

		if(!$intro)
		{
			IError::show(403,'商品不能重复设置特价');
		}

		$dataArray = array(
			'name'       => IFilter::act(IReq::get('name','post')),
			'condition'  => $condition,
			'award_value'=> $award_value,
			'is_close'   => $is_close,
			'start_time' => ITime::getDateTime(),
			'intro'      => join(",",$intro),
			'type'       => $type,
			'award_type' => 7,
			'sort'       => IFilter::act(IReq::get('sort'),'int'),
		);

		$proObj->setData($dataArray);
		if($id)
		{
			$where = 'id = '.$id;
			$proObj->update($where);
		}
		else
		{
			$proObj->add();
		}

		//开启
		if($is_close == 0 && $intro)
		{
			goods_class::goodsDiscount(join(",",$intro),$award_value,"percent","reduce");
		}
		$this->redirect('sale_list');
	}

	//[特价商品]删除
	function sale_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$proObj = new IModel('promotion');
			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
			}
			else
			{
				$where = ' id = '.$id;
			}
			$where .= ' and award_type = 7 ';

			//恢复特价商品价格
			$proList = $proObj->query($where);
			foreach($proList as $key => $val)
			{
				if($val['is_close'] == 0 && $val['intro'])
				{
					goods_class::goodsDiscount($val['intro'],$val['award_value'],"percent","add");
				}
			}
			$proObj->del($where);
			$this->redirect('sale_list');
		}
		else
		{
			$this->redirect('sale_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//商家结算报表导出
	function sellerReport()
	{
		$where  = util::search(IReq::get('search'));
		$billDB = new IQuery('bill as b');
		$billDB->join   = "left join seller as s on s.id = b.seller_id";
		$billDB->where  = $where;
		$billDB->fields = "b.*,s.email,s.true_name";
		$billDB->group  = "b.seller_id";
		$billData       = $billDB->find();

		$reportObj = new report('seller_bill');
		$reportObj->setTitle(array("商家邮箱","商家名称","付款金额（元）","货款起始时间","货款结束时间","结算状态"));
		if($billData)
		{
			foreach($billData as $key => $val)
			{
				$insertData = array(
					$val['email'],
					$val['true_name'],
					$val['amount'],
					$val['start_time'],
					$val['end_time'],
					$val['is_pay'] == 1 ? "已结算" : "未结算",
				);
				$reportObj->setData($insertData);
			}
		}
		$reportObj->toDownload();
	}

    //[积分兑换]添加,修改 表单显示
    function cost_point_edit()
    {
        $id = IFilter::act(IReq::get('id'),'int');
        if($id)
        {
            $costPointObj = new IModel('cost_point');
            $costPointRow = $costPointObj->getObj('id = '.$id);
            if(!$costPointRow)
            {
                $this->redirect('cost_point_list');
                return;
            }

            //积分兑换商品
            $goodsObj = new IModel('goods');
            $goodsRow = $goodsObj->getObj('id = '.$costPointRow['goods_id'],'id,name,sell_price,img');
            if(!$goodsRow)
            {
            	IError::show(403,'关联商品被删除，请重新选择要兑换的商品');

            }

            $result = array(
            	'isError' => false,
                'data'    => $goodsRow,
            );
            $costPointRow['goodsRow'] = JSON::encode($result);
            $this->costPointRow = $costPointRow;
        }
        $this->redirect('cost_point_edit');
    }

    //[积分兑换]添加,修改
    function cost_point_edit_act()
    {
        $id = IFilter::act(IReq::get('id','post'),'int');
        $goods_id = IFilter::act(IReq::get('condition','post'),'int');
        $point = IFilter::act(IReq::get('point','post'),'int');
        $is_close = IFilter::act(IReq::get('is_close'),'int');
        $user_group  = IFilter::act(IReq::get('user_group','post'));
        $user_group  = is_array($user_group) ? join(',',$user_group) : "";
        $name  = IFilter::act(IReq::get('name','post'));

        $dataArray = array(
            'id'         => $id,
            'goods_id'   => $goods_id,
            'name'       => $name,
            'point'      => $point,
            'is_close'   => $is_close,
            'user_group' => $user_group,
        );

        $costPointObj = new IModel('cost_point');
        $costPointObj->setData($dataArray);
        if($id)
        {
            $costPointObj->update('id = '.$id);
        }
        else
        {
            $id = $costPointObj->add();
        }
        $result = Active::goodsActiveEdit($id,'costpoint');
		if($result && is_string($result))
		{
		    $costPointObj->rollback();
		    IError::show($result);
		}
        $this->redirect('cost_point_list');
    }

    //[积分兑换]删除
    function cost_point_del()
    {
        $id = IFilter::act(IReq::get('id'));
        if($id)
        {
			if(is_array($id))
			{
				$id = join(',',$id);
			}
			Active::goodsActiveDel($id,'costpoint');
            $costPointObj = new IModel('cost_point');
            $costPointObj->del('id in ('.$id.')');
            $this->redirect('cost_point_list');
        }
        else
        {
            $this->redirect('cost_point_list',false);
            Util::showMessage('请选择要删除的id值');
        }
    }

    /*
    * 取得所有货款结算申请
    */
    function bill_list()
    {
        $search = IFilter::act(IReq::get('search'),'strict');
        $where = Util::search($search);
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("bill as b");
        $query->join  = 'left join seller as s on s.id = b.seller_id left join admin as a on b.admin_id = a.id';
        $query->where  = $where;
        $query->fields = 'b.*,s.seller_name,a.admin_name';
        $query->page   = $page;
        $this->query    = $query;
        $this->redirect('bill_list');
    }

    /**
     * @brief 商户订单结算
     */
	public function order_goods_list()
	{
        $search = IFilter::act(IReq::get('search'),'strict');
        $where  = Util::search($search);
		$page   = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;

		$orderGoodsQuery = CountSum::getSellerGoodsFeeQuery();
		$orderGoodsQuery->page  = $page;
		$orderGoodsQuery->where = $orderGoodsQuery->getWhere()." and ".$where;
		$this->query = $orderGoodsQuery;
		$this->redirect('order_goods_list');
	}

	//[拼团]添加修改[单页]
	function assemble_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		if($id)
		{
			$assembleObj = new IModel('assemble');
			$assembleRow = $assembleObj->getObj($id);
			if(!$assembleRow)
			{
				$this->redirect('assemble_list');
				return;
			}

			//促销商品
			$goodsObj = new IModel('goods');
			$goodsRow = $goodsObj->getObj($assembleRow['goods_id']);

			$result = array(
				'isError' => false,
				'data'    => $goodsRow,
			);
			$assembleRow['goodsRow'] = JSON::encode($result);
			$this->assembleRow = $assembleRow;
		}
		$this->redirect('assemble_edit');
	}

	//[拼团]添加修改[动作]
	function assemble_update()
	{
		$id      = IFilter::act(IReq::get('id'),'int');
		$goodsId = IFilter::act(IReq::get('goods_id'),'int');

		$dataArray = array(
			'id'        	=> $id,
			'title'     	=> IFilter::act(IReq::get('title','post')),
			'is_close'      => IFilter::act(IReq::get('is_close','post')),
			'intro'     	=> IFilter::act(IReq::get('intro','post')),
			'goods_id'      => $goodsId,
			'sum_count'     => IFilter::act(IReq::get('sum_count','post')),
			'assemble_price'=> IFilter::act(IReq::get('assemble_price','post')),
			'limit_nums'    => IFilter::act(IReq::get('limit_nums','post')),
			'sort'          => IFilter::act(IReq::get('sort','post')),
		);

		if($goodsId)
		{
			$goodsObj = new IModel('goods');
			$goodsRow = $goodsObj->getObj($goodsId);

			//处理上传图片
			if(isset($_FILES['img']['name']) && $_FILES['img']['name'] != '')
			{
			    $uploadDir = IWeb::$app->config['upload'].'/assemble';
				$uploadObj = new PhotoUpload($uploadDir);
				$photoInfo = $uploadObj->run();
				$dataArray['img'] = $photoInfo['img']['img'];
			}
			else
			{
				$dataArray['img'] = $goodsRow['img'];
			}

			$dataArray['sell_price'] = $goodsRow['sell_price'];
		}
		else
		{
			$this->assembleRow = $dataArray;
			$this->redirect('assemble_edit',false);
			Util::showMessage('请选择要关联的商品');
		}

		$assembleObj = new IModel('assemble');
		$assembleObj->setData($dataArray);

		if($id)
		{
			$where = 'id = '.$id;
			$assembleObj->update($where);
		}
		else
		{
            $id = $assembleObj->add();
		}
		$result = Active::goodsActiveEdit($id,'assemble');
		if($result && is_string($result))
		{
		    $assembleObj->rollback();
		    IError::show($result);
		}
		$this->redirect('assemble_list');
	}

	//[拼团]删除
	function assemble_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			if(is_array($id))
			{
				$id = join(',',$id);
			}
			Active::goodsActiveDel($id,'assemble');
			$assebleObj = new IModel('assemble');
			$assebleObj->del('id in ('.$id.')');
			$this->redirect('assemble_list');
		}
		else
		{
			$this->redirect('assemble_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}
}