<?php
require_once(dirname(__FILE__).'/../../util/connection.php');

function getCardCount($mobile){
    try{
        $conn = db_connect();
        $card = array();
        $sql = "select * from custom_activity_cards
                         where mobile = '$mobile'";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10001;
            throw new Exception('GET_CARD_COUNT_ERROR');
        }
        $count = $sqlres -> num_rows;
        $card['mobile'] = $mobile;
        if ($count == 0){
            $card['card_count'] = 0;
            $card['status'] = 0;
        }else{
            $row = $sqlres->fetch_assoc();
            $coupon = array();
            $card['card_count'] = $row['card_count'];
            $card['status'] = $row['status'];
        }
        $conn->close();
        return array("data"=>$card,"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function updateCardCount($mobile,$card_count){
    try{
        $conn = db_connect();

        $sql = "select * from custom_activity_cards
                         where mobile = '$mobile'";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10002;
            throw new Exception('GET_CARD_COUNT_ERROR');
        }
        $count = $sqlres -> num_rows;
        if ($count == 0){
            if ($card_count >= 7){
                $card_count = 7;
                $status = 1;
            }else{
                $status = 0;
            }
            $sql="insert into custom_activity_cards(mobile,card_count,status) values ('$mobile',$card_count,$status) ";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10003;
                throw new Exception('UPDATE_CARD_COUNT_ERROR');
            }
        }else{
            $row = $sqlres->fetch_assoc();
            if($row['status'] == 0){
                if($row['card_count'] + $card_count >= 7){
                    $card_count = 7;
                    $status = 1;
                }else{
                    $card_count = $row['card_count'] + $card_count;
                }

                $sql = "update custom_activity_cards
                            set card_count=$card_count,status=$status,last_update_date=now()
                          where mobile = '$mobile'";
                $sqlres = $conn->query($sql);
                if(!$sqlres){
                    $errorcode = 10004;
                    throw new Exception('UPDATE_CARD_COUNT_ERROR');
                }
            }
        }

        $conn->commit();
        $conn->close();
        return array("data"=>'update card count success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->rollback();
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function useCardCount($mobile){
    try{
        $conn = db_connect();
        $return = getCardCount($mobile);
        if($return['data']['status'] != 1){
            $errorcode = 10005;
            throw new Exception('INVALID_CARD_STATUS');
        }
        $sql = "update custom_activity_cards
                            set status=2,last_update_date=now()
                          where mobile = '$mobile'";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 10006;
            throw new Exception('USE_CARD_ERROR');
        }

        $conn->close();
        return array("data"=>'use card success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

?>