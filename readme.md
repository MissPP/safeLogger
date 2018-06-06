# SafeLogger
  written by MissPP
<br>一个轻量级的日志插件，无需担心有任何耦合插件，简单实用，易上手
### 下载
  解压缩包放至WEB目录内   
  example: /var/www/html   or /htdoc

### 1.安装
(1)通常情况:(无需使用SQL，可以跳过此步骤，自行修改/config/config.php中相关参数，isSQL改为0即可) <br>
  进入install.html文件   
  example: 您的域名/install.html or  localhost/instal.html
  在install.html中输入您的信息。
  <br>可能出现的问题:安装过程中出现异常，或者已经有库名发生冲突。DROP或者换个库名再试即可。请确保您的信息正确!

### 2.使用
  (1) 主页地址: 
  您的域名/slentry.php?module=search
  登录后，您可以查看或者添加您的日志

  (2) 记录日志
  方法1:
  //write_log($log_msg, $iErrorCode = 0, $iErrorArea = 0, $iLevel = 0, $iBiz = 0, $user = null, $rootPath = null, $sFileName = null, $isSql = null) 

  //参数1: 日志内容, 参数2: 错误码,  参数3: 错误域, 参数4: 错误等级, 参数5: 业务号, 参数6: 记录者名称, 参数7: 路径, 参数8: 文件名, 参数9: 是否插入数据库

  //根据需要您完全可以只传第一个参数，别的参数会自动读取配置文件以及设定的默认值
```
require_once("safeLogger.php"); 
$log = new safeLogger();
$log = write_log('记录了一条日志啦！');
```

  方法2:
  参数同上 懒人调用
```
require_once("safeLogger.php"); 
wlog('记录了一条日志啦！');
```
团队长期维护此项目，欢迎PR，BUG反馈，所需合理功能我们都会扩展功能。欢迎联系QQ：565378270
