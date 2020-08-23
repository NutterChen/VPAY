<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no,email=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>扫码支付</title>
    <link href="modules/gateways/vmq_alipay/css/pay.css" rel="stylesheet" media="screen">
<style>
.button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}

.button1 {font-size: 10px;}

</style>
</head>

<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico_log ico-1"></span>
    </h1>

    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount" id="money">￥0.00</div>
        <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
            <div data-role="qrPayImg" class="qrcode-img-area">
                <div class="ui-loading qrcode-loading" data-role="qrPayImgLoading" style="display: none;">加载中</div>
                <div style="position: relative;display: inline-block;">
                    <img id='show_qrcode' alt="加载中..." src="" width="210" height="210" style="display: block;">
                    <img onclick="$('#use').hide()" id="use" src="modules/gateways/vmq_alipay/img/use_1.png" style="position: absolute;top: 50%;left: 50%;width:32px;height:32px;margin-left: -21px;margin-top: -21px">
                </div>
            </div>

<a href="alipays://platformapi/startapp?saId=20000067&amp;url={$systemurl}modules%2fgateways%2fvmq_alipay%2falipayautotrans%2f%3fuserid%3d{$alipay_userid}%26amount%3d{$amount}%26memo%3d{$alipay_memo}"target="_blank">
<button class="button button1">手机点我支付</button>


</a> 

        </div>
        <div class="time-item" id="msg">
            <h1>二维码过期时间</h1>
            <strong id="hour_show">0时</strong>
            <strong id="minute_show">0分</strong>
            <strong id="second_show">0秒</strong>
        </div>

        <div class="tip">
            <div class="ico-scan"></div>
            <div class="tip-text">
                <p>请使用支付宝扫一扫</p>
                <p>扫描二维码完成支付</p>
            </div>
        </div>

        <div class="detail" id="orderDetail">
            <dl class="detail-ct" id="desc" style="display: none;">

                <dt>状态</dt>
                <dd id="createTime">订单创建</dd>

            </dl>
            <a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
        </div>

        <div class="tip-text">
        </div>


    </div>
    <div class="foot">
        <div class="inner">
            <p>手机用户可保存上方二维码到手机中</p>
            <p>在支付宝扫一扫中选择“相册”即可</p>
        </div>
    </div>

</div>
<div class="copyRight">
    <p>技术支持：<a href="https://www.bank.cat/" target="_blank">BanK.cat</a></p>
</div>

<script src="modules/gateways/vmq_alipay/js/jquery-1.10.2.min.js"></script>
<!--[if lt IE 8]>
<script src="modules/gateways/vmq_alipay/js/json3.min.js"></script><![endif]-->
<script src="modules/gateways/vmq_alipay/js/pay_util.js"></script>
<script>
    var user_data ={$userdata}
	show_Qrcode(user_data);
</script>
<script>
    setTimeout(function () {
        $('#use').hide() //2秒后隐藏中间那LOGO
    }, user_data.logoShowTime || 2000);
</script>
</body>
</html>