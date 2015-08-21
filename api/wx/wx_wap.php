<?php
ini_set('date.timezone','Asia/Shanghai');
require_once dirname(__FILE__)."/../util/global.php";
require_once dirname(__FILE__)."/lib/WxPay.Api.php";
require_once dirname(__FILE__)."/example/WxPay.JsApiPay.php";

function MakeSign($str)
{
    //签名步骤一：按字典序排序参数
    ksort($str);
    reset($str);
    $param = '';
    foreach ($str AS $key => $val)
    {
        $param  .= "$key=$val&";
    }
    //签名步骤二：在string后加入KEY
    $string = $param . "&key=".WxPayConfig::KEY;
    //签名步骤三：MD5加密
    $string = md5($string);
    //签名步骤四：所有字符转为大写
    $result = strtoupper($string);
    return $result;
}

function getPrepayId($total_amount,$order_number,$attach){
    $input = new WxPayUnifiedOrder();
    $input->SetBody("美果优鲜订单");
    $input->SetAttach("缤纷水果");
    $input->SetOut_trade_no($attach);
    $input->SetTotal_fee($total_amount*100);
	$input->SetAttach($order_number);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("商品标签");
    $input->SetNotify_url("https://dev1.meiguoyouxian.com/magento/custom/api/util/wx.php");
    $input->SetTrade_type("APP");
    $order = WxPayApi::unifiedOrder($input);
    return $order;
}


function pay($order){
    $sign_str = array();
    $api = new WxPayApi();
    $sign_str['appid'] = $order['appid'];
    $sign_str['partnerid'] = $order['mch_id'];
    $sign_str['prepayid'] = $order['prepay_id'];
    $sign_str['package'] = 'Sign=WXPay';
    $sign_str['noncestr'] = $api::getNonceStr();
    $sign_str['timestamp'] = time();
    $sign_str['sign']  = MakeSign($sign_str);
    $sign_str['success'] = $order['return_code'] == 'SUCCESS'?1:0;
    $sign_str['return_msg'] = $order['return_msg'];
    return $sign_str;
}

/*try{
    $param = $_REQUEST;
    $order = getPrepayId();
    $pay_str = pay($order);
    if($pay_str['success'] == 0){
        $errorcode = 10052;
        throw new Exception($pay_str['return_msg']);
    }
    $result = array("data"=>$pay_str,"success"=>1,"errorcode"=>0);

    if(isset($param['array']) and trim($param['array']) != '' ){
        dump($result);
        exit;
    }
    echo json_encode($result);
}catch (Exception $e){
    $result = array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    echo json_encode($result);
}*/

?>