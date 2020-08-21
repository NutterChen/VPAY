<?php
use WHMCS\Database\Capsule;

function vmq_wechat_MetaData() {
    return array(
        'DisplayName' => 'V免签(微信)',
        'APIVersion' => '1.1',
    );
}

function vmq_wechat_config() {
    $configarray = array(
        "FriendlyName"  => array(
            "Type"  => "System",
            "Value" => "V免签(微信)"
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

function vmq_wechat_link($params) {
	if(@$_REQUEST['getstatus'] == 'yes'){
		return '等待支付中';
	}
    if($_REQUEST['vpaysub'] == 'yes'){
	   $RandomString = chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122));
       if ($params['amount'] < 10) {
        $params['amount'] = $params['amount'] + rand(1,20)/100;
        }
        elseif ($params['amount'] < 100) {
         $params['amount'] = $params['amount'] + rand(1,40)/100;
        }
        else {
         $params['amount'] = $params['amount'] + date('s')/100;
        };
       $PayID = $RandomString.'|'.$params['invoiceid'];
	   $PaySign = md5('wechat'.$PayID.$params['amount'].$params['systemurl'].'/modules/gateways/vmq_wechat/callback.php'.trim($params['appsk']));
	   $GetInfo = json_decode(vmq_wechat_curl_post(trim($params['appurl']).'/createorder',array("appkey"=>trim($params['appsk']),"payid"=>$PayID,"type"=>'wechat',"price"=>$params['amount'],"sign"=>$PaySign,"notifyurl"=>$params['systemurl'].'/modules/gateways/vmq_wechat/callback.php')),true);
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
	   $userdata['outTime'] = $GetInfo['data']['timeout'];
	   $userdata['logoShowTime'] = 1;
	   exit(vmq_wechat_makehtml(json_encode($userdata)));
	}
    if(stristr($_SERVER['PHP_SELF'],'viewinvoice')){
		return '<form method="post" id=\'vpaysub\'><input type="hidden" name="vpaysub" value="yes"></form><button type="button" class="btn btn-danger btn-block" onclick="document.forms[\'vpaysub\'].submit()">使用微信支付</button>';
    }else{
         return '<img style="width: 150px" src="'.$params['systemurl'].'/modules/gateways/vmq_wechat/wechat.png" alt="微信支付" />';
    }

}

if(!function_exists("vmq_wechat_makehtml")){
function vmq_wechat_makehtml($userdata){
	$skin_raw = file_get_contents(__DIR__ . "/vmq_wechat/themes.tpl");
    $skin_raw = str_replace('{$userdata}',$userdata,$skin_raw);
    return $skin_raw;
}
}

if(!function_exists("vmq_wechat_curl_post")){
function vmq_wechat_curl_post($url,$data){
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