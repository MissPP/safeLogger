<?php
Class DAO{
    public static $getInstance;
    public $db;
    function __construct($create = 1)
    {
        if(!$this->db){

            $config = config::getInstance();

            $config = $config->slConfig;
            $host = $config['DB']['HOST'];
            $port = $config['DB']['PORT'];
            $user = $config['DB']['USERNAME'];
            $password = $config['DB']['PASSWORD'];
            $database = $config['DB']['DATABASE'];

            $this->db = new dbBase($host, $user, $password, $database, $port,$create); //port默认3306

        }
    }
    function __clone()
    {
        // TODO: Implement __clone() method.
    }
    //install页面 $create = 0
    public static function getInstance($create = 1){

        if(!self::$getInstance){

        $config = config::getInstance();
        $config = $config->slConfig;
        $host = $config['DB']['HOST'];
        $port = $config['DB']['PORT'];
        $user = $config['DB']['USERNAME'];
        $password = $config['DB']['PASSWORD'];
        $database = $config['DB']['DATABASE'];

        self::$getInstance = new dbBase($host, $user, $password, $database, $port,$create); //port默认3306

        }
        return self::$getInstance;
    }

    public function getUser($username,&$num){

        $sql = "select * from `pre_safe_logger_user` where pre_sl_username = '" . $username . "'";

        $res = $this->db->ExecSearch($sql, $num);

        return $res;
    }

    public function changePassword($newPassword, $username){

        $sql = "update `pre_safe_logger_user` set `pre_sl_password` = '" . $newPassword . "' where `pre_sl_username` =" . $username;
        $res = $this->db->ExecQuery($sql);

        return $res;
    }

    public function addLog($params){
        $ip = getIp();
        $content = htmlspecialchars(trim($params['content']));
        $biz = htmlspecialchars(trim($params['biz']));
        $errorCode = htmlspecialchars(trim($params['errorCode']));
        $errorArea = htmlspecialchars(trim($params['errorArea']));
        $sql = "INSERT INTO `pre_safe_logger` (`pre_sl_msg`,`pre_sl_ibiz`,`pre_sl_code`,`pre_sl_error_area`,`pre_sl_user`, `pre_sl_ip`,`pre_sl_insert_time`) VALUES ('".$content."','".$biz."','".$errorCode."','".$errorArea."','".$_SESSION['user']."','".$ip."', now())";

        $res = $this->db->ExecQuery($sql);
        if(!$res){
            throw new Exception("addLog Failed");
        }
        $id = mysqli_insert_id($this->db->link);
        $sql2 = "SELECT * FROM `pre_safe_logger` where `pre_sl_id` = " .$id;
        $res2 = $this->db->ExecSearch($sql2,$num);

        return $res2;
    }

    public function writeSql($msg, $user, $ip, $biz, $error_area, $iErr, $sFileName = 0)
    {
        $sql = "insert into `pre_safe_logger` (`pre_sl_user`, `pre_sl_msg` ,`pre_sl_ip`,`pre_sl_insert_time`, `pre_sl_code`, `pre_sl_ibiz`,`pre_sl_error_area`,`pre_sl_log_file_name`) values('" . $user . "','" . $msg . "', '" .$ip  . "',now()," . $iErr . "," . $biz . "," . $error_area . ",'" . $sFileName . "')";

        $res = $this->db->ExecQuery($sql);
        if (!$res) {
            throw new Exception("Insert SQL Failed");
        }
    }

    public function searchLog($conditions ,$start, $pageSize){

        $sql = 'SELECT * FROM `pre_safe_logger`  WHERE 1=1'.$conditions. " LIMIT ".$start*$pageSize.", {$pageSize} ";

        $res = $this->db->ExecSearch($sql,$num);
        return $res;
    }

    public function getAllLog($conditions){

        $sql = 'SELECT count(*) FROM `pre_safe_logger`  WHERE 1=1'.$conditions;

        $res = $this->db->ExecSearch($sql, $num);
        $count = array("count"=>$res[0]);

        return $count;
    }
}


?>