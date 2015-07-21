<?php
//post param:
//1.method: 'get','update'
//2.phone number
//3.update param
//return:
//{customer:{},status:,errcode:}
require_once(dirname(__FILE__).'/action/customer_action.php');

function getFromMobile($mobile){
    $entity_id_array = getEntityId($mobile);
    $customer = array();
    if($entity_id_array['success'] == 0){
        throw new Exception($entity_id_array['data']);
    }else{
        $entity_id = $entity_id_array['data'];
        $column_array = array('sex','nickname','birthday','myimage');
        foreach($column_array as $column){
            $column_tmp = getCustomerColumn($entity_id,$column);
            if($column_tmp['success'] == 0){
                throw new Exception($column_tmp['data']);
            }else{
                $customer[$column]=$column_tmp[$column];
            }
        }
        return array("data"=>$customer,"success"=>1,'errorcode'=>0);
    }
}

function updateFromMobile($mobile,$customer){
    $entity_id_array = getEntityId($mobile);
    if($entity_id_array['success'] == 0){
        throw new Exception($entity_id_array['data']);
    }else{
        $entity_id = $entity_id_array['data'];
        foreach($customer as $customer_name=>$column_value){
            $enable_list = array('sex','nickname','myimage','birthday');
            if(in_array($customer_name,$enable_list)){
                $column_tmp = updateCustomerColumn($entity_id,$customer_name,$column_value);
                if($column_tmp['success'] == 0){
                    throw new Exception($column_tmp['data']);
                }
            }
        }
        return array("data"=>'Update customer success',"success"=>1,'errorcode'=>0);
    }
}

try{
    $param = $_REQUEST;
    $result = array();
    $customer = array();
    $status = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10015;
        throw new Exception('NONE_METHOD');
    }

    if(!isset($param['mobile']) or trim($param['mobile'] == '')){
        $errorcode = 10016;
        throw new Exception('NONE_MOBILE_PHONE_NUMBER');
    }

    $method = $param['method'];
    $mobile = $param['mobile'];
    if($method == 'get'){
        $return = getFromMobile($mobile);
        $errorcode = $return['errorcode'];
        $success = $return['success'];
        if($success == 1){
            $data = $return['data'];
        }else{
            throw new Exception($return['data']);
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
            $data = 'NO_CHANGE';
        }else{
            $return = updateFromMobile($mobile,$customer);
            $errorcode = $return['errorcode'];
            $success = $return['success'];
            if($success == 1){
                $data = $return['data'];
            }else{
                throw new Exception($return['data']);
            }
        }

    }else{
        $errorcode = 10017;
        throw new Exception("INVALID_METHOD");
    }
    $result = array("data"=>$data,"success"=>1,"errorcode"=>0);
    if(isset($param['array']) and trim($param['array']) != '' ){
        dump($result);
        exit;
    }
    echo json_encode($result);
}catch (Exception $e){
    $result = array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    echo json_encode($result);
}
?>