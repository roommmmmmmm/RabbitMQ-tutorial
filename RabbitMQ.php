<?php

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
        // self::$_connection = $connection = new AMQPConnection();
        // $connection->setHost('127.0.0.1');
        // $connection->setLogin('root');
        // $connection->setPassword('root');
        // $connection->connect();
        // $channel = new AMQPChannel($connection);
        // $exchange = new AMQPExchange($channel);
        // $exchange->setName("exchangeD");
        // $exchange->setType(AMQP_EX_TYPE_DIRECT);
        // $exchange->setFlags(AMQP_DURABLE);
        // $exchange->declareExchange();
        // $queue = new AMQPQueue($channel);
        // $queue->setName('test');
        // $queue->setFlags(AMQP_DURABLE);
        // $queue->bind($exchange->getName(), 'test');
        // $queue->declareQueue();
		// self::$_pool = $exchange;
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
        // return self::$_pool->publish($message, $queue, $flags = AMQP_NOPARAM, [ 'delivery_mode'=> 2 ]);
        return self::$_pool->publish($message, $queue);
    }

	public function close()
	{
		return self::$_connection->disconnect();
	}
}
