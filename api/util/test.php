<?php
require_once("connection.php");

$fruits = array("Apple", "Banana", "Orange", "Pear", "Grape", "Lemon", "Watermelon");
$subset = array_slice($fruits,0,2);
print_r($subset);

?>