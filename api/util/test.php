<?php
require_once("connection.php");

$array = array("1"=>1,"a"=>2,"3"=>3,"4"=>4,"5"=>5);

foreach($array as $v=>$g){
    if(in_array(2,$array)){
        echo $g;
    }
}

//print_r($list);
?>