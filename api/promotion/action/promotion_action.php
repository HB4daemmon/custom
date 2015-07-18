<?php
require_once(dirname(__FILE__).'/../../util/connection.php');

function getCouponCode($rule_id){
    try{
        $conn = db_connect();
        $sql = "select * from salesrule_coupon
                        where rule_id = $rule_id
                          and now() < expiration_date
                          and times_used = 0
                          and coupon_id not in (select coupon_id from custom_promotions)
                        limit 1";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            throw new Exception('GET_COUPON_ERROR');
        }
        $count = $sqlres -> num_rows;
        if ($count == 0){
            throw new Exception('NO_UNUSED_COUPON_OR_RULES_ERROR');
        }
        $row = $sqlres->fetch_assoc();
        $coupon = array();
        $coupon_array = ['coupon_id','rule_id','code','times_used','expiration_date'];
        foreach($coupon_array as $c){
            $coupon[$c] = $row[$c];
        }
        $conn->close();
        return array("coupon"=>$coupon,"errcode"=>0,"status"=>'get coupon success');
    }catch (Exception $e){
        $conn->close();
        return array("coupon"=>'',"errcode"=>1,"status"=>$e->getMessage());
    }
}

function createPromotion($phone,$rule_id){
    try{
        $conn = db_connect();
        $coupon_array = getCouponCode($rule_id);
        $coupon = $coupon_array['coupon'];
        if($coupon_array['errcode'] == 1){
            throw new Exception($coupon_array['status']);
        }
        $coupon_id = $coupon['coupon_id'];
        $sql = "insert into custom_promotions (coupon_id,phone,catalog,enable_flag)
                       values ($coupon_id,'".$phone."','',1)";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            throw new Exception('CREATE_PROMOTION_ERROR');
        }
        $conn->commit();
        $conn->close();
        return array("promotion"=>'',"errcode"=>0,"status"=>'create promotion success');
    }catch (Exception $e){
        $conn->rollback();
        $conn->close();
        return array("promotion"=>'',"errcode"=>1,"status"=>$e->getMessage());
    }
}

?>