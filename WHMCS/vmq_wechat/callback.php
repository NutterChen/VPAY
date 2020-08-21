<?php
use WHMCS\Database\Capsule;
# Required File Includes
include("../../../init.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

$gatewaymodule = "vmq_wechat";
$GATEWAY       = getGatewayVariables($gatewaymodule);
if(!$GATEWAY["type"]){
	exit("fail");
}

$security['out_trade_no'] = explode('|',@$_REQUEST['payid']);
if(!@$security['out_trade_no'][1]){
	exit('fail');
}
$security['out_trade_no'] = $security['out_trade_no'][1];
$security['total_fee'] = @$_REQUEST['price'];
$security['trade_no'] = @$_REQUEST['orderid'];
$Sign = md5(@$_REQUEST['type'].@$_REQUEST['payid'].@$_REQUEST['orderid'].@$_REQUEST['price'].trim($GATEWAY['appsk']));
//额外手续费
$fee = 0;
if($Sign == @$_REQUEST['sign']){
    $invoiceid = checkCbInvoiceID($security['out_trade_no'], $GATEWAY["name"]);
    checkCbTransID($security['trade_no']);
    addInvoicePayment($invoiceid,$security['trade_no'],trim($security['total_fee']),$fee,$gatewaymodule);
    logTransaction($GATEWAY["name"], $_REQUEST, "Successful");
    echo 'success';
} else {
    echo 'fail';
}