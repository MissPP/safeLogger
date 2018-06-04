<?php

/**
 * Created by PhpStorm.
 * User: v_jyxue
 * Date: 2018/5/14
 * Time: 9:45
 */
class account extends dao
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function account()
    {
        $this->db = DAO::getInstance();
        $params = getParam();
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
        } else {
            echo outMsg('登录状态失效', -2);
            return 0;
        }

        $oldPassword = md5(md5($params['oldPass']));
        $newPassword = md5(md5($params['newPass']));
        if(!preg_match("/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/",$params['newPass'])){
            echo outMsg("密码必须为6-16英文加数字", -1);
            return 0;
        };
        //密码限制
        if ($newPassword == $oldPassword) {
            echo outMsg("新旧密码相同", -1);
            return 0;
        }
        $res = $this->getUser($username, $num);

        if ($num > 0) {
            if ($res[0]['pre_sl_password'] == $oldPassword && !empty($res)) {

                $res = $this->changePassword($newPassword, $username);

                if ($res) {
                    echo outMsg("修改密码成功请牢记您的密码", 1);
                    return 1;
                } else {
                    throw new Exception("重置密码失败");
                }
            } else {
                echo outMsg('当前密码输入错误', 0);
                return 0;
            }
        }
    }
}





