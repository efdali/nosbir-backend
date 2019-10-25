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


    $postId=$_POST["id"];


    $sil=$db->prepare("delete yorum where id=:id");
    $sil->bindParam(":id",$postId);
    
    if($sil->execute()){
        echo json_encode(array(
            "mesaj" => "Yorum başarıyla silindi",
            "durum" => 1
        ))

    }else{
        echo json_encode(array(
            "mesaj" => "Yorum silinirken bir hata oluştu",
            "durum" => 0
        ));
    }






}
?>