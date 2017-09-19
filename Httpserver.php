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
        //创建一个inotify句柄
        $fd = inotify_init();

        //监听文件，仅监听修改操作，如果想要监听所有事件可以使用IN_ALL_EVENTS
        $watch_descriptor = inotify_add_watch($fd, __DIR__, IN_MODIFY);

        //加入到swoole的事件循环中
        swoole_event_add($fd, function ($fd) {
            $events = inotify_read($fd);
            if ($events) {
                foreach ($events as $event) {
                    echo "项目文件", $event['name'] ,"发生了变化，重新启动", PHP_EOL;
                    $this->_serv->reload();
                    // echo "inotify Event :" . var_export($event, 1) . "\n";
                }
            }
        });
		// swoole_timer_tick(2000, function() {
		// //	clearstatcache(true, 'config.php');
		// //	$fmtime = filemtime('config.php');
		// 	clearstatcache(true, 'application.ini');
		// 	$fmtime = filemtime('application.ini');
		// 	if (empty($this->_confmtime)) {
		// 		echo '第一次载入配置文件', PHP_EOL;
		// 		$this->_confmtime = $fmtime;
		// 	}
		// 	if ($fmtime != $this->_confmtime) {
		// 		echo '配置文件发生了更改,更改时间为:', date('Y-m-d H:i:s', $fmtime), PHP_EOL;
		// 		$this->_confmtime = $fmtime;
		// 		$this->_serv->reload();
		// 	} else {
		// 		echo 'Nothing happened current memory use :', memory_get_usage(true) / 1024 / 1024, ' MB' , PHP_EOL;
		// 	}
		// //	var_dump(date('Y-m-d H:i:s', $fmtime));
		// //	var_dump('------------------'. $this->_count++);
		// });
	}


    public function onWorkerStart($serv, $id)
    {
		// include 'RabbitMQ.php';
        // // $this->_cn['pool'] = RabbitMQ::getConnection();
        // // 注册连接池
        // \Yaf\Registry::set('pool', RabbitMQ::getConnection());
        //
        // define('APPLICATION_PATH', __DIR__);
		// $this->_app = new \Yaf\Application( APPLICATION_PATH . "/conf/application.ini");
		// // ob_start();
		// $this->_app->bootstrap();
		// ob_end_clean();
    }

    public function onWorkerStop($serv, $work_id)
    {
        // $this->_cn['pool']->close();
        echo "项目文件发生了变化，重新启动", PHP_EOL;
		//var_dump('请求到了以后我把链接给关了重新fork了进程');
    }

    public function onRequest($request, $response)
    {
        // ob_start();
        // try {
        //     \Yaf\Registry::set('response', $response);
        //
        //     $yaf_request = new \Yaf\Request\Http($request->server['request_uri']);
        //     $yaf_request->setParam('method','POST');
        //     var_dump($yaf_request);
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
//$server = new HttpServer('0.0.0.0', 2333);
//$server->run();
