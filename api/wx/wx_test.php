<?php
ini_set('date.timezone','Asia/Shanghai');
require_once dirname(__FILE__)."/../util/global.php";
require_once dirname(__FILE__)."/lib/WxPay.Api.php";
require_once dirname(__FILE__)."/example/WxPay.JsApiPay.php";

try{
    $pay = new WxPayApi();
    $xmlstring = <<<XML
<?xml version="1.0" encoding="ISO-8859-1"?>
<xml>
<return_code>George</return_code>
<from>John</from>
<heading>Reminder</heading>
<body>Don't forget the meeting!</body>
</xml>
XML;
    $url = dirname(__FILE__)."https://dev1.meiguoyouxian.com/magento/custom/api/util/wx.php";
    $response = WxPayApi::postXmlCurl($xmlstring, $url, false, $timeOut);
    var_dump($response);

}catch(Exception $e){

}
?>