<?php
require_once(dirname(__FILE__).'/action/category_action.php');

try{
    $param = $_REQUEST;
    $result = array();
    $category = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10054;
        throw new Exception('NONE_METHOD');
    }
    $method = $param['method'];

    if($method == 'create'){
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
            dump($return);
        }
        setCategoryPath();
        exit;
    }else if($method == 'test'){
        setCategoryPath();
    }else if($method == 'setpath'){
        $return = setCategoryPath();
        if($return['success'] == 0){
            $errorcode = $return['errorcode'];
            throw new Exception($return['data']);
        }
        $data = $return['data'];
    }else{
        $errorcode = 10058;
        throw new Exception("INVALID_METHOD");
    }
    $result = array("data"=>$data,"success"=>1,"errorcode"=>0);
    if(isset($param['array']) and trim($param['array']) != '' ){
        dump($result);
        exit;
    }
    echo json_encode($result);
}catch(Exception $e){
    $result = array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    echo json_encode($result);
}

?>