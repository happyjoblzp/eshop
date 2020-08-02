<?php
return array(
	'logs'=>array(
		'path'=>'backup/logs',
		'type'=>'file'
	),
	'DB'=>array(
		'type'=>'mysqli',
        'tablePre'=>'iwebshop_',
		'read'=>array(
			array('host'=>'localhost:3306','user'=>'iwebshop','passwd'=>'iwebshop','name'=>'iwebshop'),
		),

		'write'=>array(
			'host'=>'localhost:3306','user'=>'iwebshop','passwd'=>'iwebshop','name'=>'iwebshop',
		),
	),
	'interceptor' => array('themeroute@onCreateController','layoutroute@onCreateView','plugin'),
	'langPath' => 'language',
	'viewPath' => 'views',
	'skinPath' => 'skin',
    'classes' => 'classes.*',
    'rewriteRule' =>'pathinfo',
	'theme' => array('pc' => array('huawei' => 'default','sysdm' => 'default','sysseller' => 'default'),'mobile' => array('mobile' => 'default','sysdm' => 'default','sysseller' => 'default')),
	'timezone'	=> 'Etc/GMT-8',
	'upload' => 'upload',
	'dbbackup' => 'backup/database',
	'safe' => 'cookie',
	'lang' => 'zh_sc',
	'debug'=> '0',
	'configExt'=> array('site_config'=>'config/site_config.php','plugin_config'=>'config/plugin_config.php'),
	'encryptKey'=>'1678ce5d911cbd78410f34549fd9149c',
	'authorizeCode' => '',
	'uploadSize' => '10',
	'low_withdraw' => '1',
	'low_bill' => '0',
);
?>
