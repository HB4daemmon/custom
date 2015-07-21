<?php
//------------------------------------------------------------------------------------------
/* 活动卡片API
 *
 * @param:GET/POST
 *   $method       方法名: get 获取/update 更新数量/use 获取优惠
 *   $mobile       手机号
 *   $additional   附加参数
 *
 * @return:JSON(data,success,errorcode)
 *   [0]..[n]        地址数组
 *     mobile         电话
 *     card_count    卡片数量
 *     status        状态:0(未激活)，1(已激活)，2(已使用)
 *
 * exp:http://localhost/magento/custom/api/activity/card.php?method=get&mobile=15151834774
 *     http://localhost/magento/custom/api/activity/card.php?method=update&mobile=15151834774&additional=1
 *     http://localhost/magento/custom/api/activity/card.php?method=use&mobile=15151834774&additional=1
 * by Daemon 2015-7-20
 */
//------------------------------------------------------------------------------------------

require_once(dirname(__FILE__).'/action/card_action.php');

try{
    $param = $_REQUEST;
    $result = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10007;
        throw new Exception('NONE_METHOD');
    }

    if(!isset($param['mobile']) or trim($param['mobile'] == '')){
        $errorcode = 10008;
        throw new Exception('NONE_MOBILE');
    }


    $method = $param['method'];
    $mobile = $param['mobile'];

    if(isset($param['additional']) and trim($param['additional'] != '')){
        $additional = $param['additional'];
    }else{
        $additional = '';
    }

    if($method == 'get'){
        $return = getCardCount($mobile);
        $errorcode = $return['errorcode'];
        $success = $return['success'];

        if($success == 1){
            $data = $return['data'];
        }else{
            throw new Exception($return['data']);
        }
    }else if($method == 'update'){
        if($additional == ''){
            $errorcode = 10009;
            throw new Exception("NO_UPDATE_PARAM_WHEN_UPDATE_CARD");
        }else{
            $card_count = $additional;
        }
        $return = updateCardCount($mobile,$card_count);
        $errorcode = $return['errorcode'];
        $success = $return['success'];

        if($success == 1){
            $data = $return['data'];
        }else{
            throw new Exception($return['data']);
        }
    }else if($method = 'use'){
        $return = useCardCount($mobile);
        $errorcode = $return['errorcode'];
        $success = $return['success'];

        if($success == 1){
            $data = $return['data'];
        }else{
            throw new Exception($return['data']);
        }
    }
    else{
        $errorcode = 10010;
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
    if(isset($param['array']) and trim($param['array']) != '' ){
        dump($result);
        exit;
    }
    echo json_encode($result);
}

?>