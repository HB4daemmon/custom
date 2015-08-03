<?php
require_once(dirname(__FILE__).'/action/customer_action.php');

try{
    $param = $_REQUEST;
    $result = array();
    $address = array();

    if(!isset($param['method']) or trim($param['method']) == ''){
        $errorcode = 10044;
        throw new Exception('NONE_METHOD');
    }
    $method = $param['method'];

    if($method == 'create'){
        $filepath = dirname(__FILE__).'/../../transfer/';
        if(!isset($param['filename']) or trim($param['filename'] == '')){
            $filename = 'ecs_user_address_list.csv';
        }else{
            $filename = $param['filename'];
        }
        $file = $filepath.$filename;
        $f = fopen($file,'r');
        while($data = fgetcsv($f)){
            $address['user_id'] = $data[1];
            $address['address'] = $data[7];
            $address['name'] = $data[2];
            $address['tel'] = $data[3];
            $address['remark'] = $data[8];
            $address['dateline'] = $data[9];
            $address['city'] = $data[4];
            $address['id'] = $data[0];
            $address['district'] = $data[5];
            $address['area'] = $data[6];
            $return = createCustomerAddress($address);
            dump_msg($return);
        }
        exit;
    }else if($method == 'set_default_address'){
        $return = setDefaultAddress();
        dump_msg($return);
        /*if($return['success'] == 0){
            $errorcode = $return['errorcode'];
            throw new Exception($return['data']);
        }*/
    }else{
        $errorcode = 10057;
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