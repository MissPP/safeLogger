<?php

Class search  extends dao{
    public $db;
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

    }

    public function search(){
        if(!isset($_SESSION['user'])){
            echo outMsg("��¼̬ʧЧ",-1);

            return false;
        }
        $this->db = DAO::getInstance();
        $params = getParam();
        $conditions = '';
        $start = $params['page']-1;
        $pageSize =$params['pageSize'];

        if(isset($params['content'])&&$params['content']!=''){
            $conditions .= " AND pre_sl_msg like '%".$params['content']."%'";
        }
        if(isset($params['biz'])&&$params['biz']!=''){
            $conditions .= " AND pre_sl_ibiz = ".$params['biz'];
        }
        if(isset($params['errorCode'])&&$params['errorCode']!=''){
            $conditions .= " AND pre_sl_code = ".$params['errorCode'];
        }
        if(isset($params['errorArea'])&&$params['errorArea']!=''){
            $conditions .= " AND pre_sl_error_area = ".$params['errorArea'];
        }
        if(isset($params['login'])&&$params['login']!=''){
            $conditions .= " AND pre_sl_user = '".$params['login']."'";
        }
        if(isset($params['time'])&&$params['time']!=''){
            $conditions .= " AND pre_sl_insert_time between '".$params['time']." 00:00:00' AND '".$params['time']." 23:59:59'";
        }
        if(isset($params['order'])&&$params['order']!=''){
            $conditions .= " ORDER BY  pre_sl_id ".$params['order'];
        }

        $res = $this->searchLog($conditions ,$start, $pageSize);

        $count = $this->getAllLog($conditions);

        echo json_encode(array_merge($res,$count));
    }


}


?>