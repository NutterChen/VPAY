<?php
header('Content-type:text/json');
require_once __DIR__ . '/../../../init.php';
use Illuminate\Database\Capsule\Manager as Capsule;
$invoiceid = trim($_REQUEST['invoiceid']);
if(!$invoiceid){
	exit(json_encode(array('status'=>404,'msg'=>'账单不能为空')));
}
$userid = trim($_SESSION['uid']);
if(!$userid){
    exit(json_encode(array('status'=>403,'msg'=>'尚未登录或登录状态已经过期')));
}
$order_data = Capsule::table('tblinvoices')->where('id',$invoiceid)->where('userid',$userid)->first();
if(!$order_data){
	exit(json_encode(array('status'=>404,'msg'=>'账单不存在或该账单不属于您')));
}
$status = $order_data->status;	
if($status == "Paid"){
    exit(json_encode(array('status'=>200,'msg'=>'支付已成功')));
}else{
    exit(json_encode(array('status'=>500,'msg'=>'支付未完成')));
}