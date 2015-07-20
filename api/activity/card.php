<?php
//------------------------------------------------------------------------------------------
/* 活动卡片API
 *
 * @param:GET/POST
 *   $method       方法名: get 获取/update 更新数量/use 获取优惠
 *   $mobile       手机号
 *
 * @return:JSON(data,success,errorcode)
 *   [0]..[n]        地址数组
 *     address_id    地址ID
 *     name          姓名
 *     tel           电话
 *     address       地址
 *     remark        备注
 *
 * by Daemon 2015-7-20
 */
//------------------------------------------------------------------------------------------

require_once(dirname(__FILE__).'/action/card_action.php');

try{
    /*$param = $_REQUEST;
    $result = array();
    $card_count = array();
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
    $result = array("promotions"=>$promotions,"status"=>$status,"errcode"=>$errcode);*/
    
    echo json_encode($result);
}catch (Exception $e){
    $result = array("promotions"=>'',"status"=>$e->getMessage(),"errcode"=>1);
    echo json_encode($result);
}

?>