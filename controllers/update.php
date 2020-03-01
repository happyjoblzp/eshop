<?php
/**
 * @brief 升级更新控制器
 */
class Update extends IController
{
	/**
	 * @brief 升级更新
	 */
	public function index()
	{
		set_time_limit(0);

		if(is_file('config/plugin_config.php') && is_writable('config/plugin_config.php') == false)
		{
		    die('<config/plugin_config.php>请确保文件存在并且可以拥有写入权限');
		}

		//备份banner数据
		$guideDB = new IModel('guide');
		$guideData = $guideDB->query();

		$sql = array(
            "TRUNCATE TABLE `{pre}guide`",
            "alter table `{pre}guide` add column `id` int(11) unsigned NOT NULL;",
            "alter table `{pre}guide` drop primary key;",
            "alter table `{pre}guide` add primary key(id,_hash);",
            "alter table `{pre}guide` modify id int(11) auto_increment;",
            "alter table `{pre}guide` add column `seller_id` int(11) unsigned default '0' COMMENT '卖家ID';",
            "ALTER TABLE `{pre}guide` ADD INDEX(`seller_id`);",
            "ALTER TABLE `{pre}guide` DROP `order`;",

    		"alter table `{pre}ticket` add column `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动类型 0:全品类 1:只能用于某个商品 2:只能用于某个分类';",
    		"alter table `{pre}ticket` add column `condition` text NOT NULL COMMENT '活动生效条件 当type=0空,当type=1<商品ID>,type=2<商品分类ID>';",
    		"alter table `{pre}ticket` add column `limit_sum` decimal(15,2) NOT NULL COMMENT '满多少元可用';",

            "ALTER TABLE `{pre}banner` DROP `order`;",

            "alter table `{pre}ad_position` add `seller_id` int(11) unsigned DEFAULT '0' COMMENT '卖家ID';",
            "ALTER TABLE `{pre}ad_position` ADD INDEX(`seller_id`);",

            "alter table `{pre}ad_manage` add `seller_id` int(11) unsigned DEFAULT '0' COMMENT '卖家ID';",
            "ALTER TABLE `{pre}ad_manage` ADD INDEX(`seller_id`);",

		    "ALTER TABLE `{pre}goods` ADD `config` text COMMENT '商品配置参数'",

            "DROP TABLE IF EXISTS `{pre}goods_extend_preorder_discount`;",
            "CREATE TABLE `{pre}goods_extend_preorder_discount` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `goods_id` int(11) unsigned NOT NULL COMMENT '商品ID',
              `product_id` int(11) unsigned default '0' COMMENT '货品ID',
              `start_date` date NOT NULL COMMENT '服务开始日期',
              `end_date` date NOT NULL COMMENT '服务结束日期',
              `discount` decimal(15,2) NOT NULL COMMENT '浮动价格正负数',
              `seller_id` int(11) unsigned DEFAULT '0' COMMENT '商家ID',
              PRIMARY KEY (`id`),
              index (`goods_id`),
              index (`product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时间预订类商品价格浮动表';",


            "DROP TABLE IF EXISTS `{pre}goods_extend_preorder_disnums`;",
            "CREATE TABLE `{pre}goods_extend_preorder_disnums` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `goods_id` int(11) unsigned NOT NULL COMMENT '商品ID',
              `product_id` int(11) unsigned NOT NULL default '0' COMMENT '货品ID',
              `start_date` date NOT NULL COMMENT '服务开始日期',
              `end_date` date NOT NULL COMMENT '服务结束日期',
              `disnums` int(11) NOT NULL COMMENT '浮动库存正负数',
              `seller_id` int(11) unsigned DEFAULT '0' COMMENT '商家ID',
              PRIMARY KEY (`id`),
              index (`goods_id`),
              index (`product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时间预订类商品库存浮动表';",


            "DROP TABLE IF EXISTS `{pre}order_extend_preorder`;",
            "CREATE TABLE `{pre}order_extend_preorder` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
              `goods_id` int(11) unsigned NOT NULL COMMENT '商品ID',
              `product_id` int(11) unsigned NOT NULL default '0' COMMENT '货品ID',
              `goods_nums` int(11) unsigned NOT NULL default '1' COMMENT '商品数量',
              `start_date` date NOT NULL COMMENT '服务开始日期',
              `end_date` date NOT NULL COMMENT '服务结束日期',
              PRIMARY KEY (`id`),
              index (`order_id`),
              index (`goods_id`),
              index (`product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单扩展表预订类';",


            "DROP TABLE IF EXISTS `{pre}assemble`;",
            "CREATE TABLE `{pre}assemble` (
              `id` int(11) unsigned NOT NULL auto_increment,
              `is_close` tinyint(1) NOT NULL default '0' COMMENT '0开启,1关闭,2待审核',
              `goods_id` int(11) unsigned NOT NULL COMMENT '关联商品id',
              `assemble_price` decimal(15,2) NOT NULL default '0.00' COMMENT '拼团活动价格',
              `sell_price` decimal(15,2) NOT NULL default '0.00' COMMENT '商品原价格',
              `title` varchar(255) NOT NULL COMMENT '拼团标题',
              `sum_count` int(11) NOT NULL default '0' COMMENT '已销售量',
              `intro` varchar(255) default NULL COMMENT '介绍',
              `img` varchar(255) default NULL COMMENT '活动商品图片',
              `limit_nums` smallint(5) unsigned NOT NULL  COMMENT '达成成团人数条件',
              `sort` smallint(5) NOT NULL DEFAULT '99' COMMENT '排序',
              `seller_id` int(11) unsigned NOT NULL default '0' COMMENT '商家ID',
              `_hash` int(11) unsigned NOT NULL COMMENT '预留散列字段',
              PRIMARY KEY  (`id`,`_hash`),
              index (`is_close`),
              index (`goods_id`),
              index (`seller_id`),
              index (`sort`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='拼团表';",


            "DROP TABLE IF EXISTS `{pre}assemble_commander`;",
            "CREATE TABLE `{pre}assemble_commander` (
              `id` int(11) unsigned NOT NULL auto_increment,
              `assemble_id` int(11) unsigned NOT NULL COMMENT '拼团表ID',
              `user_id` int(11) unsigned NOT NULL COMMENT '团长用户ID',
              `user_name` varchar(255) NOT NULL COMMENT '团长用户名',
              `user_ico` varchar(255) NOT NULL COMMENT '团长头像',
              `member_nums` smallint(5) unsigned NOT NULL default '1' COMMENT '当前参团用户数量',
              `limit_nums` smallint(5) unsigned NOT NULL  COMMENT '达成成团人数条件',
              `is_finish` tinyint(1) NOT NULL default '0' COMMENT '0未成团,1已成团',
              `create_time` datetime NOT NULL COMMENT '生成时间',
              PRIMARY KEY  (`id`),
              index (`assemble_id`),
              index (`is_finish`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='拼团团长表';",


            "DROP TABLE IF EXISTS `{pre}assemble_active`;",
            "CREATE TABLE `{pre}assemble_active` (
              `id` int(11) unsigned NOT NULL auto_increment,
              `assemble_commander_id` int(11) unsigned NOT NULL COMMENT '拼团团长表ID',
              `user_id` int(11) unsigned NOT NULL COMMENT '拼团成员用户ID',
              `order_no` varchar(20) NOT NULL COMMENT '订单号',
              `is_pay` tinyint(1) NOT NULL default '0' COMMENT '0未支付,1已支付',
              `create_time` datetime NOT NULL COMMENT '生成时间',
              PRIMARY KEY  (`id`),
              index (`is_pay`),
              index (`assemble_commander_id`),
              index (`order_no`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='拼团组员报名表';",

		);

		foreach($sql as $key => $val)
		{
		    IDBFactory::getDB()->query( $this->_c($val) );
		}

		//还原guide数据
		if($guideData)
		{
		    foreach($guideData as $item)
		    {
		        unset($item['order']);
		        $guideDB->setData($item);
		        $guideDB->add();
		    }
		}

		//把plugin表里面输入写入到config/plugin_config.php里面
		$pluginDB = new IModel('plugin');
		$pluginData = $pluginDB->query();
		$insertData = [];
		foreach($pluginData as $key => $item)
		{
		    $insertData[$item['class_name']] = ['is_open' => $item['is_open'],'config_param' => $item['config_param']];
		}
		Config::edit('config/plugin_config.php',$insertData);

		//更新config.php在扩展中加入plugin_config.php文件
		$writeData = ['site_config' => 'config/site_config.php','plugin_config' => 'config/plugin_config.php'];
		Config::edit('config/config.php',['configExt' => $writeData]);

        //清空runtime缓存
		$runtimePath = IWeb::$app->getBasePath().'runtime';
		$result      = IFile::clearDir($runtimePath);
		die("升级成功!! V5.6版本");
	}

	public function _c($sql)
	{
		return str_replace('{pre}',IWeb::$app->config['DB']['tablePre'],$sql);
	}
}