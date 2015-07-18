<?php
//post param:
//1.method: 'get','update','create'
//2.phone
//3.params
//return:
//{promotion:{},status:,errcode:}
require_once(dirname(__FILE__).'/action/promotion_action.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type:text/html; charset=utf-8');

try{
    /*$param = $_REQUEST;
    $result = array();
    $customer = array();
    $status = array();
    $errcode = 0;

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errcode = 1;
        throw new Exception('NONE_METHOD');
    }

    if(!isset($param['phone']) or trim($param['phone'] == '')){
        $errcode = 1;
        throw new Exception('NONE_PHONE');
    }

    $method = $param['method'];
    $phone = $param['phone'];
    if($method == 'get'){
        $return = getFromPhone($phone);
        $errcode = $return['errcode'];
        $status = $return['status'];
        if($errcode == 0){
            $customer = $return['customer'];
        }else{
            throw new Exception('GET_ERROR');
        }
    }else if ($method == 'update'){
        $update_flag = 0;
        $column_array = array('sex','nickname','birthday','myimage');
        foreach($column_array as $column){
            if(isset($param[$column]) and trim($param[$column]) != ''){
                $customer[$column] = $param[$column];
                $update_flag = 1;
            }
        }
        if($update_flag == 0){
            $status = 'NO_CHANGE';
            $errcode = 0;
        }else{
            $return = updateFromPhone($phone,$customer);
            $errcode = $return['errcode'];
            $status = $return['status'];
            if($errcode == 0){
                $customer = $return['customer'];
            }else{
                throw new Exception('UPDATE_ERROR');
            }
        }

    }else{
        throw new Exception("INVAILD_METHOD");
    }
    $result = array("customer"=>$customer,"status"=>$status,"errcode"=>$errcode);*/
    $result = createPromotion('15151834774',1);
    echo json_encode($result);
}catch (Exception $e){
    $result = array("customer"=>'',"status"=>$e->getMessage(),"errcode"=>1);
    echo json_encode($result);
}

?>