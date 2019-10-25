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

    $baslik = strip_tags(trim($_POST["baslik"]));
    $icerik = strip_tags(trim($_POST["icerik"]));
    $postId = strip_tags(trim($_POST["id"]));

    if(!$baslik || !$icerik){
        echo json_encode(array(
            "mesaj" => "lutfen boş kısım bırakmayın ",
            "durum" => 0
        ));
    }else{
        $guncelle=$db->prepare("update post set
                                baslik=:baslik,
                                icerik=:icerik,
                                id=:id,
                                uyeId=:uyeId");
        $guncelle->bindParam(":baslik",$baslik);
        $guncelle->bindParam(":icerik",$icerik);
        $guncelle->bindParam(":id",$postId);
        $guncelle->bindParam("uyeId",$id);

        if($guncelle->execute()){
            echo json_encode(array(
                "mesaj" => "Post başarıyla güncellendi",
                "durum" => 1
            ));
        }else{
            echo json_encode(array(
                "mesaj" => "Post guncellenirken bir sorun oluştu",
                "durum" => 0
            ));
        }

    }






}

?>