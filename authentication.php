<?php

require_once("sistem/ayar.php");
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");

$mail=$_POST["mail"];
$pass=$_POST["pass"];

$stmt=$db->prepare("select * from user where user_mail= :mail limit 0,1");
$stmt->bindParam(":mail",$mail);
$stmt->execute();
$num=$stmt->rowCount();
if($num>0){

    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $id=$row["user_id"];
    $hashPass=$row["user_pass"];

    if(password_verify($pass,$hashPass)){


        $secret_key="nosbirciler";
        $issuer_claim = "nosbir.com"; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 60; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $id,
                "mail" => $mail,
            ));

        http_response_code(200);
        $jwt=JWT::encode($token,$secret_key);
        echo json_encode(array(
            "status" => 1,
            "token"=>$jwt,
            "expire_at"=>$expire_claim,
            "message"=>"Yetkilendirme Başarılı."
        ));

    }else{

        http_response_code(401);
        echo json_encode(array(
            "status"=>0,
            "message"=>"Yetkilendirme Başarısız.Şifre Hatalı."
        ));

    }

}else{
    http_response_code(401);
    echo json_encode(array(
        "status"=>0,
        "message"=>"Yetkilendirme Başarısız.Böyle bir email adresi bulunamadı."
    ));
}

