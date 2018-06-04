<?php

//use namespace register
define('ROOT_PATH', dirname(__FILE__));
date_default_timezone_set("PRC");
require_once 'common/dao.class.php';
require_once 'common/db.class.php';
require_once 'common/config.class.php';
require_once 'common/lgcommon.class.php';
require_once  'hook.php';
session_start();

Class safeLogger
{
    public $db;
    public $mLog_level;
    public $mUser;
    public $mLog_path;
    public $mLog_dir;
    public $isInsertSql = 1;
    public $mFileName;
    static public $instance;
    //报错等级列表
    protected $mLog_level_list = array(
        "EMERGENCY" => 0,
        "ALERT    " => 1,
        "CRITICAL " => 2,
        "ERROR    " => 3,
        "WARNING  " => 4,
        "NOTICE   " => 5,
        "INFO     " => 6,
        "DEBUG    " => 7
    );

    function __construct()
    {
    }

    function __clone()
    {
        // TODO: Implement __clone() method.
    }


    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;

    }
	
	//参数1: 日志内容, 参数2: 错误码,  参数3: 错误域, 参数4: 错误等级, 参数5: 业务号, 参数6: 记录者名称, 参数7: 路径, 参数8: 文件名, 参数9: 是否插入数据库
	//根据需要您完全可以只传第一个参数，别的参数会自动读取配置文件以及设定的默认值
    public function write_log($log_msg, $iErrorCode = 0, $iErrorArea = 0, $iLevel = 0, $iBiz = 0, $user = null, $rootPath = null, $sFileName = null, $isSql = null) 
    {
        $this->loadConfig();

        $rootPath = $rootPath ? $rootPath : $this->mLog_path . $this->mLog_dir;
        $sFileName = $sFileName ? $sFileName : $this->mFileName;
        $isSql = $isSql ? $isSql : $this->isInsertSql;
        $user = $user ? $user : $this->mUser;
        $iLevel = $iLevel ? $iLevel : $this->mLog_level;
        $nowDateTime = date('Y-m-d H:i:s');
        $ip = getIp();

        $msg = array(
            "Occur Time" => $nowDateTime,
            "Error Message" => $log_msg,
            "IP" => $ip,
            "Error Code" => $iErrorCode,
            "Area code" => $iErrorArea,
            "Biz Type" => $iBiz,
            "Level" => $iLevel
        );

        $wMsg = json_encode($msg).PHP_EOL;

        if ($iLevel > $this->mLog_level) {
            return false;
        }

        if (!$this->setDir()) {
            throw new Exception("Create LogPath: {$rootPath} error! Please Check the config file and File Permission");
        }

        if (!$this->write($wMsg)) {
            throw new Exception("Cant write file!Please Check Permission");
        }

        if ($isSql == 1) {
            $this->db = new DAO();
            $log_file = $rootPath . '/' . $sFileName;
            $this->db->writeSql($log_msg, $user, $ip,$iBiz, $iErrorArea, $iErrorCode, $log_file);
        }

        return true;
    }


    public function loadConfig()
    {
        $config = config::getInstance();
        $option = $config->slConfig;
        $existConfig = empty($option);
        $this->mLog_path = $option['LOG']['PATH'];
        $this->mLog_dir = $option['LOG']['DIR'];
        $this->mFileName = $option['LOG']['FILENAME'];
        $this->mUser = $option['LOG']['SQLUSERNAME'];
        $this->mLog_level = $option['LOG']['LOGLEVEL'];
        $this->isInsertSql = $option['LOG']['ISSQL'];

        $isFileWriteable = $this->isFileWriteable();

        return $existConfig && $isFileWriteable;

    }

    protected function setDir()
    {
        $dir = $this->mLog_path . $this->mLog_dir;
        return is_dir($dir) || @mkdir($dir, 0777);
    }

    public function isFileWriteable()
    {
        $file = $this->mLog_path . $this->mLog_dir . '/' . $this->mFileName;
        $res = is_writable($file);
        return $res;
    }

    public function write($msg)
    {
        $log_file = $this->mLog_path . $this->mLog_dir . '/' . $this->mFileName;
        $open_log = fopen($log_file, 'ab+');
        $res = fwrite($open_log, $msg);
        fclose($open_log);
        return $res;
    }

}


?>