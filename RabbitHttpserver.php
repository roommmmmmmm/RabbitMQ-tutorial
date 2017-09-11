<?php
require 'RabbitMQ.php';
class HttpServer
{
    private $_serv = null;
    private $_cn = array();
    public function run($ip, $port)
    {
        $this->_serv = new swoole_http_server($ip, $port);
        $this->_serv->set(
            [
                'worker_num' => 4,
                'log_file'   => '/tmp/swoole_dbpool.log',
            ]
        );
        $this->_serv->on('request', array($this, 'onRequest'));
        $this->_serv->on('workerstart', array($this, 'onWorkerStart'));
        $this->_serv->start();
    }

    public function onWorkerStart($serv, $id)
    {
        $this->_cn['pool'] = RabbitMQ::getAMQPLibConnection();
    }

    public function onRequest($request, $response)
    {
        // var_dump($request->server);
        $message = 'Hello World!';
        $res = $this->_cn['pool']->push($message);
        // $res = $this->_cn['pool']->publish($message, 'test');
        if ($res){
            $response->end("<h1>success!</h1>");
        } else {
            $response->end("<h1>fail!</h1>");
        }
        // $response->header('Content-Type', 'text/html;charset=utf-8');
        // $response->end($this->_cn['conn']);
        // $response->end("<h1>Hello Swoole. . #".rand(1000, 9999)."</h1>");
    }
}
$server = new HttpServer();
$server->run('0.0.0.0', '8080');
