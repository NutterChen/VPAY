<?php 
use Workerman\Worker;
use think\Db;
require_once __DIR__ . '/vendor/autoload.php';
function curl_post_https($url,$data){ 
    $url = preg_replace('/([^:])[\/\\\\]{2,}/','$1/',$url);
	$urlfields = "";
	foreach($data as $k => $v ){
		$urlfields .= $k . "=" . urlencode($v) . "&";
	}
	$url = rtrim($url.'?'.$urlfields,'&');
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    $tmpInfo = curl_exec($curl); // 执行操作
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}
$worker = new Worker();
$worker->count = 1;
$worker->name = 'Pay Watch';
$worker->onWorkerStart = function($worker)
{
	Db::setConfig(['type'=> 'sqlite','database'=> __DIR__.'/database.db','prefix'=> '','debug'=> true]);
    \Workerman\Lib\Timer::add(30, function(){
		$data = Db::table('payinfo')->where('status','waitnotify')->select();
		include __DIR__.'/config.php';
        foreach ($data as $item) {
			$data2 = @(Db::table('payinfo')->where('payid',$item['payid'])->find())['status'];
			if($data2 == 'waitnotify'){
				Db::table('payinfo')->where('payid',$item['payid'])->update(["status" => 'donotify']);
				$PaySign = md5($item['type'].$item['payid'].$item['orderid'].$item['price'].$AppKey);
				$NotifyReturn = curl_post_https($item["notifyurl"],array('type' => $item['type'],'price' => $item['price'],'orderid' => $item['orderid'],'payid' => $item['payid'],'sign' => $PaySign));
				if(trim($NotifyReturn) == 'success'){
					//异步请求成功
					Db::table('payinfo')->where('id',$item['id'])->delete();
				}else{
					Db::table('payinfo')->where('payid',$item['payid'])->update(["status" => 'waitnotify']);
				}
			}
	    }
		Db::table('payinfo')->where('status','<>','waitnotify')->where('status','<>','donotify')->where('endtime','<',time())->delete();
    });
};
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}