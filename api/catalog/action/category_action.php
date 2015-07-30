<?php
require_once(dirname(__FILE__).'/../../util/connection.php');

    function createCategory($category){
        try{
            $conn = db_connect();
            $date = date('Y-m-d H:i:s');
            $sql = "INSERT INTO `catalog_category_entity` ( `entity_type_id`, `attribute_set_id`, `parent_id`, `created_at`, `updated_at`, `path`, `position`, `level`, `children_count`)
                          VALUES (3, 3, '', '$date', '$date','' ,'' ,'' , 0)";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10048;
                throw new Exception('');
            }

            $conn->close();
            return array('data'=>'',"success"=>1,"errorcode"=>0);

        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function insertCategoryColumns($category_entity_id,$column_name,$value,$type){
        try{
            $conn = db_connect();
            $value = addslashes($value);
            if($type == 'int' or $type == 'datetime' or $type == 'decimal' or $type == 'text' or $type == 'varchar'){
                $sql = "INSERT INTO `customer_address_entity_$type` ( `entity_type_id`, `attribute_id`, `entity_id`, `value`)
                          VALUES(3,(select attribute_id from eav_attribute where attribute_code = '$column_name' and entity_type_id = 3),$category_entity_id,'$value')";
            }else{
                $errorcode = 10049;
                throw new Exception('INVALID_CATEGORY_TYPE');
            }

            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10050;
                throw new Exception('INSERT_CATEGORY_ERROR');
            }
            $conn->close();
            return array('data'=>'Insert Address success'.$sql,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage().$sql,"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function setCategoryPath(){

    }
?>