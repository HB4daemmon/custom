<?php
require_once dirname(__FILE__)."/../../api/util/connection.php";

if ($_FILES["file"]["type"] == "text/csv")
{
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
}
else
{
    echo "文件格式错误，请上传csv的格式。";
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
            if($order_id !='' and $order_sn != ''){
                try{
                    $conn = orange_connect();
                    $sql = "update ecs_pay_log set is_paid = 1 where log_id = '$order_id'";
                    $sqlres = $conn->query($sql);
                    $log_time = strtotime("now");
                    $sql1="Update ecs_order_info SET order_status = '1',confirm_time = '$log_time',pay_status = '2',pay_time = '$log_time',money_paid = order_amount,order_amount = 0
                                  where order_id = '$order_id'";
                    $sqlres1 = $conn->query($sql1);

                    $sql2 = "INSERT INTO ecs_order_action (order_id, action_user, order_status, shipping_status, pay_status, action_place, action_note, log_time)
                    values ('$order_id','admin',2,0,0,0,'通过接口','$log_time')";
                    $sqlres2 = $conn->query($sql2);
                    if(!$sqlres or !$sqlres1 or !$sqlres2){
                        throw new Exception('处理失败');
                    }
                    $conn->commit();
                    $conn->close();
                    order_paid($order_id, 2);
                    echo "订单号[".$order_sn."]已处理<br>";
                }catch(Exception $e){
                    echo "订单号[".$order_sn."]处理失败<br>";
                    $conn->rollback();
                    $conn->close();
                }
            }
        }
    }
}

?>
