<?php
//post param:
//1.method: 'get','update','create'
//2.mobile
//3.additional
//return:
//{promotion:{},status:,errcode:}
//http://localhost/magento/custom/api/promotion/promotion.php?method=get&mobile=15151834774
//http://localhost/magento/custom/api/promotion/promotion.php?method=create&mobile=15151834774&additional=1
//http://localhost/magento/custom/api/promotion/promotion.php?method=update&additional[promotion_id]=5&additional[code]=delete
require_once(dirname(__FILE__).'/action/promotion_action.php');

try{
    $param = $_REQUEST;
    $result = array();
    $promotions = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10025;
        throw new Exception('NONE_METHOD');
    }

    $method = $param['method'];

    if(isset($param['additional']) and trim($param['additional'] != '')){
        $additional = $param['additional'];
    }else{
        $additional = '';
    }

    if($method == 'get'){
        if(!isset($param['mobile']) or trim($param['mobile'] == '')){
            $errorcode = 10026;
            throw new Exception('NONE_MOBILE_PHONE_NUMBER');
        }
        $mobile = $param['mobile'];

        if($additional == ''){
            $sort = 'desc';
        }else{
            $sort = $additional;
        }
        $return = getPromotions($mobile,$sort);
        $errorcode = $return['errorcode'];
        $success = $return['success'];
        if($success == 1){
            $promotions = $return['data'];
        }else{
            $errorcode = $return['errorcode'];
            throw new Exception($return['data']);
        }
    }else if ($method == 'create'){
        if(!isset($param['mobile']) or trim($param['mobile'] == '')){
            $errorcode = 10027;
            throw new Exception('NONE_MOBILE_PHONE_NUMBER');
        }
        $mobile = $param['mobile'];

        if($additional == ''){
            $errorcode = 10028;
            throw new Exception("NO_RULE_ID_WHEN_CREATE_PROMOTIONS");
        }else{
            $rule_id = $additional;
        }

        $return = createPromotion($mobile,$rule_id);
        $errorcode = $return['errorcode'];
        $success = $return['success'];
        $promotions = $return['data'];
        if($success != 1){
            throw new Exception($return['data']);
        }
    }else if($method == 'use' or $method = 'disable'){
        if(isset($param['additional']) and trim($param['additional'] != '')){
            $promotion_id = $param['additional'];
        }else{
            $errorcode = 10029;
            throw new Exception("NO_PROMOTION_ID_WHEN_USE_OR_DISABLE_PROMORION");
        }

        $return = updatePromotion($promotion_id,$method);
        $errorcode = $return['errorcode'];
        $success = $return['success'];
        $promotions = $return['data'];
        if($success == 0){
            throw new Exception($return['data']);
        }
    }else{
        $errorcode = 10030;
        throw new Exception("INVALID_METHOD");
    }
    $result = array("data"=>$promotions,"success"=>1,"errorcode"=>0);
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