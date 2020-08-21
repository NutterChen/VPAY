# VMQ-PHP

V免签 Workerman服务端，基于Workerman3.X版本，与Workerman4.X版本不兼容，支持Whmcs对接

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

请下载Monitor APP文件夹内的app安装在安卓手机上，初次启用请给予APP相关权限

若APP无法工作，请去下方地址下载最新版
Apk下载地址:[https://github.com/szvone/vmqApk/releases](https://github.com/szvone/vmqApk/releases)

打开V免签App后，初次启用请给予APP相关权限，选择手动配置,在弹出的提示框中填写'[服务端IP或域名]:20001/[Config.php里设置的AppKey]',如 xxxx.com:20001/testkey

确认后点击'检测心跳',如果返回的信息含有“Success”字样则对接成功

## WHMCS插件安装

Whmcs支付插件位于WhmcsPay文件夹内,上传到modules/gateways并在Whmcs后台启用配置即可

WHMCS插件依赖于支付金额的小数位来区分相同金额的订单，小数位由累加形成

## 感谢

https://github.com/szvone and 
Flyqie
