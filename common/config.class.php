<?php
Class config{
    static public $config;
    static public $instance;

    public static function  getInstance(){

        if(!self::$instance instanceof self){
            self::$instance= new self();
        }
        return self::$instance;
    }
    public function __get($param){

        if(!isset($this->$param)){
            include_once dirname(__FILE__).'/../config/'.$param.'.php';

            $this->$param = $$param;

        }else{
            $this->config=array();
        }
        return $this->$param;
    }
}


?>