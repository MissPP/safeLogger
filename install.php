<?php
require_once 'safeLogger.php';

Class install
{
    public $db;
    public $flag_first_entry;

    public function index()
    {
    }

    public function firstOpen()
    {
        //创建初始化方法;
        $param = getParam();
        $username = htmlspecialchars(trim($param['username']));
        $password = htmlspecialchars(trim($param['password']));
        $host = htmlspecialchars(trim($param['host']));
        $port = htmlspecialchars(trim($param['port']));
        $database = htmlspecialchars(trim($param['database']));
        $sqlUsername = htmlspecialchars(trim($param['sqlUsername']));
        $sqlPassword = htmlspecialchars(trim($param['sqlPassword']));

        $this->firstEntryWriteConfig($host, $sqlUsername, $sqlPassword, $port, $database);

        $this->db = DAO::getInstance(2);

        if ($this->db->link) {
            try {
                $res = $this->db->createDatabase($database);
            } catch (Exception $e) {
                outMsg("Connect fail check your account", -1);
            }
            if ($res) {
                $this->db->setDatabase($database);
                $this->db->setCharset('utf8');
                $this->db->showDatabase($database);
                $this->db->createTable();
                $this->db->createFirstAdmin($username, $password);
				$_SESSION['user'] = $username;
				echo outMsg("success", 1);
				
            }
        } else {
            outMsg("Connect fail check your account", -1);
            exit;
        }
   
//            unlink('install.php');
    }

    function firstEntryWriteConfig($host, $username, $password, $port, $database)
    {
        $f2 = fopen("./config/slConfig.php", 'w');
        if (!$f2) {
            throw new Exception("Open File Fail");
        }
        $res = fwrite($f2,
            '<?php
    $slConfig["DB"] = array(
        \'HOST\' => \'' . $host . '\',
         \'PORT\' => \'' . $port . '\',
         \'USERNAME\' => \'' . $username . '\',
         \'PASSWORD\' => \'' . $password . '\',
         \'DATABASE\' => \'' . $database . '\'
           );
    $slConfig["LOG"] = array(
         \'PATH\' => \'/\',
         \'SQLUSERNAME\' => \'default\',
         \'DIR\' => \'log_list\',
         \'LOGLEVEL\' => \'7\',
         \'FILENAME\' =>\'safe_logger.log\',
         \'ISSQL\' => \'1\'
           );
?>');

        if (!$res) {
            throw new Exception("Write File Failed");
        }
        fclose($f2);
    }

}

$instance = new install();
$instance->firstOpen();


?>