<?php
/**
 * @copyright (c) 2015 aircheng.com
 * @file mysqli_class.php
 * @brief mysqli数据库应用
 * @author nswe
 * @date 2017/11/1 23:41:53
 * @version 5.3
 * @notice 数据库所有写操作以action为最小单元进行事务区间。要支持事务必须要满足以下情况：
 *         1,mysql账号root或者具有super权限，可以动态更改数据库配置。
 *         2,表的引擎必须为：innodb索引
 */
/**
 * @class IMysqli
 * @brief MYSQLI数据库应用
 */
class IMysqli extends IDB
{
	/**
	 * @brief 数据库连接
	 * @param array $dbinfo 数据库的连接配制信息 [0]ip地址 [1]用户名 [2]密码 [3]数据库
	 * @return Boolean or resource 值: false:链接失败; resource类型:链接的资源句柄;
	 */
	public function connect($dbinfo)
	{
		$hostArray = explode(':',$dbinfo['host']);
		$hostPort  = isset($hostArray[1]) ? $hostArray[1] : ini_get("mysqli.default_port");

	  	$linkRes   = new mysqli($hostArray[0],$dbinfo['user'],$dbinfo['passwd'],$dbinfo['name'],$hostPort);
	  	if($linkRes->connect_error)
	  	{
	  		throw new IException($linkRes->connect_error,1000);
	  		return false;
	  	}
	  	else
	  	{
		  	$DBCharset = isset(IWeb::$app->config['DB']['charset']) ? IWeb::$app->config['DB']['charset'] : 'utf8';
		  	$linkRes->set_charset($DBCharset);

			/*只有拥有MYSQL的SUPER权限才可以做临时环境的调整，否则无效*/
		  	//宽松的SQL执行
		  	$linkRes->query("SET SESSION sql_mode = '' ");

		  	//设置事务级别和日志方式
		  	$linkRes->query("SET SESSION tx_isolation='read-uncommitted' ");
		  	$linkRes->query("SET SESSION binlog_format='MIXED' ");

		  	//设置时区东八区
		  	if(IWeb::$app->config['timezone'] == 'Asia/Shanghai')
		  	{
    		  	$linkRes->query("set global time_zone = '+8:00' ");
    		  	$linkRes->query("set time_zone = '+8:00' ");
		  	}
		  	return $linkRes;
	  	}
	}

	/**
	 * @brief 获取数据库内容
	 * @param $connect resource 连接对象
	 * @param $sql     string   SQL语句
	 * @param $type    string   返回数据的键类型
	 * @return array or Boolean 查询结果集
	 */
	public function read($connect,$sql,$type = MYSQLI_ASSOC)
	{
		$result   = array();
		$resource = $connect->query($sql);

		if($resource)
		{
			while($data = $resource->fetch_array($type))
			{
				$result[] = $data;
			}
			$resource->free();
			return $result;
		}
		return false;
	}

	/**
	 * @brief 写入操作
	 * @param $connect resource 连接对象
	 * @param $sql     string   SQL语句
	 * @return int or boolean 失败:false; 成功:影响的结果数量;
	 */
	public function write($connect,$sql)
	{
		$result = $connect->query($sql);

		if($result==true)
		{
			$sqlType = $this->getSqlType($sql);
			switch($sqlType)
			{
				case "insert":
				case "replace":
				{
					return $connect->insert_id;
				}
				break;

				case "update":
				{
					return $connect->affected_rows;
				}
				break;

				default:
				{
					return $result;
				}
			}
		}
		return false;
	}

	/**
	 * @brief 关闭自动提交
	 * @param $connect resource 连接对象
	 * @return boolean
	 */
	public function autoCommit($connect)
	{
		$dataArray = $this->read($connect,"SELECT @@tx_isolation as isolation");
		if($dataArray && $dataArray[0]['isolation'] == "READ-UNCOMMITTED")
		{
			return $connect->autocommit(false);
		}
		return $connect->autocommit(true);
	}

	/**
	 * @brief 提交事务
	 * @param $connect resource 连接对象
	 * @return boolean
	 */
	public function commitTran($connect)
	{
		return $connect->commit();
	}

	/**
	 * @brief 回滚事务
	 * @param $connect resource 连接对象
	 * @return boolean
	 */
	public function rollbackTran($connect)
	{
		return $connect->rollback();
	}

	/**
	 * @brief 关闭数据库
	 * @param $connect resource 连接对象
	 * @return boolean
	 */
	public function close($connect)
	{
		return $connect->close();
	}
}