# SafeLogger
  written by MissPP
<br>һ������������־��������赣�����κ���ϲ������ʵ�ã�������
### ����
  ��ѹ��������WEBĿ¼��   
  example: /var/www/html   or /htdoc

### 1.��װ
(1)ͨ�����:(����ʹ��SQL�����������˲��裬�����޸�/config/config.php����ز�����isSQL��Ϊ0����) <br>
  ����install.html�ļ�   
  example: ��������/install.html or  localhost/instal.html
  ��install.html������������Ϣ��
  <br>���ܳ��ֵ�����:��װ�����г����쳣�������Ѿ��п���������ͻ��DROP���߻����������Լ��ɡ���ȷ��������Ϣ��ȷ!

### 2.ʹ��
  (1) ��ҳ��ַ: 
  ��������/slentry.php?module=search
  ��¼�������Բ鿴�������������־

  (2) ��¼��־
  ����1:
  //write_log($log_msg, $iErrorCode = 0, $iErrorArea = 0, $iLevel = 0, $iBiz = 0, $user = null, $rootPath = null, $sFileName = null, $isSql = null) 

  //����1: ��־����, ����2: ������,  ����3: ������, ����4: ����ȼ�, ����5: ҵ���, ����6: ��¼������, ����7: ·��, ����8: �ļ���, ����9: �Ƿ�������ݿ�

  //������Ҫ����ȫ����ֻ����һ����������Ĳ������Զ���ȡ�����ļ��Լ��趨��Ĭ��ֵ
```
require_once("safeLogger.php"); 
$log = new safeLogger();
$log = write_log('��¼��һ����־����');
```

  ����2:
  ����ͬ�� ���˵���
```
require_once("safeLogger.php"); 
wlog('��¼��һ����־����');
```
�Ŷӳ���ά������Ŀ����ӭPR��BUG������������������Ƕ�����չ���ܡ���ӭ��ϵQQ��565378270
