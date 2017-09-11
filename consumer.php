<?php
require_once '__lockApp.php';
//Establish connection AMQP
$connection = new AMQPConnection();
$connection->setHost('192.168.204.70');
$connection->setLogin('root');
$connection->setPassword('root');
$connection->connect();
//Create and declare channel
$channel = new AMQPChannel($connection);
$routing_key = 'pool';
$callback_func = function(AMQPEnvelope $message, AMQPQueue $q) use (&$max_jobs) {
	echo " [x] Received: ", $message->getBody(), PHP_EOL;
	sleep(sleep(substr_count($message->getBody(), '.')));
	echo " [X] Done", PHP_EOL;
	$q->ack($message->getDeliveryTag());
};
try{
	$queue = new AMQPQueue($channel);
	$queue->setName($routing_key);
	$queue->setFlags(AMQP_DURABLE);
	$queue->declareQueue();
	echo ' [*] Waiting for logs. To exit press CTRL+C', PHP_EOL;
	$queue->consume($callback_func);
}catch(AMQPQueueException $ex){
	print_r($ex);
}catch(Exception $ex){
	print_r($ex);
}
$connection->disconnect();
