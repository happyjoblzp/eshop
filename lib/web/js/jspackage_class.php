<?php
/**
 * @copyright Copyright(c) 2010 aircheng.com
 * @file jspackage_class.php
 * @brief 系统JS包加载类文件
 * @author nswe
 * @date 2014/10/12 21:05:27
 * @version 2.7
 * @update 4.7
 * 更新了lazyload惰性加载图片
 */

 /**
  * @class IJSPackage
  * @brief IJSPackage 系统JS加载类
  */
class IJSPackage
{
	//系统JS注册表
	private static $JSPackages = array(
		'jquery' => array(
			'js' => array(
				'jquery/jquery-1.12.4.min.js',
			)
		),

		'form' => array('js' => 'form/form.js'),

		'dialog' => array(
			'js' => array(
				'artdialog/artDialog.js?v=20190606',
				'artdialog/plugins/iframeTools.js'
			),
			'css' => 'artdialog/skins/black.css'
		),

		'layer' => array(
			'js' => 'layer/layer.js',
		),

		'layer_mobile' => array(
			'js' => array(
				'layer/mobile/layer.js',
				'layer/mobile/init.js',
			),
			'css'=> 'layer/mobile/need/layer.css',
		),

		'kindeditor' => array(
			'js' => array(
				'editor/kindeditor-min.js',
			),
			'callback' => 'initKindEditior'
		),

		'validate' => array(
			'js'=>'autovalidate/validate.js?v=20190606',
			'css'=>'autovalidate/style.css'
		),

		'my97date' => array('js' => 'my97date/wdatepicker.js'),

		'artTemplate' => array(
			'js' => array(
				'artTemplate/artTemplate.js',
				'artTemplate/artTemplate-plugin.js'
			)
		),
		'cookie' => array('js' => 'cookie/jquery.cookie.js'),

		'chart' => array('js' => 'echarts/echarts.simple.min.js'),

		'areaSelect' => array('js' => 'areaSelect/areaSelect.js'),

		'zoom' => array(
			'js' => 'jqueryZoom/jquery.imagezoom.min.js',
			'css' => 'jqueryZoom/imagezoom.css',
		),

		'bxSlider' => array(
			'js' => 'jquerySlider/jquery.bxslider.min.js',
			'css' => 'jquerySlider/jquery.bxslider.css',
		),

		'fileUpload' => array(
			'js' => array(
				'jqueryFileUpload/jquery.ui.widget.js',
				'jqueryFileUpload/jquery.iframe-transport.js',
				'jqueryFileUpload/jquery.fileupload.js',
			),
		),

		'lazyLoad' => array(
			'js' => 'jqueryLazyLoad/jquery.lazyload.js',
			'callback' => 'initLazyLoad',
		),

		'table' => array(
			'js'  => 'jqueryTable/jquery.restable.js',
			'css' => 'jqueryTable/jquery.restable.css',
		),
	);

	/**
	 * @brief 加载系统的JS方法
	 * @param $name    string
	 * @param $charset string
	 * @return String
	 */
	public static function load($name,$charset='UTF-8')
	{
		if(!isset(self::$JSPackages[$name]))
		{
			return '';
		}

		$dir       = self::getFileOrDir(self::$JSPackages[$name]);
		$realjspath= IWeb::$app->getRuntimePath().'_systemjs/'.$dir;

		//如果没有创建就开始拷贝文件
		if(!file_exists($realjspath))
		{
			IFile::xcopy(dirname(__FILE__).'/source/'.$dir,$realjspath);
		}

		$webjspath    = IWeb::$app->getWebRunPath().'_systemjs/';
		$resultString = '';
		foreach(self::$JSPackages[$name] as $key => $val)
		{
			switch($key)
			{
				case "js":
				{
					if(is_array($val))
					{
						foreach($val as $file)
						{
							$resultString .= self::getJsHtml($webjspath.$file,$charset);
						}
					}
					else
					{
						$resultString .= self::getJsHtml($webjspath.$val,$charset);
					}
				}
				break;

				case "css":
				{
					if(is_array($val))
					{
						foreach($val as $file)
						{
							$resultString .= self::getCssHtml($webjspath.$file,$charset);
						}
					}
					else
					{
						$resultString .= self::getCssHtml($webjspath.$val,$charset);
					}
				}
				break;

				case "callback":
				{
					$resultString .= call_user_func(array('IJSPackage',$val));
				}
				break;
			}
		}

		return $resultString;
	}

	/**
	 * 获取文件或者目录
	 */
	private static function getFileOrDir($pathInfo)
	{
		if(is_array($pathInfo))
		{
			return self::getFileOrDir(current($pathInfo));
		}
		else
		{
			return dirname($pathInfo);
		}
	}

	/**
	 * @brief 获取JS的html
	 */
	private static function getJsHtml($fileName,$charset = 'utf-8')
	{
		return '<script type="text/javascript" charset="'.$charset.'" src="'.$fileName.'"></script>';
	}

	/**
	 * @brief 获取CSS的html
	 */
	private static function getCssHtml($fileName,$charset = 'utf-8')
	{
		return '<link rel="stylesheet" type="text/css" href="'.$fileName.'" />';
	}

	/**
	 * @brief 输出脚本
	 */
	private static function getCallback($code,$charset = 'utf-8')
	{
		return '<script type="text/javascript" charset="'.$charset.'">'.$code.'</script>';
	}

	/**
	 * @brief kindeditor的参数设置
	 */
	private static function initKindEditior()
	{
		$result = '<script type="text/javascript">';
		$result.= 'window.KindEditor.options.uploadJson = "'.IUrl::creatUrl('/pic/upload_json').'";';
		$result.= '</script>';
		return $result;
	}

	/**
	 * @brief lazyLoad惰性加载
	 */
	private static function initLazyLoad()
	{
		return '<script>$(function(){new LazyLoad();});</script>';
	}
}