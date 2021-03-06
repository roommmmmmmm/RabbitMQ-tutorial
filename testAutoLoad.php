<?php
date_default_timezone_set('Asia/Shanghai');
use Zend\Config\Ini;
class HttpServer
{
    private $_serv = null;
    private $_cn = array();
	private $_process = null;
	private $_confmtime = null; // 这是用来检查配置文件是否进行了更改

    private $_app;

	public function __construct($address = '127.0.0.1', $port = 80)
	{
        $this->_serv = new swoole_http_server($address, $port);
        $this->_serv->set(
            [
                'worker_num' => 4,
                'log_file'   => '/tmp/swoole_dbpool.log',
				'max_request' => 20000,
				//'daemonize' => 1
            ]
        );

	}

    public function run()
    {
		$this->_process = new swoole_process([$this, 'autoLoadConfig']);
		$this->_serv->addProcess($this->_process);

        $this->_serv->on('request', array($this, 'onRequest'));
        $this->_serv->on('workerstart', array($this, 'onWorkerStart'));
        $this->_serv->on('workerstop', array($this, 'onWorkerStop'));
        $this->_serv->start();
    }

	public function autoLoadConfig()
	{
		swoole_timer_tick(2000, function() {
		//	clearstatcache(true, 'config.php');
		//	$fmtime = filemtime('config.php');
			clearstatcache(true, 'application.ini');
			$fmtime = filemtime('application.ini');
			if (empty($this->_confmtime)) {
				// echo '第一次载入配置文件', PHP_EOL;
				$this->_confmtime = $fmtime;
			}
			if ($fmtime != $this->_confmtime) {
				// echo '配置文件发生了更改,更改时间为:', date('Y-m-d H:i:s', $fmtime), PHP_EOL;
				$this->_confmtime = $fmtime;
				$this->_serv->reload();
			} else {
				// echo 'Nothing happened current memory use :', memory_get_usage(true) / 1024 / 1024, ' MB' , PHP_EOL;
			}
		//	var_dump(date('Y-m-d H:i:s', $fmtime));
		//	var_dump('------------------'. $this->_count++);
		});
	}


    public function onWorkerStart($serv, $id)
    {
		opcache_reset();
		include 'dispatch.php';
		$this->_app = Dispatch::getInstance();
    }

    public function onWorkerStop($serv, $work_id)
    {
        //$this->_cn['pool']->close();
		$this->_app = null;
		var_dump('进程被重新启动了');
    }

    public function onRequest($request, $response)
    {
			if ($this->_app) {
				$this->_app->route($request, $response);
			
			} else {
				$reponse->status(500);
             	$response->end("Error");
			}
        // ob_start();
       // try {
       //     \Yaf\Registry::set('response', $response);

       //     $yaf_request = new \Yaf\Request\Http($request->server['request_uri']);
       //     //var_dump($yaf_request);
       //     $this->_app->getDispatcher()->dispatch($yaf_request);
       // } catch ( \Yaf\Exception $e ) {
       //     var_dump( $e );
       // }

        // $result = ob_get_contents();
        // ob_end_clean();
        // var_dump($result);

		// $message = '1123124324324';
		// //$message = $request->get['msg'];
        // $res = $this->_cn['pool']->push($message, 'pool');
        // // $res = $this->_cn['pool']->publish($message, 'test');
        // if ($res){
        //     $response->end("<h1>success!</h1>");
        // } else {
        //     $response->end("<h1>fail!</h1>");
        // }
    }
}
$server = new HttpServer('0.0.0.0', 2333);
$server->run();
