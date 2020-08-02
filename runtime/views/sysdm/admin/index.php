<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理后台登录</title>
<link type="image/x-icon" href="<?php echo IUrl::creatUrl("")."favicon.ico";?>" rel="icon">
<link rel="stylesheet" href="<?php echo $this->getWebSkinPath()."css/admin.css";?>" />
<meta name="robots" content="noindex,nofollow">
<script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/artdialog/artDialog.js?v=20190606"></script><script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/artdialog/plugins/iframeTools.js"></script><link rel="stylesheet" type="text/css" href="/iWebShop5.6/runtime/_systemjs/artdialog/skins/black.css" />
<script type="text/javascript" charset="UTF-8" src="/iWebShop5.6/runtime/_systemjs/autovalidate/validate.js?v=20190606"></script><link rel="stylesheet" type="text/css" href="/iWebShop5.6/runtime/_systemjs/autovalidate/style.css" />
<script type='text/javascript' src='<?php echo IUrl::creatUrl("")."public/javascript/public.js";?>'></script>
</head>
<body class="skin-blue">
	<div class="wrapper" style="background: url(<?php echo $this->getWebViewPath()."skin/default/images/bg.jpg";?>) no-repeat;background-size: 100% 100%;">
		<div class="loginbox">
			<form action='<?php echo IUrl::creatUrl("/admin/login_act");?>' method='post'>
				<div class="form-group text-center">
					<h2 style="color:#fff"><strong>iWebShop后台管理</strong></h2>
				</div>

				<div class="form-group input-group input-group-lg">
					<span class="input-group-addon"><i class="fa fa-user" style="width:30px"></i></span>
					<input type="text" class="form-control" autocomplete="off" name="admin_name" placeholder="用户名" pattern="required" />
				</div>

				<div class="form-group input-group input-group-lg">
					<span class="input-group-addon"><i class="fa fa-key" style="width:30px"></i></span>
					<input type="password" class="form-control" name="password" autocomplete="off" placeholder="密码" pattern="required" />
				</div>

				<div class="form-group input-group input-group-lg">
					<span class="input-group-addon"><i class="fa fa-font" style="width:30px"></i></span>
					<input type="text" class="form-control" autocomplete="off" name='captcha' placeholder="验证码" pattern="required" />
					<span class="input-group-addon no-padding"><img onclick="changeCaptcha()" src='<?php echo IUrl::creatUrl("/simple/getCaptcha");?>' style='width:110px' id='captchaImg' /></span>
				</div>

				<div class="form-group"><button type="submit" class="btn btn-lg btn-warning btn-block"><strong>登 录</strong></button></div>
			</form>
		</div>
	</div>
	<footer class="main-footer">
		Power by  <a href="http://www.aircheng.com" target="_blank">www.aircheng.com</a> Copyright &copy; 2005-2018
	</footer>
</body>
</html>
