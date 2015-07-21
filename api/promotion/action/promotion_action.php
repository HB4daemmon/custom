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
            $errorcode = 10018;
            throw new Exception('GET_COUPON_ERROR');
        }
        $count = $sqlres -> num_rows;
        if ($count == 0){
            $errorcode = 10019;
            throw new Exception('NO_UNUSED_COUPON_OR_RULES_ERROR');
        }
        $row = $sqlres->fetch_assoc();
        $coupon = array();
        $coupon_array = ['coupon_id','rule_id','code','times_used','expiration_date'];
        foreach($coupon_array as $c){
            $coupon[$c] = $row[$c];
        }
        $conn->close();
        return array("data"=>$coupon,"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function createPromotion($mobile,$rule_id){
    try{
        $conn = db_connect();

        $validation = validateUniquePromotion($mobile,$rule_id);
        if($validation['success'] == 0){
            throw new Exception($validation['data']);
        }

        $coupon_array = getUnusedCouponCode($rule_id);
        $coupon = $coupon_array['data'];
        if($coupon_array['success'] == 0){
            throw new Exception($coupon_array['data']);
        }
        $coupon_id = $coupon['coupon_id'];
        $sql = "insert into custom_promotions (coupon_id,mobile,catalog,enable_flag)
                       values ($coupon_id,'".$mobile."','',1)";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10020;
            throw new Exception('CREATE_PROMOTION_ERROR');
        }
        $conn->commit();
        $conn->close();
        return array("data"=>'Create promotion success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->rollback();
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function validateUniquePromotion($mobile,$rule_id){
    try{
        $conn = db_connect();

        $sql = "select * from custom_promotions
                        where mobile = $mobile
                          and rule_id = $rule_id)";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10031;
            throw new Exception('QUERY_PROMOTION_ERROR');
        }
        $count = $sqlres->num_rows;
        if($count != 0){
            $errorcode = 10032;
            throw new Exception('DUPLICATE_PROMOTION_CODE');
        }
        $conn->close();
        return array("data"=>'Validate promotion success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function getPromotions($mobile,$sort){
    try{
        $conn = db_connect();
        $promotions = array();
        $sql = "select * from custom_promotions cp,
                              salesrule_coupon sc
                        where cp.coupon_id = sc.coupon_id
                          and mobile = '$mobile'
                        order by cp.promotion_id $sort";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10021;
            throw new Exception('GET_PROMOTION_ERROR');
        }

        $count = $sqlres -> num_rows;
        if ($count == 0){
            $errorcode = 10022;
            throw new Exception('THIS_PHONE_HAS_NO_PROMOTION');
        }

        $promotion_array = ['promotion_id','coupon_id','mobile','catalog','enable_flag','rule_id','times_used','code','expiration_date'];

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
        return array("data"=>$promotions,"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function updatePromotion($promotion_id,$code){
    try{
        $conn = db_connect();
        if ($code == 'use'){
            $sql = "update custom_promotions set enable_flag = 0
                                           where promotion_id = $promotion_id";
        }else{
            $errorcode = 10023;
            throw new Exception('INVALID_UPDATE_CODE');
        }

        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10024;
            throw new Exception('UPDATE_PROMOTION_ERROR');
        }
        $conn->commit();
        $conn->close();
        return array("data"=>'Update promotion success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->rollback();
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

?>