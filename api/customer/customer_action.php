<?php
    require_once('../util/connection.php');

    function getEntityId($phone){
        try{
            $conn = db_connect();
            $sql = "select cev.entity_id from customer_entity_varchar cev,
                          eav_attribute ea
                    where cev.attribute_id = ea.attribute_id
                      and ea.attribute_code = 'phone'
                      and cev.value = $phone";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                throw new Exception('QUERY_ENTITY_ERROR');
            }
            $row = $sqlres->fetch_assoc();
            $entity_id = $row['entity_id'];
            if($entity_id == ''){
                throw new Exception('NO_THIS_ACCOUNT');
            }
            $conn->close();
            return array("entity_id"=>$entity_id,"errcode"=>0,"status"=>'query entity success');
        }catch (Exception $e){
            $conn->close();
            return array("entity_id"=>-1,"errcode"=>1,"status"=>$e->getMessage());
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
                throw new Exception('QUERY_'.strtoupper($column_name).'_ERROR');
            }
            $row = $sqlres->fetch_assoc();
            $column_value = $row["value"];
            $conn->close();
            return array($column_name=>$column_value,"errcode"=>0,"status"=>'query '.$column_name.' success');
        }catch (Exception $e){
            $conn->close();
            return array($column_name=>-1,"errcode"=>1,"status"=>$e->getMessage());
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
                                              where attribute_code = '$column_name')";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                throw new Exception('UPDATE_'.strtoupper($column_name).'_ERROR');
            }
            $conn->commit();
            $conn->close();		
            return array($column_name=>$column_value,"errcode"=>0,"status"=>'update '.$column_name.' success');
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array($column_name=>-1,"errcode"=>1,"status"=>$e->getMessage());
        }
    }

?>