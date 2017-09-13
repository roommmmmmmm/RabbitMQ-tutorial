<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class DirectController extends \Yaf\Controller_Abstract {

	/**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/pool/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function indexAction() {
		//1. fetch query
		// $get = $this->getRequest()->getQuery("get", "default value");

		// echo "this is direct";

        $pool = \Yaf\Registry::get('pool');
        $response = \Yaf\Registry::get('response');
        // $isSuc = $pool->push('12312324', 'pool');
        // var_dump($isSuc);
        $response->end('success');
		return FALSE;
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
	}
}
