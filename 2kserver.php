<?php
//Establish connection to AMQP
$connection = new AMQPConnection();
$connection->setHost('192.168.204.70');
$connection->setLogin('root');
$connection->setPassword('root');
$connection->connect();
//Create and declare channel
$channel = new AMQPChannel($connection);
//AMQPC Exchange is the publishing mechanism
$exchange = new AMQPExchange($channel);
try{
	$routing_key = 'no-bind-exchange';
	$queue = new AMQPQueue($channel);
	$queue->setName($routing_key);
	$queue->setFlags(AMQP_DURABLE);
	$queue->declareQueue();
  $message = 'dasdsadddddddddddddddddddddddddddddddddddddddddddddddddddddddddssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssds,kmmmmmmmmmmmmlkkljkasddddddddddddsadddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd+65+5as6dbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb+5+56+5322adasdasdasddsasssssssssssssssssssssssssaaaaaasaddddddddddddddddddddddddddddddddddddddddddddcxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzfgdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfaaaaaadrrrrrrrrrrrrdddddddddddddddddddddddddddddddddddcvbbbbbbbbbbbbbbbbbbbbbbzcccccccccccccccccccccccccccccccccccccccccccccccasddddddddddddddddddddddddddddddddddddddddaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaazxccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccZXCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCc vxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxdsfffffffffffffffffffffffffffffffffffffffffdasdsadddddddddddddddddddddddddddddddddddddddddddddddddddddddddssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssds,kmmmmmmmmmmmmlkkljkasddddddddddddsadddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd+65+5as6bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb+5+56+5322adasdasdasddsasssssssssssssssssssssssssaaaaaasaddddddddddddddddddddddddddddddddddddddddddddcxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzxzfgdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfaaaaaadrrrrrrrrrrrrdddddddddddddddddddddddddddddddddddcvbbbbbbbbbbbbbbbbbbbbbbzcccccccccccccccccccccccccccccccccccccccccccccccasddddddddddddddddddddddddddddddddddddddddaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaazxccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccZXCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCc vxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxdsfffffffffffffffffffffffffffffffffffffffff';
  for ($i=0; $i < 100000; $i++) { 
  	$res = $exchange->publish($message, $routing_key, AMQP_NOPARAM, array('delivery_mode'=>2));
  	if ($res) {
		    echo 'Congratulation!', PHP_EOL;
	}else {
		    echo 'Send Error!', PHP_EOL;
	}
  }
  
//	$connection->disconnect();
}catch(Exception $ex){
	print_r($ex);
}
