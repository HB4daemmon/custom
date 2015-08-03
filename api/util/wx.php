<?php
define('IN_ECTOUCH',TRUE);
require_once dirname(__FILE__)."/connection.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/init.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/lib_payment.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/lib_order.php";

/*foreach ($_GET as $key=>$value)
{
    logger("Key: $key; Value: $value");
}
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
logger($postStr);*/

//日志记录
function logger($log_content)
{
    try{
        $max_size = 100000;
        $log_filename = "log.xml";
        if(file_exists($log_filename)){
            echo 'exist';
        }
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
    }catch (Exception $e){
        echo $e->getMessage();
    }
}

try{

    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    logger($postStr);
    $conn = orange_connect();
    if(isset($postStr)){
        $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
        $return_code = $postObj->return_code;
        $appid = $postObj->appid;
        $attach = $postObj->attach;
        $bank_type = $postObj->bank_type;
        $cash_fee = ($postObj->cash_fee)/100;
        $fee_type = $postObj->fee_type;
        $is_subscribe = $postObj->is_subscribe;
        $mch_id = $postObj->mch_id;
        $nonce_str = $postObj->nonce_str;
        $openid = $postObj->openid;
        $out_trade_no = $postObj->out_trade_no;
        $result_code = $postObj->result_code;
        $sign = $postObj->sign;
        $time_end = $postObj->time_end;
        $total_fee = ($postObj->total_fee)/100;
        $trade_type = $postObj->trade_type;
        $transaction_id = $postObj->transaction_id;
        $return_msg = $postObj->result_code;

        if($return_code == 'SUCCESS'){
            $sql = "SELECT * from custom_wx_recall where transaction_id = '$transaction_id'";
            $sqlres = $conn->query($sql);
            if($sqlres->num_rows == 0){
                $sql1 = "INSERT INTO custom_wx_recall(return_code,return_msg,attach,bank_type,cash_fee,fee_type,is_subscribe,mch_id,nonce_str,openid,out_trade_no,result_code,sign,time_end,total_fee,trade_type,transaction_id)
                        values ('$return_code','$return_msg','$attach','$bank_type','$cash_fee','$fee_type','$is_subscribe','$mch_id','$nonce_str','$openid','$out_trade_no','$result_code','$sign','$time_end','$total_fee','$trade_type','$transaction_id')";
                $conn->query($sql1);
                order_paid($attach, 2);
                logger($sql1);
            }
        }else{
            $sql = "INSERT INTO custom_wx_recall(return_code,return_msg) values ('$return_code','$return_msg')";
            $conn->query($sql);
        }
    }

    $conn->commit();
    $conn->close();
}catch(Exception $e){
    $conn->rollback();
    $conn->close();
    return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
}
?>