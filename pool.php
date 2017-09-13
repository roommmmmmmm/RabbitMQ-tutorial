<?php
require_once __DIR__ . '/vendor/autoload.php';
/**
 * 连接池,保存对应连接
 *
 *
 */
class ConnectPool
{
	public $_transient = array();
	public $_durable = array();
	public $_connection = array();
	public $_channel = array();

	public function __construct()
	{
		$this->_connection[] = $connection = new AMQPConnection();
		$connection->setHost('192.168.204.70');
		$connection->setLogin('root');
		$connection->setPassword('root');
		$connection->connect();
		//Create and declare channel
		$this->_channel[] = new AMQPChannel($connection);	
		$this->_channel[] = new AMQPChannel($connection);	
	}

}

