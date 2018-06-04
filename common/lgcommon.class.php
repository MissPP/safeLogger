<?php

function wlog($log_msg, $iErrorCode = 0, $iErrorArea = 0, $iLevel = 0, $iBiz = 0, $user = null, $rootPath = null, $sFileName = null, $isSql = null)
{
    $logger = safeLogger::getInstance();

    $logger->write_log($log_msg, $iErrorCode, $iErrorArea, $iLevel, $iBiz, $user, $rootPath, $sFileName , $isSql );

}

function getStr($str)
{
    if (empty($str) || !$str) {
        return false;
    }
    $str = strip_tags($str);
    $str = preg_replace('/;|\'|"|\s|\%|\*/', '', $str);
    if (strlen($str) > 30) {
        throw new Exception("Too long");
    }
    return $str;
}

function getIp()
{

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWORDED_FOR'])) {
        $ip = implode(',', $_SERVER['HTTP_X_FORWORDED_FOR']);
    } else if ($_SERVER['REMOTE_ADDR']) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function getParam()
{
    $params = array_merge($_GET, $_POST);
    if (is_array($params)) {
        $ret = array();
        foreach ($params as $key => $value) {
            $key = urlencode(trim($key));
            if (!get_magic_quotes_gpc()) {
                $value = addslashes(trim($value));
            }
            $ret[$key] = $value;
        }
        return $ret;
    }
    return false;
}

function outMsg($msg, $status)
{
    $msg = iconv('gbk', 'utf-8', $msg);
    $ret = json_encode(array('status' => $status, 'msg' => $msg));
    return $ret;
}

?>