<?php
require_once("../sistem/ayar.php");
require_once("../yardımcılar/token-dogrula.php");
if ($_POST) {

    $token = json_decode(TokenDogrula::dogrula()); // token doğrula fonksiyonundan dönen jsonı decode ediyoruz
    if ($token->{"durum"} == 0) { // fonsiyondan durum 0 dönmüşse token doğrulanamadı hatası verip işlemi sonlandırıyoruz
        echo json_encode(array(
            "durum" => 0,
            "mesaj" => "Token doğrulanamadı."
        ));
        die(); // işlem sonlandırma bundan sonrası çalışmayacak
    }

    $id=$token->{"token"}->{"data"}->{"id"}; // eğer token normal geldiyse token içinden üye id yi alıyoruz
    $postId=$_GET["postId"];
    $tur=$_GET["tur"];



    $begenme=$db->prepare("select * from begeni b
                        where b.uyeId=:uyeId and b.postId=:postId and b.tur=:tur");
    $begenme->bindParam("uyeId:",$id);
    $begenme->bindParam("postId:",$postId);
    $begenme->bindParam("tur:",$tur);
    $begenme->execute();
    if($begenme->fetch(PDO::FETCH_ASSOC)){

        echo json_encode(array(
            "mesaj" => "Bu soruyu daha önce beğendin",
            "durum" => 0
          ));
        
    }else{
        $kaydet=$db->prepare("insert into begeni on
        uyeId=:uyeId,
        postId=:postId,
        tur=:tur
        ");
        $kaydet->bindParam("uyeId:",$id);
        $kaydet->bindParam("postId:",$postId);
        $kaydet->bindParam("tur:",$tur);
        $kaydet->execute();
        if($row=$kaydet->fetch(PDO::FETCH_ASSOC)){
            echo json_encode(array(
                "mesaj" => "Begeni başarıyla kaydedildi",
                "durum" => 1
            ));
        }else{
            echo json_encode(array(
                "mesaj" => "Kaydedilirken bir hata oluştu",
                "durum" => 0
            ));
            
        }
    }
}
        
    


 





?>