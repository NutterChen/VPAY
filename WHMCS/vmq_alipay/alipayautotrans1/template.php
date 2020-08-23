<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no,email=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>支付宝</title>
    <script src="./js/alipayjsapi.min.js"></script>
</head>
<body>

<div id="box">
    <a id="btn1" data-href='' class="btn btn-primary disabled" style="font-size:20px;">
    </a>
</div>

    <script>
        let o = {}
        let params = decodeURIComponent(window.location.search.replace("?", ""))
        params.split("&").forEach(v => {
            var kv = v.split("=")
            o[kv[0]] = kv[1]
        })
        let userId = itsuserid;  //用户id
        let amount = itsamount; //转账金额
        let memo = "itsmemo";   //备注
        let hf=`<a id="btn1"
            data-href='alipays://platformapi/startapp?appId=20000123&amp;actionType=scan&amp;biz_data={&quot;s&quot;: &quot;money&quot;,&quot;u&quot;: &quot;${userId}&quot;,&quot;a&quot;: &quot;${amount}&quot;,&quot;m&quot;: &quot;${memo}&quot;}'
            class="btn btn-primary disabled" style="display: none">
        </a>`;
        document.getElementById('box').innerHTML=hf;
        let timer1 = setInterval(function(){
            let btn1 = document.getElementById('btn1')
            btn1.setAttribute('href', btn1.getAttribute('data-href'));
            btn1.click();
            try{
                clearInterval(timer1);
            }
            catch(x) {

            }
        }, 1000);
    </script>
</body>
</html>