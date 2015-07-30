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

function getPrepayId(){
    $input = new WxPayUnifiedOrder();
    $input->SetBody("test1");
    $input->SetAttach("test1");
    $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
    $input->SetTotal_fee("1");
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test");
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
    $sign_str['timestamp'] = date("YmdHis");
    $sign_str['sign']  = MakeSign($sign_str);
    return $sign_str;
}

$order = getPrepayId();
   echo(pay($order));
?>