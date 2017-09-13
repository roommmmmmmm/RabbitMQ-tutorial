<?php
/**
 *
 */
class Server
{
  private static $host;
  private static $user;
  private static $passwd;
  private static $response =array();
  function __construct($host='127.0.0.1',$user='guest',$passwd='guest')
  {
      self::$host=$host;
      self::$user=$user;
      self::$passwd=$passwd;
  }
   public function getChannel() {
    if (isset(self::$response['channel']) && null != self::$response['channel']) {
      return self::$response['channel'];
    }else {
      self::$response['connect']=new AMQPConnection();
      self::$response['connect']->setHost(self::$host);
      self::$response['connect']->setLogin(self::$user);
      self::$response['connect']->setPassword(self::$passwd);
      self::$response['connect']->pconnect();
      self::$response['channel']=$this->Channel(self::$response['connect']);
      return self::$response['channel'];
    }
  }
  /**
   * 创建一个连接
   */
  private function Channel($connect){
     return new AMQPChannel($connect);
  }
  /**
   * 创建一个路由
   */
   public function setExchange($channel){
     $exchange = new AMQPExchange($channel);
     $exchange->setType(AMQP_EX_TYPE_DIRECT);
     $exchange->setName('EX-durable');
     $exchange->setFlags(AMQP_DURABLE);
     $exchange->declareExchange();
     return $exchange;
   }
   /**
    * 创建一条队列
    */
    public function getQueue($channel,$routing_key,$flags=AMQP_DURABLE){
      $queue = new AMQPQueue($channel);
      $queue->setName($routing_key);
      $queue->setFlags(AMQP_DURABLE);
      $queue->declareQueue();
      $queue->bind('EX-durable','hello1');
    }
    /**
     * 发送信息
     */
     public function sendMessage($message,$routing_key){
     }
     /**
      * 关闭连接
      */
      public function Close(){
        self::$response['connect']->disconnect();
      }
}
$rabbit = new Server('192.168.204.70','root','root');
$rabbit_channel = $rabbit->getChannel();
// var_dump($rabbit_connect);
$rabbit_exchange = $rabbit->setExchange($rabbit_channel);
$rabbit->getQueue($rabbit_channel,'hello1');
$message = '123123';
for ($i=0; $i < 100000; $i++) { 
  $res = $rabbit_exchange->publish($message, 'hello1', AMQP_NOPARAM, array('delivery_mode'=>2));
  if ($res) {
    //$rabbit->Close();
    echo 'Congratulation!', PHP_EOL;
  }else {
    echo 'Send Error!', PHP_EOL;
  }
}
// var_dump($rabbit_exchange);
