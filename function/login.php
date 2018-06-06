<?php

Class login extends dao
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function login()
    {

        $this->db = DAO::getInstance();
        $params = getParam();
        $username = htmlspecialchars(trim($params['username']));
        $password = md5(md5(htmlspecialchars(trim($params['passwd']))));

        $res = $this->getUser($username,$num);
        if ($num > 0) {
            if ($res[0]['pre_sl_password'] == $password && !empty($res)) {
                $_SESSION['user'] = $res[0]['pre_sl_username'];
                echo outMsg('登录成功', 1);
                return 1;
            } else {
                echo outMsg('账号或者密码错误', 0);
                return 0;
            }
        } else {
            echo outMsg('账号不存在', 0);
            return 0;
        }
    }

    public function bakeup()
    {
//        $sql = "insert into tbOpLog(sUser, sUserName, sUserRole, sOpedUser, s) values ('%s', '%s', '%s', '%s', '%', '%s', %d, '%s', '%s', %d)";
//        $sql = sprintf($sql,
//            iconv("utf-8", "gbk", $params['sUser']),
//            iconv("utf-8", "gbk", $params['sUserName']),
//            iconv("utf-8", "gbk", $params['sUserRole']),
//            iconv("utf-8", "gbk", $params['sOpedUser']),
//            iconv("utf-8", "gbk", $params['sOpedUserName']));

    }


}


?>