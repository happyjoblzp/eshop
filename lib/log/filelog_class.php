<?php
/**
 * @copyright (c) 2011 aircheng.com
 * @file filelog_class.php
 * @brief 文本格式日志
 * @author nswe
 * @date 2016/6/6 22:42:55
 * @version 4.5
 */

/**
 * @class IFileLog
 * @brief 文本格式日志处理类
 */
class IFileLog implements ILog
{
	//默认文件日志存放目录
	private $path;

	/**
	 * @brief 文件日志类的构造函数
	 */
	function __construct($path)
	{
		$logPath    = IWeb::$app->getBasePath();
		$logPath   .= isset(IWeb::$app->config['logs']) ? trim(IWeb::$app->config['logs']['path'],"/") : "backup/logs";
		$logPath   .= "/".trim($path,"/");
		$this->path = $logPath;
	}

	/**
	 * @brief  写入日志
	 * @param  mixed $logs 记录日志内容 支持string和array形式
	 * @return bool   操作结果
	 */
	public function write($logs = "")
	{
		if(!$this->path)
		{
			throw new IException('the file path is undefined');
		}

		//生成路径
		$fileName = $this->path;
		if(!file_exists($dirname = dirname($fileName)))
		{
			IFile::mkdir($dirname);
		}

		if(is_array($logs))
		{
		    $logs['时间'] = ITime::getDateTime();
		    foreach($logs as $key => $item)
		    {
		        if(is_array($item))
		        {
		            $item = var_export($item,true)."\n";
		            $content[] = $key.":".$item;
		        }
		        else
		        {
		            $content[] = $key.":".$item."\t";
		        }
		    }
		    $result = join("",$content);
		}
		else
		{
		    $result = $logs;
		}
		return error_log($result."\n\n", 3 ,$fileName);
	}
}