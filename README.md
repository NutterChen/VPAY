# VPAY

V免签whmcs修改版，基于Workerman服务端，基于Workerman3.X版本，与Workerman4.X版本不兼容，支持Whmcs对接

## 使用说明

### API安装

该程序需要php-posix和php-curl扩展，一般情况下，安装PHP时会默认安装

另外需要启用以下PHP函数

pcntl_fork,
pcntl_wait,
pcntl_alarm,
pcntl_signal,
pcntl_signal_dispatch

将API文件夹内文件上传到服务器中，然后

使用'php /路径/start.php start'进行debug运行

使用'php /路径/start.php start -d'进行后台运行

### 监控APP配置

请下载Monitor APP文件夹内的app安装在安卓手机上，若APP无法工作，请去下方地址下载最新版
Apk下载地址:[https://github.com/szvone/vmqApk/releases](https://github.com/szvone/vmqApk/releases)

打开V免签App后，初次启用请给予APP相关权限，选择手动配置,在弹出的提示框中填写'[服务端IP或域名]:20001/[Config.php里设置的AppKey]',如 xxxx.com:20001/testkey

确认后点击'检测心跳',如果返回的信息含有“Success”字样则对接成功

## WHMCS插件安装

注意，用宝塔面板建站的朋友，千万不要打开宝塔面板网站设置的“强制https”

这会使你的用户退出登陆后无法在登陆进用户中心，以及导致本插件无法正常回调

你可以在宝塔面板网站设置里申请https证书后，再在whmcs管理的常规设置里再http后面加上s，这才是whmcs开启https的正确方法

Whmcs支付插件位于WHMCS文件夹内,上传到modules/gateways并在Whmcs后台启用配置即可

WHMCS插件依赖于支付金额的小数位来区分相同金额的订单，小数位由累加形成

请在whmcs后台设置alipay userid，此ID可以在登陆网页版支付宝个人中心后查看源代码

搜索“userid”，后面的数字就是支付宝userid

## 感谢

https://github.com/szvone and 
Flyqie
