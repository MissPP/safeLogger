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
            echo outMsg("��¼̬ʧЧ", -1);
            return false;
        }
        $this->db = DAO::getInstance();
        $params = getParam();

        $res = $this->addLog($params);
        if ($res) {
            echo outMsg(json_encode($res), 1);
        } else {
            echo outMsg("��¼ʧ��", 0);
        }
    }


}


?>