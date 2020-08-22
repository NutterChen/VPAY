# VmqPHP
Vmq Workerman服务端，基于Workerman，支持Whmcs对接

该程序需要需要php-posix和php-curl扩展!请务必安装!

git clone后执行composer install 并按照config.php中的说明配置好即可,使用'php /路径/start.php -d'进行运行

如果您目标机器没有composer,也可以使用releases里提供的zip包解压缩后配置config.php并使用'php /路径/start.php -d'进行运行

Whmcs支付插件位于WhmcsPay文件夹内,上传到modules/gateways并在Whmcs后台启用配置即可

Apk下载地址:[https://github.com/szvone/vmqApk/releases](https://github.com/szvone/vmqApk/releases)

打开V免签App后选择手动配置,在弹出的提示框中填写'[服务端IP或域名]:20001/[Config.php里设置的AppKey]',如 xxxx.com:20001/testkey

确认后选择'检测心跳',如果返回的信息含有Success字样则对接成功
