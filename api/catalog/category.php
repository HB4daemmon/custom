<?php
require_once(dirname(__FILE__).'/action/category_action.php');

try{
    $param = $_REQUEST;
    $result = array();
    $category = array();
    $product = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10054;
        throw new Exception('NONE_METHOD');
    }
    $method = $param['method'];

    if($method == 'create_category'){
        $filepath = dirname(__FILE__).'/../../transfer/';
        if(!isset($param['filename']) or trim($param['filename'] == '')){
            $filename = 'ecs_category.csv';
        }else{
            $filename = $param['filename'];
        }
        $file = $filepath.$filename;
        $f = fopen($file,'r');
        while($data = fgetcsv($f)){
            $category['is_show'] = $data[12];
            $category['cat_desc'] = $data[3];
            $category['cat_name'] = $data[1];
            $category['cat_id'] = $data[0];
            $category['path_column'] = $data[19];
            $category['parent_id'] = $data[4];
            $return = createCategory($category);
            dump_msg($return);
        }
        setCategoryPath();
        exit;
    }else if($method == 'test'){
        dump_msg(getCategories(4));
        exit;
    }else if($method == 'setpath'){
        $return = setCategoryPath();
        if($return['success'] == 0){
            $errorcode = $return['errorcode'];
            throw new Exception($return['data']);
        }
        $data = $return['data'];
    }else if($method == 'create_product'){
        $filepath = dirname(__FILE__).'/../../transfer/';
        if(!isset($param['filename']) or trim($param['filename'] == '')){
            $filename = 'ecs_goods.csv';
        }else{
            $filename = $param['filename'];
        }
        $file = $filepath.$filename;
        $output_file = dirname(__FILE__)."/../../../var/import/import_product".date('YmdHis').".csv";
        $f = fopen($file,'r');
        $o = fopen($output_file,'w');
        $first_flag = 'Y';
        while($data = fgetcsv($f)){
            $product['product_id'] = $data[0];
            $product['cat_id'] = $data[1];
            $product['unit'] = $data[10];
            $product['goods_sn'] = $data[12];
            $product['goods_name'] = $data[13];
            $product['price'] = $data[21];
            $product['goods_brief'] = $data[27];
            $product['goods_desc'] = $data[55];
            $product['goods_thumb'] = $data[29];
            $product['goods_img'] = $data[30];
            $product['original_img'] = $data[31];
            $product['is_on_sale'] = $data[34];
            $product['add_time'] = $data[38];
            $product['sort_order'] = $data[39];
            $return = createProductCSV($product,$first_flag,$o);
            dump_msg($return);
            $first_flag = 'N';
        }
        fclose($o);
        fclose($f);
        echo "Create product file success.";
        exit;
    }else{
        $errorcode = 10058;
        throw new Exception("INVALID_METHOD");
    }
    $result = array("data"=>$data,"success"=>1,"errorcode"=>0);
    if(isset($param['array']) and trim($param['array']) != '' ){
        dump_msg($result);
        exit;
    }
    echo json_encode($result);
}catch(Exception $e){
    $result = array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    echo json_encode($result);
}

?>