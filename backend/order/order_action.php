<?php
require_once dirname(__FILE__)."/../../api/util/connection.php";

if ($_FILES["file"]["error"] > 0)
{
    echo "上传文件失败: " . $_FILES["file"]["error"] . "<br />";
}
else
{
    $file = dirname(__FILE__)."/upload/" . $_FILES["file"]["name"];
    if (file_exists($file)){
        unlink($file);
    }
    move_uploaded_file($_FILES["file"]["tmp_name"], $file);
}


if (file_exists($file))
{
    $f = fopen($file,'r');
    $skip_lines = 0;
    while($data = fgetcsv($f)){
        $skip_lines ++;
        if($skip_lines > 3){
            $order_id = explode('_',$data[4])[1];
            $order_sn = explode('_',$data[4])[0];
            $transaction_type =mb_convert_encoding($data[5],"UTF-8", "GB2312");
            if($transaction_type == '交易退款'){
                try{
                    $conn = orange_connect();
                    $sql = "update ecs_pay_log set is_paid = 0 where log_id = '$order_id'";
                    $sqlres = $conn->query($sql);
                    $log_time = strtotime("now");
                    $sql1="Update ecs_order_info SET order_status = '3',confirm_time = '$log_time',pay_status = '0',pay_time = '$log_time',money_paid = 0 ,pay_note='refund',order_amount=goods_amount+shipping_fee
                                  where order_id = '$order_id'";
                    $sqlres1 = $conn->query($sql1);

                    $sql2 = "INSERT INTO ecs_order_action (order_id, action_user, order_status, shipping_status, pay_status, action_place, action_note, log_time)
                    values ('$order_id','admin',3,0,0,0,'接口退款','$log_time')";
                    $sqlres2 = $conn->query($sql2);
                    if(!$sqlres or !$sqlres1 or !$sqlres2){
                        throw new Exception('处理失败');
                    }
                    $conn->commit();
                    $conn->close();
                    echo "订单号[".$order_sn."]已处理<br>";
                }catch(Exception $e){
                    echo "订单号[".$order_sn."]处理失败<br>";
                    $conn->rollback();
                    $conn->close();
                }
            }else{
                if($order_id !='' and $order_sn != ''){
                    try{
                        $conn = orange_connect();
                        $s = "select pay_note from ecs_order_info where order_id = '$order_id'";
                        $sres = $conn->query($s);
                        $pay_note = $sres->fetch_assoc()['pay_note'];
                        if($pay_note != 'refund'){
                            $sql = "update ecs_pay_log set is_paid = 1 where log_id = '$order_id'";
                            $sqlres = $conn->query($sql);
                            $log_time = strtotime("-8 hours");
                            $sql1="Update ecs_order_info SET order_status = '1',confirm_time = '$log_time',pay_status = '2',pay_time = '$log_time',money_paid = goods_amount+shipping_fee,order_amount = 0
                                  where order_id = '$order_id' and order_status != '1' and pay_status != '2'";
                            $sqlres1 = $conn->query($sql1);

                            $sql2 = "INSERT INTO ecs_order_action (order_id, action_user, order_status, shipping_status, pay_status, action_place, action_note, log_time)
                                      values ('$order_id','admin',1,0,2,0,'接口支付','$log_time')";
                            $sqlres2 = $conn->query($sql2);
                            if(!$sqlres or !$sqlres1 or !$sqlres2){
                                throw new Exception('处理失败');
                            }
                            $conn->commit();
                            $conn->close();
                            echo "订单号[".$order_sn."]已处理<br>";
                        }
                    }catch(Exception $e){
                        echo "订单号[".$order_sn."]处理失败<br>";
                        $conn->rollback();
                        $conn->close();
                    }
                }
            }

        }
    }
}

?>
