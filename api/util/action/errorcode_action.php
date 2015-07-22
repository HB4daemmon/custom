<?php
require_once(dirname(__FILE__).'/../../util/connection.php');

function createErrorCode($errcode_number,$code,$description=''){
    try{
        $conn = db_connect();
        $errcode_number = addslashes($errcode_number);
        $code = addslashes($code);
        if($description == ''){
            $description = $code;
        }else{
            $description = addslashes($description);
        }

        $sql = "insert into custom_errorcode(errcode_number,code,description) values ($errcode_number,'$code','$description')";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 1000;
            throw new Exception('CREATE_ERRORCODE_ERROR');
        }

        $conn->close();
        return array("data"=>'Create errorcode success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcodes);
    }
}

function updateErrorCode($errcode_number,$code,$description){
    try{
        $conn = db_connect();
        $errcode_number = addslashes($errcode_number);
        $code = addslashes($code);
        $description = addslashes($description);
        if($description == ''){
            $sql = "update custom_errorcode set code = '$code'
                                       where errcode_number = $errcode_number";
        }else if($code == ''){
            $sql = "update custom_errorcode set description ='$description'
                                       where errcode_number = $errcode_number";
        }else if($code != '' and $description != ''){
            $sql = "update custom_errorcode set code = '$code',description ='$description'
                                       where errcode_number = $errcode_number";
        }else{
            $errorcode = 1002;
            throw new Exception('UPDATE_ERRORCODE_ERROR');
        }

        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode = 1003;
            throw new Exception('UPDATE_ERRORCODE_ERROR');
        }

        $conn->close();
        return array("data"=>'Create errorcode success',"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
    }
}

function getErrorCode($errorcode_number){
    try{
        $conn = db_connect();
        $errorcode_number = addslashes($errorcode_number);

        $sql = "select * from custom_errorcode
                         where errcode_number = $errorcode_number";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode_number = 1004;
            throw new Exception('GET_ERRORCODE_ERROR');
        }

        $row = $sqlres ->fetch_assoc();
        $errorcode_array=['errorcode_id','errorcode_number','code','description'];
        foreach($errorcode_array as $e){
            $errorcode[$e] = $row[$e];
        }

        $conn->close();
        return array("data"=>$errorcode,"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode_number);
    }
}

function getMaxErrorCode(){
    try{
        $conn = db_connect();
        $sql = "select * from custom_errorcode
                         order by errorcode_id desc limit 1";
        $sqlres = $conn->query($sql);
        if(!$sqlres){
            $errorcode_number = 1005;
            throw new Exception('GET_MAX_ERRORCODE_ERROR');
        }

        $row = $sqlres ->fetch_assoc();
        $errorcode_array=['errorcode_id','errorcode_number','code','description'];
        foreach($errorcode_array as $e){
            $errorcode[$e] = $row[$e];
        }

        $conn->close();
        return array("data"=>$errorcode,"success"=>1,"errorcode"=>0);
    }catch (Exception $e){
        $conn->close();
        return array("data"=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode_number);
    }
}
?>