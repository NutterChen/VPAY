<?php
use WHMCS\Database\Capsule;

function vmq_alipay_MetaData() {
    return array(
        'DisplayName' => 'V免签(支付宝)',
        'APIVersion' => '1.1',
    );
}

function vmq_alipay_config() {
    $configarray = array(
        "FriendlyName"  => array(
            "Type"  => "System",
            "Value" => "V免签(支付宝)"
        ),
        "appurl"  => array(
            "FriendlyName" => "支付接口地址",
            "Type"         => "text",
            "Size"         => "32",
			"Default"         => "http://xxx.xxx:20000",
        ),
        "payqr"  => array(
            "FriendlyName" => "支付二维码(只保留二维码即可)",
            "Type"         => "text",
            "Size"         => "32",
        ),
        "appsk" => array(
            "FriendlyName" => "应用Key",
            "Type"         => "text",
            "Size"         => "32",
        )
    );

    return $configarray;
}

function vmq_alipay_link($params) {
	if(@$_REQUEST['getstatus'] == 'yes'){
		return '等待支付中';
	}
    if($_REQUEST['vpaysub'] == 'yes'){
	   $RandomString = chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122));
       $initialvaluefile = $_SERVER['DOCUMENT_ROOT'].'/modules/gateways/vmqno.txt';
       $initialvalue = file_get_contents($initialvaluefile);
       $accumulate = 0.01;
       file_put_contents($initialvaluefile,str_replace($initialvalue,$initialvalue+$accumulate,file_get_contents($initialvaluefile)));
       $initialvaluenew = file_get_contents($initialvaluefile);
       if ( $initialvaluenew > 0.06 ) {
           file_put_contents($initialvaluefile,str_replace($initialvaluenew,0,file_get_contents($initialvaluefile)));
           $initialvaluenew = file_get_contents($initialvaluefile);
        };
       $params['amount'] = $params['amount'] + $initialvaluenew;
	   $PayID = $RandomString.'|'.$params['invoiceid'];
	   $PaySign = md5('alipay'.$PayID.$params['amount'].$params['systemurl'].'/modules/gateways/vmq_alipay/callback.php'.trim($params['appsk']));
	   $GetInfo = json_decode(vmq_alipay_curl_post(trim($params['appurl']).'/createorder',array("appkey"=>trim($params['appsk']),"payid"=>$PayID,"type"=>'alipay',"price"=>$params['amount'],"sign"=>$PaySign,"notifyurl"=>$params['systemurl'].'/modules/gateways/vmq_alipay/callback.php')),true);
	   if(!$GetInfo){
		   exit('订单添加错误：服务器未返回任何有效信息');
	   }
	   if($GetInfo['code'] != 200){
		   exit('订单添加错误：'.$GetInfo['msg']);
	   }
	   $userdata = array();
	   $userdata['qrcode'] = $params['payqr'];
	   $userdata['money'] = $params['amount'];
	   $userdata['invoiceid'] = $params['invoiceid'];
	   $userdata['make_time'] = date('Y-m-d H:i:s',$GetInfo['data']['maketime']);
	   $userdata['end_time'] = date('Y-m-d H:i:s',$GetInfo['data']['stoptime']);
	   $userdata['order_id'] = $GetInfo['data']['orderid'];
	   $userdata['outTime'] = ($GetInfo['data']['timeout']) * 60;
	   $userdata['logoShowTime'] = 2;
	   exit(vmq_alipay_makehtml(json_encode($userdata)));
	}
    if(stristr($_SERVER['PHP_SELF'],'viewinvoice')){
		return '<form method="post" id=\'vpaysub\'><input type="hidden" name="vpaysub" value="yes"></form><button type="button" class="btn btn-danger btn-block" onclick="document.forms[\'vpaysub\'].submit()">使用支付宝支付</button>';
    }else{
         return '<img style="width: 150px" src="'.$params['systemurl'].'/modules/gateways/vmq_alipay/alipay.png" alt="支付宝支付" />';
    }

}

if(!function_exists("vmq_alipay_makehtml")){
function vmq_alipay_makehtml($userdata){
	$skin_raw = file_get_contents(__DIR__ . "/vmq_alipay/themes.tpl");
    $skin_raw = str_replace('{$userdata}',$userdata,$skin_raw);
    return $skin_raw;
}
}

if(!function_exists("vmq_alipay_curl_post")){
function vmq_alipay_curl_post($url,$data){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $tmpInfo = curl_exec($curl);
    curl_close($curl);
    return $tmpInfo;
}
}