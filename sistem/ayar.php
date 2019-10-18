<?php

$host="localhost";
$db="nosbir";
$username="root";
$pass="";

try{
    $db=new PDO("mysql:host=$host;dbname=$db;charset=utf8",$username,$pass);
}catch(PDOException $e){
    http_response_code(500);
    echo json_encode(array(
        "status"=>0,
        "error" => $e->getMessage()
    ));

    die();

}

?>