<?php
require_once dirname(__FILE__)."/connection.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/lib_payment.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/lib_payment.php";

function Init($xml)
{
    $obj = new self();
    $obj->FromXml($xml);
    if($obj->values['return_code'] != 'SUCCESS'){
        return $obj->GetValues();
    }
    $obj->CheckSign();
    return $obj->GetValues();
}

try{
    $param = $_REQUEST;
    $str = implode(',',$param);
    $conn = orange_connnect();
    $sql = "INSERT INTO custom_wx_recall values (return_code,return_msg) values ('1','$str')";

    //order_paid($order_sn, 2);
    $conn->commit();
    $conn->close();
}catch(Exception $e){
    $conn->rollback();
    $conn->close();
    return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
}
?>