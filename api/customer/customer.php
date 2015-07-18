<?php
//post param:
//1.method: 'get','update'
//2.phone number
//3.update param
//return:
//{customer:{},status:,errcode:}
require_once(dirname(__FILE__).'/action/customer_action.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type:text/html; charset=utf-8');

function getFromPhone($phone){
    $entity_id_array = getEntityId($phone);
    $customer = array();
    if($entity_id_array['errcode'] == 1){
        throw new Exception($entity_id_array['status']);
    }else{
        $entity_id = $entity_id_array['entity_id'];
        $column_array = array('sex','nickname','birthday','myimage');
        foreach($column_array as $column){
            $column_tmp = getCustomerColumn($entity_id,$column);
            if($column_tmp['errcode'] == 1){
                throw new Exception($column_tmp['status']);
            }else{
                $customer[$column]=$column_tmp[$column];
            }
        }
        return array("customer"=>$customer,"errcode"=>0,'status'=>'query success');
    }
}

function updateFromPhone($phone,$customer){
    $entity_id_array = getEntityId($phone);
    if($entity_id_array['errcode'] == 1){
        throw new Exception($entity_id_array['status']);
    }else{
        $entity_id = $entity_id_array['entity_id'];
        foreach($customer as $customer_name=>$column_value){
            $enable_list = array('sex','nickname','myimage','birthday');
            if(in_array($customer_name,$enable_list)){
                $column_tmp = updateCustomerColumn($entity_id,$customer_name,$column_value);
                if($column_tmp['errcode'] == 1){
                    throw new Exception($column_tmp['status']);
                }
            }
        }
        return array("customer"=>'',"errcode"=>0,'status'=>'update success');
    }
}

try{
    $param = $_REQUEST;
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
        /*if(isset($param['sex']) and trim($param['sex']) != ''){
            $customer['sex'] = $param['sex'];
            $update_flag = 1;
        }
        if(isset($param['nickname']) and trim($param['nickname']) != ''){
            $customer['nickname'] = $param['nickname'];
            $update_flag = 1;
        }
        if(isset($param['myimage']) and trim($param['myimage']) != ''){
            $customer['myimage'] = $param['myimage'];
            $update_flag = 1;
        }
        if(isset($param['birthday']) and trim($param['birthday']) != ''){
            $customer['birthday'] = $param['birthday'];
            $update_flag = 1;
        }*/

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
    $result = array("customer"=>$customer,"status"=>$status,"errcode"=>$errcode);
    echo json_encode($result);
}catch (Exception $e){
    $result = array("customer"=>'',"status"=>$e->getMessage(),"errcode"=>1);
    echo json_encode($result);
}
?>