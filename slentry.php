<?php
require_once 'safeLogger.php';

Class slentry
{
    public function entry()
    {
        $get = $_GET;
        $params = array();
        $urlParamCount = 0;
        $urlRange = array("controller", "page", "module");
        foreach ($get as $key => $value) {
            $key = urlencode(trim($key));
            $params[$key] = htmlspecialchars(trim($value));
            if (in_array($key, $urlRange)) {
                $urlParamCount++;
            };
        }
        if ($urlParamCount > 1) {
            throw new Exception("Bad Url!");
        }else if ($urlParamCount==0 || empty($get)){
            $this->easyConfig('login');
        }

        if (isset($params['page'])) {
            $urlParam = $params['page'];
            $this->easyConfig($urlParam);
        }

        if (isset($params['controller'])) {
            $urlParam = $params['controller'];
            if(isset($params['controller'])){
                $func = $params['controller'];
            }else{
                $func = 'controller';
            }

            include_once ROOT_PATH.'/function/'.$urlParam.'.php';
            $module = new $params['controller'];
            $module->$func();
            //you can render html in your function
        }
        if (isset($params['module'])) {
            $urlParam = $params['module'];
            if(isset($params['func'])){
                $func = $params['func'];
            }else{
                $func = 'index';
            }

            include_once ROOT_PATH."/function/"."$urlParam".".php";
            $module = new $params['module'];
            $module->$func();
            $this->easyConfig($urlParam);
        }
    }


    public function easyConfig($param){
        $config = array(
            'search' => array('header','search'),       //include "include header.html" ;include "seracher.html";
            'account' => array('header','account'),
            );

        foreach ( $config as $key => $value){

            if($param == $key){

                $row = $value;
                foreach ($row as $key2 => $value2){

                    include_once ROOT_PATH.'/page/'.$value2.'.html';

                }
             return false;
            }
        }
            include_once  ROOT_PATH.'/page/'.$param.'.html';
        return false;
    }
}

$log = new slentry();
$log->entry();

?>