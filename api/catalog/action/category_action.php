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
                throw new Exception('CREATE_CATEGORY_ERROR');
            }

            $sql = "select last_insert_id()";
            $sqlres = $conn->query($sql);
            $row = $sqlres->fetch_array();
            $category_entity_id = $row[0];

            $is_active = $category['is_show'];
            $include_in_menu = $category['is_show'];
            $description = $category['cat_desc'];
            $name = $category['cat_name'];
            $cat_id = $category['cat_id'];
            $origin_path = $category['path_column'];
            $origin_parent_id = $category['parent_id'];
            $category_int_array = array("is_active"=>$is_active,"include_in_menu"=>$include_in_menu,"landing_page"=>NULL,"is_anchor"=>0,"custom_use_parent_settings"=>0,"custom_apply_to_products"=>0);
            $category_datetime_array = array("custom_design_from"=>NULL,"custom_design_to"=>NULL);
            $category_decimal_array = array("filter_price_range"=>NULL);
            $category_text_array = array("description"=>$description,"meta_keywords"=>NULL,"meta_description"=>NULL,"custom_layout_update"=>NULL,"available_sort_by"=>NULL);
            $category_varchar_array = array("name"=>$name,"url_key"=>$cat_id,"meta_title"=>NULL,"display_mode"=>'PRODUCTS',"custom_design"=>NULL,"page_layout"=>NULL,"origin_cat_id"=>$cat_id,"origin_path"=>$origin_path,"origin_parent_id"=>$origin_parent_id);

            foreach($category_int_array as $key=>$value){
                $return = insertCategoryColumns($category_entity_id,$key,$value,'int');
                if($return['success'] == 0){
                    $errorcode = $return['errorcode'];
                    throw new Exception($return['data']);
                }
            }
            foreach($category_datetime_array as $key=>$value){
                $return = insertCategoryColumns($category_entity_id,$key,$value,'datetime');
                if($return['success'] == 0){
                    $errorcode = $return['errorcode'];
                    throw new Exception($return['data']);
                }
            }
            foreach($category_decimal_array as $key=>$value){
                $return = insertCategoryColumns($category_entity_id,$key,$value,'decimal');
                if($return['success'] == 0){
                    $errorcode = $return['errorcode'];
                    throw new Exception($return['data']);
                }
            }
            foreach($category_text_array as $key=>$value){
                $return = insertCategoryColumns($category_entity_id,$key,$value,'text');
                if($return['success'] == 0){
                    $errorcode = $return['errorcode'];
                    throw new Exception($return['data']);
                }
            }
            foreach($category_varchar_array as $key=>$value){
                $return = insertCategoryColumns($category_entity_id,$key,$value,'varchar');
                if($return['success'] == 0){
                    $errorcode = $return['errorcode'];
                    throw new Exception($return['data']);
                }
            }
            $conn->close();
            return array('data'=>'EC CATEGORY['.$name.'] imported success',"success"=>1,"errorcode"=>0);

        }catch (Exception $e){
            $conn->close();
            return array('data'=>'EC CATEGORY['.$name.'] imported success'.$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function insertCategoryColumns($category_entity_id,$column_name,$value,$type){
        try{
            $conn = db_connect();
            $value = addslashes($value);
            if($type == 'int' or $type == 'datetime' or $type == 'decimal' or $type == 'text' or $type == 'varchar'){
                $sql = "INSERT INTO `catalog_category_entity_$type` ( `entity_type_id`, `attribute_id`, `store_id`,`entity_id`, `value`)
                          VALUES(3,(select attribute_id from eav_attribute where attribute_code = '$column_name' and entity_type_id = 3),0,$category_entity_id,'$value')";
            }else{
                $errorcode = 10049;
                throw new Exception('INVALID_CATEGORY_TYPE');
            }

            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10050;
                throw new Exception('INSERT_CATEGORY_ERROR');
            }
            $conn->commit();
            $conn->close();
            return array('data'=>'Insert Address success'.$sql,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage().$sql,"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function setCategoryPath(){
        try{
            $conn = db_connect();
            $sql = "select cce.entity_id,ccev.value from catalog_category_entity cce,
                                                          catalog_category_entity_varchar ccev,
                                                          eav_attribute ea
                                            where cce.entity_id = ccev.entity_id
                                            and ea.attribute_id = ccev.attribute_id
                                            and ea.attribute_code = 'origin_path'
                                            and cce.path =''";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10051;
                throw new Exception('GET_ORIGIN_PATH_ERROR');
            }
            $count = $sqlres->num_rows;
            if($count !=  0){
                while($row = $sqlres->fetch_assoc()){
                    $category_id = $row['entity_id'];
                    $path = $row['value'];
                    if($path != '' and count(explode(':',$path)) == 3){
                        $path_array = explode('/',explode(':',$path)[0]);
                        $new_path_array = array(1);
                        foreach ($path_array as $p){
                            $return = getCurrentCategoryId($p);
                            if($return['success'] == 0){
                                $errorcode = $return['errorcode'];
                                throw new Exception($return['data']);
                            }
                            $new_p = $return['data'];
                            array_push($new_path_array,$new_p);
                        }
                        $new_path = implode("/",$new_path_array);
                        $new_level = explode(':',$path)[1];
                        $new_position = explode(':',$path)[2];

                        $sql1 = "select ccev.value from catalog_category_entity_varchar ccev,
                                                         eav_attribute ea
                                                where ccev.attribute_id = ea.attribute_id
                                                 and ea.attribute_code = 'origin_parent_id'
                                                 and ccev.entity_id = '$category_id'";
                        $sqlres1 = $conn->query($sql1);
                        if(!$sqlres1){
                            $errorcode = 10055;
                            throw new Exception("GET_ORIGIN_PARENT_ID_ERROR");
                        }
                        $count = $sqlres1->num_rows;
                        if($count == 0){
                            $parent_id = 2;
                        }else{
                            $row = $sqlres1->fetch_assoc();
                            $origin_parent_id = $row['value'];
                            $return = getCurrentCategoryId($origin_parent_id);
                            if($return['success'] == 0){
                                $errorcode = $return['errorcode'];
                                throw new Exception($return['data']);
                            }
                            $parent_id =$return['data'];
                        }

                        $sql2="update catalog_category_entity set parent_id = $parent_id,path = '$new_path',level = $new_level,position = $new_position where entity_id = $category_id";
                        $sqlres2 = $conn->query($sql2);
                        if(!$sqlres2){
                            $errorcode = 10056;
                            throw new Exception("UPDATE_CATEGORY_PATH_ERROR");
                        }
                    }
                }
            }
            $conn->commit();
            $conn->close();
            return array('data'=>'Set category path success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function getCurrentCategoryId($origin_cat_id){
        try{
            $conn = db_connect();
            $sql = "select ccev.entity_id from catalog_category_entity_varchar ccev,
                                          eav_attribute ea
                             where ea.attribute_code = 'origin_cat_id'
                             and ea.attribute_id = ccev.attribute_id
                             and ccev.value = $origin_cat_id";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10053;
                throw new Exception('GET_CURRENT_CATEGORY_ID_ERROR');
            }
            $count=$sqlres->num_rows;
            if($count == 0){
                $category_entity_id = 2;
            }else{
                $row = $sqlres->fetch_assoc();
                $category_entity_id = $row['entity_id'];
            }
            $conn->close();
            return array('data'=>$category_entity_id,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->rollback();
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function createProductCSV($product,$first_flag,$output_file){
        try{
            $product_list = array('website','store','type','sku','name','image','small_image','thumbnail','image_label','small_image_label',
                                'thumbnail_label','media_gallery','weight','price','description','category_ids','short_description','status','tax_class_id',
                                'visibility','manage_stock','qty','use_config_manage_stock','origin_product_id','sort_order','unit');
            if($first_flag == 'Y'){
                $output = array();
                foreach($product_list as $p){
                    array_push($output,$p);
                }
            }else{
                $output = array();
                array_push($output,'base');
                array_push($output,'default');
                array_push($output,'simple');
                array_push($output,$product['goods_sn']);
                array_push($output,$product['goods_name']);
                array_push($output,str_replace('images/','/images/',$product['original_img']));
                array_push($output,str_replace('images/','/images/',$product['goods_img']));
                array_push($output,str_replace('images/','/images/',$product['goods_thumb']));
                array_push($output,$product['goods_name']);
                array_push($output,$product['goods_name']);
                array_push($output,$product['goods_name']);
                array_push($output,str_replace('images/','/images/',$product['goods_img']));
                array_push($output,'1');
                array_push($output,$product['price']);
                array_push($output,str_replace('/shop/images','/magento/media/shop/images',str_replace('"','""',$product['goods_desc'])));
                $categories = getCategories($product['cat_id'])['data'];
                array_push($output,$categories); //category
                array_push($output,$product['goods_brief']);
                $status = ($product['is_on_sale'] == '1')?'Enabled':'Disabled';
                array_push($output,$status);
                array_push($output,'None');
                array_push($output,'Catalog, Search');
                array_push($output,'1');
                array_push($output,'99999999');
                array_push($output,'0');
                array_push($output,$product['product_id']);
                array_push($output,$product['sort_order']);
                array_push($output,$product['unit']);
            }


            if(!$output_file){
                $errorcode = 10060;
                throw new Exception("FILE_OPEN_ERROR");
            }

            foreach($output as $o){
                fwrite($output_file,"\"".$o."\",");
            }
            fwrite($output_file,"\r\n");

            return array('data'=>'ITEM ['.$product['goods_sn'].':'.$product['goods_name'].'] created success',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            return array('data'=>'ITEM ['.$product['goods_sn'].':'.$product['goods_name'].'] created failed, reason: '.$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    function getCategories($origin_cat_id){
        try{
            $conn = db_connect();
            $current_entity_id = getCurrentCategoryId($origin_cat_id)['data'];
            $sql = "select path from catalog_category_entity where entity_id = $current_entity_id";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10059;
                throw new Exception("GET_CATEGORIES_ERROR");
            }
            $row = $sqlres->fetch_assoc();
            $path = str_replace('/',',',$row['path']);
            $conn->close();
            return array('data'=>$path,"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

?>