<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type:text/html; charset=utf-8');

function db_connect() {
    $xml_array = simplexml_load_file(dirname(__FILE__).'/../../../app/etc/local.xml');
	$host = $xml_array->global->resources->default_setup->connection->host;
    $username = $xml_array->global->resources->default_setup->connection->username;
    $password = $xml_array->global->resources->default_setup->connection->password;
    $db_name = $xml_array->global->resources->default_setup->connection->dbname;

     $res = new mysqli($host, $username, $password, $db_name);
     if ($res->connect_errno) {
         throw new Exception("Failed to connect database");
     }
     return $res;
}
?>