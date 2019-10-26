<?php

require_once("../sistem/ayar.php");
require_once("../yardımcılar/token-dogrula.php");
if ($_POST) {

    $token = json_decode(TokenDogrula::dogrula()); 
    if ($token->{"durum"} == 0) { 
        echo json_encode(array(
            "durum" => 0,
            "mesaj" => "Token doğrulanamadı."
        ));
        die(); 
    }

    $id=$token->{"token"}->{"data"}->{"id"}; 


    $sil=$db->prepare("delete * from uye where id=:id limit 1");
    $sil->bindParam(":sil",$sil);
    if($sil->execute()){
        echo json_encode(array(
            "mesaj" => "Hesap başarıyla silindi",
            "durum" => 1
        ));
    }else{
        echo json_encode(array(
            "mesaj" => "Hesap silinirken bir hata oluştu",
            "durum" => 0
        ));
    }












}
?>