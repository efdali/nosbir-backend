<?php

require_once("sistem/ayar.php");
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");

$secret_key="nosbirciler";
$header=apache_request_headers();
$token=$header["Authorization"];

if($token){
//    try{
        $decoded=JWT::decode($token,$secret_key,array("HS256"));
        http_response_code(200);
        echo json_encode(array(
            "status"=>1,
            "message"=>"Yetkilendirme Başarılı",
            "decoded"=>$decoded
        ));
//    }catch (Exception $e){
//        http_response_code(401);
//        echo json_encode(array(
//            "status"=>0,
//            "message"=>$e->getMessage()
//        ));
//    }
}else{
    http_response_code(401);
    echo json_encode(array(
        "status"=>0,
        "message"=>"Token Bulunamadı."

    ));
}
