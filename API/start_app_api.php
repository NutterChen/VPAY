<?php
use Workerman\Worker;
use think\Db;
require_once __DIR__ . '/vendor/autoload.php';
$http_worker = new Worker("http://0.0.0.0:20001");
$http_worker->name = 'App Api Server';
$http_worker->count = 4;

Db::setConfig(['type'=> 'sqlite','database'=> __DIR__.'/database.db','prefix'=> '','debug'=> true]);
$http_worker->onMessage = function($connection, $data)
{
	include __DIR__.'/config.php';
	$Action = (explode('?',trim(substr($_SERVER['REQUEST_URI'],1))))[0];
	if($Action == 'appHeart'){
        $t = @$_REQUEST['t'];
        $_sign = $t.$AppKey;
        if (md5($_sign)!=@$_REQUEST["sign"]){
			$connection->send(json_encode(array("code" => -1, "msg" => 'Sign Error', "data" => null)));
			return ;
        }
        $jg = time()*1000 - $t;
        if ($jg>50000 || $jg<-50000){
			$connection->send(json_encode(array("code" => -1, "msg" => 'Client Time error', "data" => null)));
			return ;
        }
		$connection->send(json_encode(array("code" => 1, "msg" => 'Success', "data" => null)));
		return ;
	}elseif($Action == 'appPush'){
        $t = @$_REQUEST["t"];
        $type = @$_REQUEST["type"];
        $price = @$_REQUEST["price"];
        $_sign = $type.$price.$t.$AppKey;
        if (md5($_sign)!=@$_REQUEST["sign"]){
			$connection->send(json_encode(array("code" => -1, "msg" => 'Sign Error', "data" => null)));
			return ;
        }
		if($type == '1'){
			$type = 'wechat';
		}elseif($type == '2'){
			$type = 'alipay';
		}else{
			$connection->send(json_encode(array("code" => -1, "msg" => 'Pay Type Error', "data" => null)));
			return ;
		}
        $jg = time()*1000 - $t;
        if ($jg>50000 || $jg<-50000){
			$connection->send(json_encode(array("code" => -1, "msg" => 'Client Time error', "data" => null)));
			return ;
        }
        $res = Db::table('payinfo')->where("price",$price)->where("status",'waitpay')->where("type",$type)->find();
        if ($res){
            Db::table('payinfo')->where("id",$res['id'])->update(array("status"=>'waitnotify',"paytime"=>time()));
			$connection->send(json_encode(array("code" => 1, "msg" => 'Success', "data" => null)));
			return ;
        }else{
			$connection->send(json_encode(array("code" => 1, "msg" => 'Success', "data" => null)));
			return ;
        }
	}else{
		$connection->send(json_encode(array("code" => -1, "msg" => 'Action Not Found', "data" => null)));
		return ;
	}
};

if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}