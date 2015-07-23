<?php
    require_once(dirname(__FILE__).'/../../util/connection.php');

    function getEntityId($mobile){
        try{
            $conn = db_connect();
            $sql = "select entity_id from customer_entity
                    where email = '$mobile@meiguoyouxian.com'";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10011;
                throw new Exception('QUERY_ENTITY_ERROR');
            }
            $row = $sqlres->fetch_assoc();
            $entity_id = $row['entity_id'];
            if($entity_id == ''){
                $errorcode = 10012;
                throw new Exception('NO_THIS_ACCOUNT');
            }
            $conn->close();
            return array("data"=>$entity_id,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array("data"=>$e->getMessage().$sql,"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function getCustomerColumn($entity_id,$column_name){
        try{
            $conn = db_connect();
            $sql = "select cev.value from customer_entity_varchar cev,
                          eav_attribute ea
                    where cev.attribute_id = ea.attribute_id
                      and ea.attribute_code = '".$column_name."'
                      and cev.entity_id = $entity_id";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10013;
                throw new Exception('QUERY_'.strtoupper($column_name).'_ERROR');
            }
            $row = $sqlres->fetch_assoc();
            $column_value = $row["value"];
            $conn->close();
            return array($column_name=>$column_value,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function updateCustomerColumn($entity_id,$column_name,$column_value){
        /*try{
            $conn = db_connect();
            $sql = "update customer_entity_varchar cev
                    set value = ?
                    where cev.entity_id = ?
                      and cev.attribute_id = (select attribute_id from eav_attribute
                                              where attribute_code = ?)";
            $stmt=$conn->prepare($sql);
            $stmt->bind_param("sis",mysql_real_escape_string($column_value) ,$entity_id,$column_name);
            $stmt->execute();
            if(!$stmt){
                throw new Exception('UPDATE_'.strtoupper($column_name).'_ERROR');
            }
            $stmt->close();
            $conn->commit();
            $conn->close();
            return array($column_name=>$column_value,"errcode"=>0,"status"=>'update '.$column_name.' success');
        }catch (Exception $e){
            $stmt->close();
            $conn->rollback();
            $conn->close();
            return array($column_name=>-1,"errcode"=>1,"status"=>$e->getMessage());
        }*/
		try{
            $conn = db_connect();
			$column_value = addslashes($column_value);
            $sql = "update customer_entity_varchar cev
                    set value = '".$column_value."'
                    where cev.entity_id = $entity_id
                      and cev.attribute_id = (select attribute_id from eav_attribute
                                              where attribute_code = '$column_name'
                                                and attribute_type_id = 1)";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10014;
                throw new Exception('UPDATE_'.strtoupper($column_name).'_ERROR');
            }
            $conn->commit();
            $conn->close();		
            return array($column_name=>$column_value,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function createCustomers($customer){
        try{
            $conn = db_connect();
            $mobile = $customer['mobile_phone'];
            $reg_time = date("Y-m-d H:i:s",$customer['reg_time']);
            $password_hash = $customer['password'];
            $origin_userid = $customer['user_id'];
            $reg_city = $customer['reg_city'];
            $default_image = '/image/apple.jpg';
            $store_list = getDefaultStoreName();

            if($store_list['success'] == 0){
                $store_name = 'Default Store View';
            }else{
                $store_name = $store_list['data'];
            }
            $date = date('Y-m-d H:i:s');
            $sql = "INSERT INTO `customer_entity` ( `entity_type_id`, `attribute_set_id`, `website_id`, `email`, `group_id`, `increment_id`, `store_id`, `created_at`, `updated_at`, `is_active`, `disable_auto_group_change`)
                          VALUES (1, 0, 1,'$mobile@meiguoyouxian.com', 1, NULL, 1, '$reg_time', '$date', 1, 0)";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10034;
                throw new Exception('CREATE_CUSTOMER_ERROR'.$sql);
            }

            if (getEntityId($mobile)['success'] == 1){
                $entity_id = getEntityId($mobile)['data'];
            }else{
                $errorcode = getEntityId($mobile)['errorcode'];
                throw new Exception(getEntityId($mobile)['data']);
            }

            $attribute_list = array('firstname' => $mobile, 'lastname' => '','password_hash' => $password_hash, 'created_in' => $store_name,'origin_user_id'=>$origin_userid,
                                    'mobile' => $mobile, 'birthday' => '1990-01-01 00:00:00','sex' => '1','myimage'=>$default_image,'nickname' => $mobile,'reg_city' => $reg_city);

            foreach($attribute_list as $key => $value){
                $return = insertCustomerColumn($entity_id,$key,$value);
                if($return['success'] == 0){
                    $errorcode = $return['errorcode'];
                    throw new Exception($return['data']);
                }
            }
            $conn->commit();
            $conn->close();
            return array('data'=>'EC USER['.$origin_userid.'] imported success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>'EC USER['.$origin_userid.'] imported failed.Reason:'.$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function insertCustomerColumn($entity_id,$column_name,$value){
        try{
            $conn = db_connect();
            $value = addslashes($value);
            $sql = "INSERT INTO `customer_entity_varchar` ( `entity_type_id`, `attribute_id`, `entity_id`, `value`)
                          VALUES(1,(select attribute_id from eav_attribute where attribute_code = '$column_name' and entity_type_id = 1),$entity_id,'$value')";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10036;
                throw new Exception('INSERT_'.strtoupper($column_name).'_ERROR');
            }
            $conn->close();
            return array('data'=>'INSERT_'.strtoupper($column_name).'_SUCCESS',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage().$sql,"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function getDefaultStoreName(){
        try{
            $conn = db_connect();
            $sql = "select name from core_store where code = 'default'";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10037;
                throw new Exception('GET_DEFAULT_STORE_NAME_ERROR');
            }
            $row = $sqlres->fetch_assoc();
            $store_mame = $row['name'];
            $conn->close();
            return array('data'=>$store_mame,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }
?>