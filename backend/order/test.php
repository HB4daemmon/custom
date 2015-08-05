<?php
require_once dirname(__FILE__)."/../../api/util/connection.php";

try{
    $conn = orange_connect();
    $sql = "update ecs_pay_log set is_paid = 1 where log_id = 58352";
    $sqlres = $conn->query($sql);
    $log_time = strtotime("now");
    $sql1="Update ecs_order_info SET order_status = '1',confirm_time = '$log_time',pay_status = '2',pay_time = '$log_time',money_paid = order_amount,order_amount = 0
                                  where order_id = '58352'";
    $sqlres1 = $conn->query($sql1);

    $sql2 = "INSERT INTO ecs_order_action (order_id, action_user, order_status, shipping_status, pay_status, action_place, action_note, log_time)
                    values (58352,'admin',2,0,0,0,'通过接口','$log_time')";
    $sqlres2 = $conn->query($sql2);
    if(!$sqlres or !$sqlres1 or !$sqlres2){
        throw new Exception('处理失败');
    }
    $conn->commit();
    $conn->close();
    echo "Update success";
}catch(Exception $e){
    echo "Update error";
    $conn->rollback();
    $conn->close();
}

?>
