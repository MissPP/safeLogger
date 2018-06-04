<?php
/**
 * Created by PhpStorm.
 * User: v_jyxue
 * Date: 2018/5/10
 * Time: 16:25
 */
Class  loginOut {

    public function __construct()
    {
    }

    public function index(){

    }

    public function loginOut(){
        unset($_SESSION['user']);
        header("location:slentry.php?module=login");
    }
}

