<?php
class HttpServer
{
    private $_serv = null;
    private $_cn = array();
    public function run($ip, $port)
    {
        $this->_serv = new swoole_http_server($ip, $port);
        $this->_serv->set(
            [
                'worker_num' => 6,
                'log_file'   => '/tmp/swoole_dbpool.log',
				'max_request' => 20000,
				//'daemonize' => 1
            ]
        );
        $this->_serv->on('request', array($this, 'onRequest'));
        $this->_serv->on('workerstart', array($this, 'onWorkerStart'));
        $this->_serv->on('workerstop', array($this, 'onWorkerStop'));
        $this->_serv->start();
    }

    public function onWorkerStart($serv, $id)
    {
		require 'RabbitMQ.php';
        $this->_cn['pool'] = RabbitMQ::getConnection(AMQP_DURABLE);
    }

    public function onWorkerStop($serv, $work_id)
    {
        $this->_cn['pool']->close();
		//var_dump('请求到了以后我把链接给关了重新fork了进程');
    }

    public function onRequest($request, $response)
    {
		$message = $request->get['msg'];
        $res = $this->_cn['pool']->push($message, 'pool');
        // $res = $this->_cn['pool']->publish($message, 'test');
        if ($res){
            $response->end("<h1>success!</h1>");
        } else {
            $response->end("<h1>fail!</h1>");
        }
    }
}
$server = new HttpServer();
$server->run('0.0.0.0', '2333');
