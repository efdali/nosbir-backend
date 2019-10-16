<?php

require_once("sistem/ayar.php");

header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");

//require_once("api/index.php");

$mail=$_POST["mail"];
$pass=$_POST["pass"];
$hashPass=password_hash($pass,PASSWORD_BCRYPT);

$stmt=$db->prepare("insert into user 
                                set user_mail = :mail,
                                    user_pass = :pass");
$stmt->bindParam(":mail",$mail);
$stmt->bindParam(":pass",$hashPass);

if($stmt->execute()){
    http_response_code(200);
    echo json_encode(array(
        "status"=>"1",
        "message"=>"Kayıt Başarılı"
    ));
}else{
    http_response_code(400);
    echo json_encode(array(
        "status"=>"0",
        "message"=>"bir hatayla karşılaşıldı."
    ));
}

?>