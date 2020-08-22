<?php
use Workerman\Worker;
use think\Db;
require_once __DIR__ . '/vendor/autoload.php';
$http_worker = new Worker("http://0.0.0.0:20000");
$http_worker->name = 'Pay Api Server';
$http_worker->count = 4;

Db::setConfig(['type'=> 'sqlite','database'=> __DIR__.'/database.db','prefix'=> '','debug'=> true]);
$http_worker->onMessage = function($connection, $data)
{
	include __DIR__.'/config.php';
	$Nowtime = time();
	$EndPaytime = ($Endtime*60)+time();
	$orderid = date("YmdHms").rand(1,9).rand(1,9).rand(1,9).rand(1,9);
    if(!@$_REQUEST['appkey'] || !@$_REQUEST['payid'] || !@$_REQUEST['type'] || !@$_REQUEST['price'] || !@$_REQUEST['notifyurl'] || !@$_REQUEST['sign']){
		$connection->send(json_encode(array('code' => 500,'msg' => '参数不全')));
		return ;
	}
	if(Db::table('payinfo')->where('payid',$_REQUEST['payid'])->find()){
        $connection->send(json_encode(array('code' => 500,'msg' => '订单号重复')));
		return ;
	}
	if(trim($_REQUEST['type']) != 'alipay' && trim($_REQUEST['type']) != 'wechat'){
		$connection->send(json_encode(array('code' => 500,'msg' => '支付接口不存在')));
		return ;
	}
	$PaySign = md5($_REQUEST['type'].$_REQUEST['payid'].$_REQUEST['price'].$_REQUEST['notifyurl'].$AppKey);
	if(trim($_REQUEST['sign']) != $PaySign){
		$connection->send(json_encode(array('code' => 500,'msg' => 'Sign不正确')));
		return ;
	}
	if(!is_numeric(trim($_REQUEST['price']))){
		$connection->send(json_encode(array('code' => 500,'msg' => '金额必须为number类型')));
		return ;
	}
	if(filter_var(trim($_REQUEST['notifyurl']), FILTER_VALIDATE_URL) === FALSE) {
        $connection->send(json_encode(array('code' => 500,'msg' => 'NotifyUrl错误')));
		return ;
    }
	if(!(Db::table('payinfo')->insert(["price" => trim($_REQUEST['price']),"time" => $Nowtime,"endtime" => $EndPaytime,"status" => 'waitpay',"payid" => $_REQUEST['payid'],"notifyurl" => $_REQUEST['notifyurl'],"type" => $_REQUEST['type'],"orderid" => $orderid]))){
        $connection->send(json_encode(array('code' => 500,'msg' => '数据添加错误')));
		return ;
	}else{
		$connection->send(json_encode(array('code' => 200,'msg' => '订单添加成功','data' => array('maketime'=>$Nowtime,'stoptime'=>$EndPaytime,'orderid'=>$orderid,'timeout'=>$Endtime))));
	}
};

if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}