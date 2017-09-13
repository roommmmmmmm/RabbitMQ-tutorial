<?php
use Zend\Config\Ini;
class RabbitMQ
{
    private static $_pool = null;
    private static $_connection = null;

    public static function getConnection()
    {
        return new RabbitMQ();
    }

	public function  __construct()
	{

		$ini = new Ini('application.ini');
		if (!empty($conf = $ini->toArray())) {
			// echo $conf['core']['rabbit']['host'], PHP_EOL;
			// var_dump($ini->get('core')->get('rabbit')->toArray());
			echo '不打印数组111' , PHP_EOL;
		}
        self::$_connection = $connection = new AMQPConnection();
        $connection->setHost('127.0.0.1');
        $connection->setLogin('root');
        $connection->setPassword('root');
        $connection->connect();
        $channel = new AMQPChannel($connection);
        $exchange = new AMQPExchange($channel);
        $queue = new AMQPQueue($channel);
		self::$_pool = $exchange;
	}

    public function push($message, $queue)
    {
        return self::$_pool->publish($message, $queue);
    }

	public function close()
	{
		return self::$_connection->disconnect();
	}
}
