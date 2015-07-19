<?php
//post param:
//1.method: 'get','update','create'
//2.phone
//3.additional
//return:
//{promotion:{},status:,errcode:}
//http://localhost/magento/custom/api/promotion/promotion.php?method=get&phone=15151834774
//http://localhost/magento/custom/api/promotion/promotion.php?method=create&phone=15151834774&additional=1
//http://localhost/magento/custom/api/promotion/promotion.php?method=update&additional[promotion_id]=5&additional[code]=delete
require_once(dirname(__FILE__).'/action/promotion_action.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type:text/html; charset=utf-8');

try{
    $param = $_REQUEST;
    $result = array();
    $promotions = array();
    $errcode = 0;

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errcode = 1;
        throw new Exception('NONE_METHOD');
    }

    $method = $param['method'];

    if(isset($param['additional']) and trim($param['additional'] != '')){
        $additional = $param['additional'];
    }else{
        $additional = '';
    }

    if($method == 'get'){
        if(!isset($param['phone']) or trim($param['phone'] == '')){
            $errcode = 1;
            throw new Exception('NONE_PHONE');
        }
        $phone = $param['phone'];

        if($additional == ''){
            $sort = 'desc';
        }else{
            $sort = $additional;
        }
        $return = getPromotions($phone,$sort);
        $errcode = $return['errcode'];
        $status = $return['status'];
        if($errcode == 0){
            $promotions = $return['promotions'];
        }else{
            throw new Exception($status);
        }
    }else if ($method == 'create'){
        if(!isset($param['phone']) or trim($param['phone'] == '')){
            $errcode = 1;
            throw new Exception('NONE_PHONE');
        }
        $phone = $param['phone'];

        if($additional == ''){
            throw new Exception("NO_RULE_ID_WHEN_CREATE_PROMOTIONS");
        }else{
            $rule_id = $additional;
        }

        $return = createPromotion($phone,$rule_id);
        $errcode = $return['errcode'];
        $status = $return['status'];
        $promotions = $return['promotions'];
        if($errcode != 0){
            throw new Exception($status);
        }
    }else if($method == 'update'){
        if($additional == ''){
            throw new Exception("NO_PARAMS_WHEN_UPDATE_PROMOTION");
        }else{
            if(!isset($additional['promotion_id']) or trim($additional['promotion_id']) == ''){
                throw new Exception("NO_PROMOTION_ID_WHEN_UPDATE_PROMOTION");
            }else if(!isset($additional['code']) or trim($additional['code'] == '')){
                throw new Exception("NO_UPDATE_CODE_WHEN_UPDATE_PROMOTION");
            }
            $promotion_id = $additional['promotion_id'];
            $code = $additional['code'];
        }
        $return = updatePromotion($promotion_id,$code);
        $errcode = $return['errcode'];
        $status = $return['status'];
        $promotions = $return['promotions'];
        if($errcode != 0){
            throw new Exception($status);
        }
    }else{
        throw new Exception("INVALID_METHOD");
    }
    $result = array("promotions"=>$promotions,"status"=>$status,"errcode"=>$errcode);
    echo json_encode($result);
}catch (Exception $e){
    $result = array("promotions"=>'',"status"=>$e->getMessage(),"errcode"=>1);
    echo json_encode($result);
}

?>