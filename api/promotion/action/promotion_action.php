<?php
require_once(dirname(__FILE__).'/../../util/connection.php');

function getUnusedCouponCode($rule_id){
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
        $coupon_array = getUnusedCouponCode($rule_id);
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
        return array("promotions"=>'',"errcode"=>0,"status"=>'create promotion success');
    }catch (Exception $e){
        $conn->rollback();
        $conn->close();
        return array("promotions"=>'',"errcode"=>1,"status"=>$e->getMessage());
    }
}

function getPromotions($phone,$sort){
    try{
        $conn = db_connect();
        $promotions = array();
        $sql = "select * from custom_promotions cp,
                              salesrule_coupon sc
                        where cp.coupon_id = sc.coupon_id
                          and phone = '$phone'
                        order by cp.promotion_id $sort";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            throw new Exception('GET_PROMOTION_ERROR');
        }

        $count = $sqlres -> num_rows;
        if ($count == 0){
            throw new Exception('THIS_PHONE_HAS_NO_PROMOTION');
        }

        $promotion_array = ['promotion_id','coupon_id','phone','phone','catalog','enable_flag','rule_id','times_used','code','expiration_date'];

        $i = 1;
        while($row = $sqlres->fetch_assoc()){
            $promotion = array();
            foreach($promotion_array as $p){
                $promotion[$p] = $row[$p];
            }
            $promotions[$i] = $promotion;
            $i ++;
        }

        $conn->close();
        return array("promotions"=>$promotions,"errcode"=>0,"status"=>'get promotions success');
    }catch (Exception $e){
        $conn->close();
        return array("promotions"=>'',"errcode"=>1,"status"=>$e->getMessage());
    }
}

function updatePromotion($promotion_id,$code){
    try{
        $conn = db_connect();
        if ($code == 'delete'){
            $sql = "update custom_promotions set enable_flag = 0
                                           where promotion_id = $promotion_id";
        }else{
            throw new Exception('INVALID_UPDATE_CODE');
        }

        $sqlres = $conn->query($sql);
        if(!$sqlres){
            throw new Exception('UPDATE_PROMOTION_ERROR');
        }
        $conn->commit();
        $conn->close();
        return array("promotions"=>'',"errcode"=>0,"status"=>'update promotion success');
    }catch (Exception $e){
        $conn->rollback();
        $conn->close();
        return array("promotions"=>'',"errcode"=>1,"status"=>$e->getMessage());
    }
}

?>