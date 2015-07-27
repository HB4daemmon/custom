<?php
require_once(dirname(__FILE__).'/../../util/connection.php');
require_once(dirname(__FILE__).'/../../util/hash.php');

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
            return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function getCustomerColumn($entity_id,$column_name,$entity_type_id){
        try{
            $conn = db_connect();
            $sql = "select cev.value from customer_entity_varchar cev,
                          eav_attribute ea
                    where cev.attribute_id = ea.attribute_id
                      and ea.attribute_code = '$column_name'
                      and cev.entity_id = $entity_id
                      and ea.entity_type_id = $entity_type_id";
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
                throw new Exception('CREATE_CUSTOMER_ERROR');
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
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
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

    function createCustomerAddress($address){
        try{
            $conn = db_connect();
            $user_id = $address['user_id'];
            $entity_id_return = getCustomerEntityIdFromUserId($user_id);
            $street = $address['city'].$address['district'].$address['area'].$address['address']."\n".$address['remark'];
            $firstname = $address['name'];
            $telephone = $address['tel'];
            $city = $address['city'];
            $origin_address_id = $address['id'];
            $district = $address['district'];
            $area = $address['area'];
            $remark = $address['remark'];
            $dateline= $address['dateline'];
            if($entity_id_return['success'] == 0){
                $errorcode = $entity_id_return['errorcode'];
                throw new Exception($entity_id_return['data']);
            }else{
                $entity_id = $entity_id_return['data'];
            }
            $date = date('Y-m-d H:i:s');
            $sql = "INSERT INTO `customer_address_entity` (`entity_type_id`, `attribute_set_id`, `increment_id`, `parent_id`, `created_at`, `updated_at`, `is_active`)
                    VALUES ( 2, 0, NULL, $entity_id, '$date', '$date', 1)";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10038;
                throw new Exception('CREATE_CUSTOMER_ERROR'.$user_id);
            }

            $sql = "select last_insert_id()";
            $sqlres = $conn->query($sql);
            $row = $sqlres->fetch_array();
            $address_entity_id = $row[0];

            insertAddressColumn($address_entity_id,'region_id',0,'int');
            insertAddressColumn($address_entity_id,'street',$street,'text');
            if($city == '南京市'){
                $region = '江苏省';
                $postcode = '210000';
            }elseif($city == '上海市'){
                $region = '上海市';
                $postcode = '200000';
            }else{
                $region = ' ';
                $postcode = '';
            }
            $address_list = array('firstname'=>$firstname,'lastname'=>'','city'=>$city,'region'=>$region,'postcode'=>$postcode,'country_id'=>'CN',
                'telephone'=>$telephone,'fax'=>'','origin_address_id'=>$origin_address_id,'district'=>$district,'area'=>$area,'remark'=>$remark,'dateline'=>$dateline);
            foreach($address_list as $key=>$value){
                $insert_return = insertAddressColumn($address_entity_id,"$key",$value,'varchar');
                if($insert_return['success'] == 0){
                    $errorcode = $insert_return['errorcode'];
                    throw new Exception($insert_return['data']);
                }
            }
            $conn->commit();
            $conn->close();
            return array('data'=>'EC ADDRESS['.$origin_address_id.'] imported success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>'EC ADDRESS['.$origin_address_id.'] imported failed.Reason:'.$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function getCustomerEntityIdFromUserId($user_id){
        try{
            $conn = db_connect();
            $sql = "select cev.entity_id from customer_entity_varchar cev,
                                            eav_attribute ea
                    where ea.attribute_code = 'origin_user_id'
                      and cev.value = '$user_id'
                      and ea.entity_type_id = 1";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10039;
                throw new Exception('GET_ENTITY_ID_ERROR');
            }
            $row = $sqlres->fetch_assoc();
            $entity_id = $row["entity_id"];
            $conn->close();
            return array('data'=>$entity_id,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function insertAddressColumn($address_entity_id,$column_name,$value,$type){
        try{
            $conn = db_connect();
            $value = addslashes($value);
            if($type == 'int'){
                $sql = "INSERT INTO `customer_address_entity_int` ( `entity_type_id`, `attribute_id`, `entity_id`, `value`)
                          VALUES(2,(select attribute_id from eav_attribute where attribute_code = '$column_name' and entity_type_id = 2),$address_entity_id,'$value')";
            }else if($type == 'text'){
                $sql = "INSERT INTO `customer_address_entity_text` ( `entity_type_id`, `attribute_id`, `entity_id`, `value`)
                          VALUES(2,(select attribute_id from eav_attribute where attribute_code = '$column_name' and entity_type_id = 2),$address_entity_id,'$value')";
            }else if($type == 'varchar'){
                $sql = "INSERT INTO `customer_address_entity_varchar` ( `entity_type_id`, `attribute_id`, `entity_id`, `value`)
                          VALUES(2,(select attribute_id from eav_attribute where attribute_code = '$column_name' and entity_type_id = 2),$address_entity_id,'$value')";
            }else{
                $errorcode = 10040;
                throw new Exception('INVALID_ADDRESS_TYPE');
            }

            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10041;
                throw new Exception('INSERT_ADDRESS_ERROR');
            }
            $conn->close();
            return array('data'=>'Insert Address success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage().$sql,"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function login($mobile,$password){
        try{
            $entity_id_array = getEntityId($mobile);
            if($entity_id_array['success'] == 0){
                $errorcode = $entity_id_array['errorcode'];
                throw new Exception($entity_id_array['data']);
            }
            $entity_id = $entity_id_array['data'];
            $password_true_array = getCustomerColumn($entity_id,'password_hash',1);
            if($password_true_array['success'] == 0){
                $errorcode = $password_true_array['errorcode'];
                throw new Exception($password_true_array['data']);
            }
            $password_true = $password_true_array['password_hash'];
            $success = validateHash($password,$password_true);
            if ($success == 0){
                $data = 'User or Password Error';
                $errorcode = 10042;
                throw new Exception('User or Password Error');
            }else{
                $data = 'Login Success';
                $errorcode = 0;
            }

            return array('data'=>$data,"success"=>$success,"errorcode"=>$errorcode);
        }catch (Exception $e){
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function setDefaultAddress(){
        try{
            $conn = db_connect();
            $sql="select max(entity_id) as address_entity_id,parent_id,count(parent_id) as entity_count from customer_address_entity group by parent_id";
            $sqlres = $conn->query($sql);
            while($row = $sqlres->fetch_assoc()){
                $count = $row['entity_count'];
                $parent_id = $row['entity_count'];
                if($count == 1){
                    $address_entity_id = $row['address_entity_id'];
                    $return = createOrUpdateDefaultAddress($parent_id,$address_entity_id);
                    if($return['success'] == 0){
                        $errorcode = $return['errorcode'];
                        throw new Exception($return['data']);
                    }
                }else{
                    
                }
            }
            $conn->close();
            return array('data'=>'Insert Address success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function createOrUpdateDefaultAddress($entity_id,$address_entity_id){
        try{
            $conn = db_connect();
            $sql="select cei.* from customer_entity_int cei, eav_attribute ea
                             where cei.entity_id = $entity_id
                               and cei.attribute_id = ea.attribute_id
                               and ea.attribute_code = 'default_shipping'";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10045;
                throw new Exception("Create or update default address failed.");
            }
            $count = $sqlres->num_rows;
            if($count == 0){
                $sql = "INSERT INTO `customer_entity_int`(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
                            VALUES(1,(select attribute_id from eav_attribute where attribute_code = 'default_shipping'),$entity_id,$address_entity_id)";
            }else{
                $sql = "UPDATE `customer_entity_int` SET `value` = $address_entity_id
                         where entity_id = $entity_id";
            }
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10046;
                throw new Exception("Create or update default address failed.");
            }
            $conn->commit();
            $conn->close();
            return array('data'=>'Insert Address success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }
?>