<?php
require_once(dirname(__FILE__).'/action/orders.php');

try{
    $param = $_REQUEST;
    $result = array();
    $product = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10061;
        throw new Exception('NONE_METHOD');
    }
    $method = $param['method'];

    if($method == 'create_category'){
        $filepath = dirname(__FILE__).'/../../transfer/';
        if(!isset($param['filename']) or trim($param['filename'] == '')){
            $filename = 'ecs_orders.csv';
        }else{
            $filename = $param['filename'];
        }
        $file = $filepath.$filename;
        $f = fopen($file,'r');
        $last_order_id = '';
        while($data = fgetcsv($f)){
            $order_id = $data[0];
            if($order_id == $last_order_id and $last_order_id != ''){
                array_push($product,array_slice($data,-16));
            }else if($order_id != $last_order_id){
                custom_order::import_orders($order,$product);
                $product = array();
                $order = array_slice($data,0,76);
                array_push($product,array_slice($data,-16));
            }
            $return = createCategory($category);
         }
        custom_order::import_orders($order,$product);
        exit;
    }else if($method == 'test'){
        $order = new custom_order();
        $order->createOrder(1,'');
        exit;
    }else{
        $errorcode = 10062;
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