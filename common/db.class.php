<?php

Class dbBase
{

    public    $link;
    private   $errorNo = 0;  //根据sqlType获取

    function __clone()
    {
        // TODO: Implement __clone() method.
    }


    function __construct($host,$user, $password, $database, $port = 3306, $creatFlag = 1)
    {
        if($creatFlag == 1){
            @$this->link = mysqli_connect($host,$user,$password, $database ,$port) or die("Wrong infomation! Cant connect to mysql");
        }else{
            @$this->link = mysqli_connect($host,$user,$password) or die(outMsg("Wrong infomation! Cant connect to mysql",-1)) ;
        }

        //加入mysqli报警
        if (mysqli_connect_errno())
        {
            return false;
        }else{
            $this->setCharset('utf8');
        }
        return true;
    }

    public function setDatabase( $name )
    {
        $res = mysqli_select_db($this->link, $name);

        if (!$res) {
            throw new Exception("Fail to Select Database");
        }
    }

    public function setCharset($charset)
    {
        mysqli_query($this->link, "set names {$charset}");
    }

    public function ExecQuery($sql)
    {
        $iErrorType = $this->get_sql_type($sql);
        if (!$this->get_sql_type($sql)) {
            throw new Exception('SQL:[' . $sql . '] Error:' . ".\n");
        } else {
            $this->errorNo = $iErrorType;
            if ($res = mysqli_query($this->link,$sql)) {
                return $res;
            } else {
                throw new Exception("Something Wrong With sql:{$sql},ErrorType:{$this->errorNo} ERROR::".mysqli_error($this->link)."\n");
                //添加sql原生捕获错误;
            }
        }
    }

    public function ExecSearch($sql,&$num)
    {

        $iErrorType = $this->get_sql_type($sql);
        if (!$this->get_sql_type($sql)) {
            throw new Exception('SQL:[' . $sql . '] Error:' . ".\n");
        } else {
            $this->errorNo = $iErrorType;
            if ($res = mysqli_query($this->link,$sql)) {
                $num = mysqli_num_rows($res) ;
                $resultset = array();
                while ($row = mysqli_fetch_array($res, MYSQLI_BOTH))
                {
                 $resultset[] = $row;
                }
                return $resultset;
            } else {
                throw new Exception("Something Wrong With sql:{$sql},ErrorType:{$this->errorNo}  ERROR::".mysqli_error($this->link));
            }
        }
    }

    public function ExecTrans($statements)
    {
        if (!is_array($statements)) {
            throw new Exception("Exec Trans Sqls not array.\n");
        }
        for ($i = 0; $i < count($statements); ++$i) {
            $sql_type = self::get_sql_type($statements[$i]);
            if (!$sql_type) {
                throw new Exception("ErrorType:{$sql_type}  Invalid Statement.\n");
            }
        }
        $this->Begin();
        for ($i = 0; $i < count($statements); ++$i) {
            $sql_type = self::get_sql_type($statements[$i]);
            if (!mysqli_query($this->link,$statements[$i])) {
                $this->errorNo = $sql_type;
                $this->Rollback();
                throw new Exception('ErrorType:' . $this->errorNo . 'Mysql query:[' . $statements[$i] . '] Error:' . mysqli_error($this->link) . ".\n");
            }
        }
        $this->Commit();
        return false;
    }

    public function Begin()
    {
        if (!mysqli_query($this->link,"start transaction;")) {
            throw new Exception('Invalid query: [start transaction;] Error::' . mysqli_error($this->link) . ".\n");
        }
        return 0;
    }

    public function Commit()
    {
        if (!mysqli_query($this->link,"commit;")) {
            throw new Exception('Invalid query: [commit;] Error::' . mysqli_error($this->link) . ".\n");
        }
        return false;
    }

    public function Rollback()
    {
        if (!mysqli_query($this->link,"rollback;" )) {
            throw new Exception('Invalid query: [rollback;] Error::' . mysqli_error($this->link) . ".\n");
        }
        return false;
    }


    public function get_sql_type($sql)
    {
        $sql = trim($sql);
        $sql_type = strtolower(substr($sql, 0, strpos($sql, " ", 0)));
        //echo "get_sql_type sql_type:".$sql_type."<br/>";
        if (strcmp($sql_type, "select") == 0)
            return '1';
        else if (strcmp($sql_type, "update") == 0)
            return '2';
        else if (strcmp($sql_type, "insert") == 0)
            return '3';
        else if (strcmp($sql_type, "delete") == 0)
            return '4';
        else if (strcmp($sql_type, "replace") == 0)
            return '5';
        else if (strcmp($sql_type, "set") == 0)
            return '6';
        else if (strcmp($sql_type, "create") == 0)
            return '7';
        return false;
    }


    public function createDatabase($database)
    {
        $sql = "CREATE DATABASE `{$database}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        $res = $this->ExecQuery($sql);
        if (!$res) {
            throw new Exception("Create Database `pre_sae_logger` Failed ERROR::".mysqli_error($this->link). ".\n");
        } else {
            return true;
        }
    }

    public function createTable(){
        $sql = "CREATE TABLE `pre_safe_logger`(
`pre_sl_id` INT(11) NOT NULL AUTO_INCREMENT,
`pre_sl_user` VARCHAR (255) NOT NULL,
`pre_sl_msg` TEXT (1000) NOT NULL,
`pre_sl_ip` VARCHAR (255) NOT NULL,
`pre_sl_insert_time` DATETIME NOT NULL,
`pre_sl_ibiz` INT (11) DEFAULT '0',
`pre_sl_code` INT (11) DEFAULT '0',
`pre_sl_error_area` int(11) DEFAULT '0',
`pre_sl_log_file_name` VARCHAR (255) NOT NULL,
 PRIMARY KEY (pre_sl_id)
)ENGINE=INNODB DEFAULT CHARSET=UTF8";

        $res = $this->ExecQuery($sql);
        if (!$res) {
            throw new Exception("Create Table `pre_safe_logger` Failed");
        }

        $sql2 = "CREATE TABLE `pre_safe_logger_user`(
`pre_sl_id` INT(11) NOT NULL AUTO_INCREMENT,
`pre_sl_username` VARCHAR(255) NOT NULL,
`pre_sl_password` VARCHAR (255) NOT NULL,
`pre_sl_create_time` DATETIME ,
`pre_sl_auth_fid` INT(11) DEFAULT '0',
`pre_sl_ibiz` INT(11) DEFAULT '0',
PRIMARY KEY(pre_sl_id)
) ENGINE=INNODB DEFAULT CHARSET=UTF8";
        $res2 = $this->ExecQuery($sql2);
        if (!$res2) {
            throw new Exception("Create Table `pre_safe_logger_user` Failed");
        }

        $sql3 = "CREATE TABLE `pre_safe_user_auth`(
`pre_sl_id`INT(11) NOT NULL AUTO_INCREMENT,
`pre_sl_user_fid` INT(11) NOT NULL,
`pre_sl_auth_name`VARCHAR (255) NOT NULL,
PRIMARY KEY(pre_sl_id)
)ENGINE=INNODB DEFAULT CHARSET=UTF8";
        $res3 = $this->ExecQuery($sql3);
        if (!$res3) {
            throw new Exception("Create Table `pre_safe_user_auth` Failed");
        } else {
            return 1;
        }
    }


    public function createFirstAdmin($user, $password)
    {
        $sql = "insert into `pre_safe_logger_user` (`pre_sl_username`, `pre_sl_password`, `pre_sl_create_time`, `pre_sl_auth_fid`, `pre_sl_ibiz`) values ('".$user."', '".md5(md5($password))."', now(), 0, 0)";
        $sql2 = "insert into `pre_safe_user_auth` (`pre_sl_id`, `pre_sl_user_fid`, `pre_sl_auth_name`) value (0,0,'default')";
        $res = $this->ExecQuery($sql);
        $res1 = $this->ExecQuery($sql2);
        if (!$res || !$res1) {
            throw new Exception("Insert DATA Failed Please check the database and try again");
        }
    }
    public function showDatabase($database)
    {
        $this->setDatabase($database);
        $this->setCharset('utf8');
    }


}

?>