<?php

Class add extends dao
{
    public $db;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function add()
    {
        if (!isset($_SESSION['user'])) {
            echo outMsg("登录态失效", -1);
            return false;
        }
        $this->db = DAO::getInstance();
        $params = getParam();

        $res = $this->addLog($params);
        if ($res) {
            echo outMsg(json_encode($res), 1);
        } else {
            echo outMsg("记录失败", 0);
        }
    }


}


?>