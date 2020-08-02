<!DOCTYPE html>
<html>

<head>
    <title>后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="<?php echo $this->getWebSkinPath()."css/admin.css";?>" />
    <!--[if lt IE 9]>
	<script src="<?php echo $this->getWebViewPath()."javascript/html5shiv.min.js";?>"></script>
	<script src="<?php echo $this->getWebViewPath()."javascript/respond.min.js";?>"></script>
	<![endif]-->
    <meta name="robots" content="noindex,nofollow">
    <link rel="shortcut icon" href="<?php echo IUrl::creatUrl("")."favicon.ico";?>" />
    <script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/jquery/jquery-1.12.4.min.js"></script> <script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/artdialog/artDialog.js?v=20190606"></script><script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/artdialog/plugins/iframeTools.js"></script><link rel="stylesheet" type="text/css" href="/iWebShop5.6/runtime/_systemjs/artdialog/skins/black.css" /> <script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/form/form.js"></script> <script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/autovalidate/validate.js?v=20190606"></script><link rel="stylesheet" type="text/css" href="/iWebShop5.6/runtime/_systemjs/autovalidate/style.css" /> <script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script> <script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/cookie/jquery.cookie.js"></script>
    <script type='text/javascript' src="<?php echo IUrl::creatUrl("")."public/javascript/twitter-bootstrap/3.3.7/js/bootstrap.min.js";?>"></script>
    <script type='text/javascript' src="<?php echo $this->getWebViewPath()."javascript/adminlte.min.js";?>"></script>
    <script type='text/javascript' src="<?php echo IUrl::creatUrl("")."public/javascript/public.js";?>"></script>
</head>

<body class="skin-blue fixed sidebar-mini" style="height: auto; min-height: 100%;">
    <div class="wrapper" style="height: auto; min-height: 100%;">
        <header class="main-header">
            <div class="logo">
                <span class="logo-mini"><b>iWeb</b></span>
                <span class="logo-lg"><b>iWebShop</b>后台管理</span>
            </div>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only"></span>
                </a>

                <!--顶部菜单 开始-->
                <div id="menu" class="navbar-custom-menu">
                    <ul class="nav navbar-nav" name="topMenu">
                        <?php $menuData=menu::init($this->admin['role_id']);?>
                        <?php foreach($items=menu::getTopMenu($menuData) as $key => $item){?>
                        <li><a hidefocus="true" href="<?php echo IUrl::creatUrl("".$item."");?>"><?php echo isset($key)?$key:"";?></a></li>
                        <?php }?>
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
                 <!--顶部菜单 结束-->
            </nav>
        </header>

		<!--左侧菜单 开始-->
        <aside id="admin_left" class="main-sidebar">
            <section class="sidebar" style="height: auto;">
                <div class="user-panel">
                    <div class="pull-left image">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="pull-left info">
                        <p><?php echo isset($this->admin['admin_name'])?$this->admin['admin_name']:"";?></p>
                        <a href="#"><?php echo isset($this->admin['admin_role_name'])?$this->admin['admin_role_name']:"";?></a>
                    </div>
                </div>

                <?php $leftMenu=menu::get($menuData,IWeb::$app->getController()->getId().'/'.IWeb::$app->getController()->getAction()->getId());$modelName = key($leftMenu);$modelValue = current($leftMenu);?>
                <ul class="sidebar-menu tree" data-widget="tree">
                    <li class="header"><?php echo isset($modelName)?$modelName:"";?>模块菜单</li>
                    <?php foreach($items=$modelValue as $key => $item){?>
                    <li class="treeview">
                        <a href="#">
                        	<i class="fa" name="ico" menu="<?php echo isset($key)?$key:"";?>"></i>
                            <span><?php echo isset($key)?$key:"";?></span>
                            <span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
                        </a>
                        <ul class="treeview-menu" name="leftMenu">
                            <?php foreach($items=$item as $leftKey => $leftValue){?>
                            <li><a href="<?php echo IUrl::creatUrl("".$leftKey."");?>"><i class="fa fa-circle-o"></i><?php echo isset($leftValue)?$leftValue:"";?></a></li>
                            <?php }?>
                        </ul>
                    </li>
                    <?php }?>

                    <?php foreach($items=Api::run('getQuickNavigaAll') as $key => $item){?>
                    <li class="header">快速导航</li>
                    <li><a href="<?php echo isset($item['url'])?$item['url']:"";?>"><i class="fa fa-star-o"></i> <span><?php echo isset($item['naviga_name'])?$item['naviga_name']:"";?></span></a></li>
                    <?php }?>
                </ul>
            </section>
        </aside>
        <!--左侧菜单 结束-->

		<!--右侧内容 开始-->
        <div id="admin_right" class="content-wrapper">
            <div class="content">
    <div class="content">
        <div class="bg-yellow tips">
            <?php $safeInstance = new safeStrategy();$checkResult = $safeInstance->check();?>
            <?php if($checkResult){?>
            <ul>
                <?php foreach($items=$checkResult as $key => $item){?>
                <li><?php echo isset($item['content'])?$item['content']:"";?></li>
                <?php }?>
            </ul>
            <?php }?>
        </div>

		<hr />

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua">
                        <i class="fa fa-comments"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">评论待回复</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/comment/comment_list/search[c.recomment_time]/=0");?>"><?php echo Api::run('commentWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red">
                        <i class="fa fa-comments-o"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">建议待回复</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/comment/suggestion_list/search[a.re_time=]/0");?>"><?php echo Api::run('suggestionWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green">
                        <i class="fa fa-twitch"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">咨询待回复</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/comment/refer_list/search[r.status=]/0");?>"><?php echo Api::run('referWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-orange">
                        <i class="fa fa-truck"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">付款待发货</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/order/order_list/search[distribution_status]/0/search[pay_status]/1");?>"><?php echo Api::run('orderWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-olive">
                        <i class="fa fa-group"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">团购活动申请</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/market/regiment_list");?>"><?php echo Api::run('regimentWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-teal">
                        <i class="fa fa-address-card"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">待审商家</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/member/seller_list/search[is_lock=]/1");?>"><?php echo Api::run('getSellerCount',array('is_lock'=>1));?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-light-blue">
                        <i class="fa fa-cube"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">待审商品</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/goods/goods_list/search[is_del]/3");?>"><?php echo Api::run('goodsWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple">
                        <i class="fa fa-money"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">待审提现</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/member/withdraw_list");?>"><?php echo Api::run('withdrawWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow">
                        <i class="fa fa-calculator"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">货款结算申请</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/market/bill_list");?>"><?php echo Api::run('billWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-maroon">
                        <i class="fa fa-thumbs-o-down"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">退款申请</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/order/refundment_list");?>"><?php echo Api::run('refundsCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-maroon">
                        <i class="fa fa-cog"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">维修申请</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/order/fix_list");?>"><?php echo Api::run('fixWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-maroon">
                        <i class="fa fa-retweet"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">换货申请</span>
                        <span class="info-box-number">
                            <a href="<?php echo IUrl::creatUrl("/order/exchange_list");?>"><?php echo Api::run('exchangeWaitCount');?></a>
                            <small>个</small>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <i class="fa fa-server"></i>
                        <h3 class="box-title">系统信息</h3>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <colgroup>
                                <col width="120px" />
                                <col />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>购买及服务</th>
                                    <th>
                                        <a href='http://wpa.qq.com/msgrd?v=3&uin=846327344&site=qq&menu=yes' target='_blank'>联系我们</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>当前版本号</th>
                                    <td><?php echo Common::getLocalVersion();?></td>
                                </tr>
                                <tr>
                                    <th>最新版本号</th>
                                    <td>...</td>
                                </tr>
                                <tr>
                                    <th>官网地址</th>
                                    <th><a href='http://www.aircheng.com' target='_blank'>www.aircheng.com</a></th>
                                </tr>
                                <tr>
                                    <th>服务器软件</th>
                                    <td><?php echo isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:"";?></td>
                                </tr>
                                <tr>
                                    <th>附件上传容量</th>
                                    <td><?php echo IUpload::getMaxSize();?></td>
                                </tr>
                                <tr>
                                    <th>授权信息</th>
                                    <td>...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <i class="fa fa-line-chart"></i>
                        <h3 class="box-title">基础统计</h3>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <colgroup>
                                <col width="120px" />
                                <col />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>商家数量</th>
                                    <td>
                                        <a href="<?php echo IUrl::creatUrl("/member/seller_list");?>"><?php echo Api::run('getSellerCount',array('is_lock'=>0));?></a>
                                        <small>家</small>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>销售总额</th>
                                    <td>
                                        <a href="<?php echo IUrl::creatUrl("/market/amount");?>"><?php echo Api::run('getOrderAmount');?></a>
                                        <small>元</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>注册用户</th>
                                    <td>
                                        <a href="<?php echo IUrl::creatUrl("/member/member_list");?>"><?php echo Api::run('getCountRegUser');?></a>
                                        <small>个</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>产品数量</th>
                                    <td>
                                        <a href="<?php echo IUrl::creatUrl("/goods/goods_list");?>"><?php echo Api::run('goodsCount');?></a>
                                        <small>个</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>品牌数量</th>
                                    <td>
                                        <a href="<?php echo IUrl::creatUrl("/brand/brand_list");?>"><?php echo Api::run('getBrandCount');?></a>
                                        <small>个</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>订单数量</th>
                                    <td>
                                        <a href="<?php echo IUrl::creatUrl("/order/order_list");?>"><?php echo Api::run('getCountOrder');?></a>
                                        <small>个</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>库存预警</th>
                                    <td>
                                    	<?php $store_num_warning = $this->_siteConfig->store_num_warning ? $this->_siteConfig->store_num_warning : 20?>
                                        <a href="<?php echo IUrl::creatUrl("/goods/goods_list/search[store_nums]/".$store_num_warning."");?>"><?php echo Api::run('getGoodsWarning',array('store_num_warning'=>$store_num_warning));?></a>
                                        <small>个</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-file-o"></i>
                <h3 class="box-title">最新10条等待处理订单</h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead>
                        <th>订单号</th>
                        <th>收货人</th>
                        <th>订单状态</th>
                        <th>金额</th>
                        <th>下单时间</th>
                        <th>操作</th>
                    </thead>
                    <tbody>
                        <?php foreach($items=Api::run('getNewsOrderList') as $key => $item){?>
                        <tr>
                            <td><?php echo isset($item['order_no'])?$item['order_no']:"";?></td>
                            <td><?php echo isset($item['accept_name'])?$item['accept_name']:"";?></td>
                            <td><?php echo Order_Class::getOrderPayStatusText($item);?></td>
                            <td><b class="text-red">￥<?php echo isset($item['order_amount'])?$item['order_amount']:"";?></b></td>
                            <td><?php echo isset($item['create_time'])?$item['create_time']:"";?></td>
                            <td>
                                <a href="<?php echo IUrl::creatUrl("/order/order_show/id/".$item['oid']."");?>">
                                	<i class='operator fa fa-eye'></i>
                                </a>

                            	<?php if($item['status']==1){?>
                            	<a href="<?php echo IUrl::creatUrl("/order/order_edit/id/".$item['oid']."");?>"><i class='operator fa fa-edit'></i></a>
                            	<?php }?>

                                <a href="javascript:delModel({link:'<?php echo IUrl::creatUrl("/order/order_del/id/".$item['oid']."");?>'})"><i class='operator fa fa-close'></i></a>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
        </div>
        <!--右侧内容 结束-->

		<!--顶部弹出菜单 开始-->
	    <aside class="control-sidebar control-sidebar-dark">
	        <ul class="control-sidebar-menu">
	            <li><a href="<?php echo IUrl::creatUrl("/admin/logout");?>"><i class="fa fa-circle-o text-red"></i> 退出管理</a></li>
	            <li><a href="<?php echo IUrl::creatUrl("/system/admin_repwd");?>"><i class="fa fa-circle-o text-yellow"></i> 修改密码</a></li>
	            <li><a href="<?php echo IUrl::creatUrl("/system/default");?>"><i class="fa fa-circle-o text-green"></i> 后台首页</a></li>
	            <li><a href="<?php echo IUrl::creatUrl("");?>" target='_blank'><i class="fa fa-circle-o text-aqua"></i> 商城首页</a></li>
	            <li><a href="<?php echo IUrl::creatUrl("/system/navigation");?>"><i class="fa fa-circle-o"></i> 快速导航</a></li>
	        </ul>
	    </aside>
	    <!--顶部弹出菜单 结束-->
    </div>
</body>
<script type='text/javascript'>
//图标配置
var icoConfig = {"商品管理":"fa-inbox","商品分类":"fa-list","品牌":"fa-registered","模型":"fa-cubes","搜索":"fa-search","会员管理":"fa-user-o","商户管理":"fa-group","信息处理":"fa-comment-o","订单管理":"fa-file-text","单据管理":"fa-files-o","发货地址":"fa-address-card-o","促销活动":"fa-bullhorn","营销活动":"fa-bell-o","优惠券管理":"fa-ticket","基础数据统计":"fa-bar-chart-o","后台首页":"fa-home","日志操作记录":"fa-file-code-o","商户数据统计":"fa-pie-chart","支付管理":"fa-credit-card","第三方平台":"fa-share-alt","配送管理":"fa-truck","地域管理":"fa-street-view","权限管理":"fa-unlock-alt","数据库管理":"fa-database","文章管理":"fa-file-o","帮助管理":"fa-question-circle-o","广告管理":"fa-flag","公告管理":"fa-bookmark-o","网站地图":"fa-sitemap","插件管理":"fa-cogs","网站管理":"fa-wrench"};
$('i[name="ico"]').each(function()
{
	var menuName = $(this).attr('menu');
	if(menuName && icoConfig[menuName])
	{
		$(this).addClass(icoConfig[menuName]);
	}
	else
	{
		//默认图标
		$(this).addClass("fa-circle");
	}
});

//兼容IE系列
$("[name='leftMenu'] [href^='javascript:']").each(function(i)
{
	var fun = $(this).attr('href').replace("javascript:","");
	$(this).attr('href','javascript:void(0)');
	$(this).on("click",function(){eval(fun)});
});

//按钮高亮
var topItem = "<?php echo $modelName;?>";
$("[name='topMenu']>li:contains('"+topItem+"')").addClass("active");

//获取左侧菜单项
function matchLeftMenu(leftItem)
{
    var matchObject = $('[name="leftMenu"]>li a[href="'+leftItem+'"]');
    if(matchObject.length > 0)
    {
        $.cookie('lastUrl', leftItem);
        return matchObject;
    }

    var lastUrl = $.cookie('lastUrl');
    if(lastUrl)
    {
        return $('[name="leftMenu"]>li a[href="'+lastUrl+'"]');
    }
    return null;
}

//左边栏菜单高亮
var leftItem   = "<?php echo IUrl::getUri();?>";
var matchObject = matchLeftMenu(leftItem);
if(matchObject)
{
    matchObject.parent().addClass("active").parent('ul').show().parent('.treeview').addClass('menu-open');
}
</script>
</html>