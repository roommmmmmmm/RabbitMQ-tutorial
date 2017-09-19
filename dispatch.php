<?php
class Dispatch
{
	private $_app = null;

	public function __construct()
	{
        define('APPLICATION_PATH', __DIR__);
		$this->_app = new \Yaf\Application( APPLICATION_PATH . "/conf/application.ini");
	}

	public static function getInstance()
	{
		return new self();
	}

	public function route($request, $response)
	{
		if ($this->_app) {
        	//ob_start();
			try {
				\Yaf\Registry::set('response', $response);
				$method = $request->server['request_method'];
				$requestUri = $request->server['request_uri']; 
				$route = explode('/', trim($requestUri, '/'));
				var_dump($route);
				if (!empty($route) && count($route) >=3) {
					list($module, $controller, $action) = $route;
					echo 'module is ', $module, PHP_EOL;
					echo 'controller is ', $controller, PHP_EOL;
					echo 'action is ', $action, PHP_EOL;
					array_shift($route);
					array_shift($route);
					array_shift($route);
				} else {
					throw new \Yaf\Exception('非法请求');
				}
				
				var_dump($route);
				$yaf_request = new \Yaf\Request\Simple($method, $module, $controller, $method);
				//var_dump($yaf_request);
				$this->_app->getDispatcher()->dispatch($yaf_request);
         	//	$result = ob_get_contents();
         	//	ob_end_clean();
			//	return $result;
			} catch (\Yaf\Exception $e ) {
				$response->status(404);
				$response->end($e->getMessage());
				//var_dump($e);
			}
		} else {
			return false;
		}
	}
}
