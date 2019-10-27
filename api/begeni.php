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



    $begenme=$db->prepare("select * from likes l
                        where l.user_id=:user_id and l.post_id=:post_id and l.type=:type");
    $begenme->bindParam("user_id:",$id);
    $begenme->bindParam("postId:",$postId);
    $begenme->bindParam("type:",$tur);
    $begenme->execute();
    if($begenme->fetch(PDO::FETCH_ASSOC)){

        echo json_encode(array(
            "mesaj" => "Bu postu daha önce beğendin",
            "durum" => 0
          ));
        
    }else{
        $kaydet=$db->prepare("insert into likes on
        user_id=:user_id,
        post_id=:post_id,
        type=:type
        ");
        $kaydet->bindParam("user_id:",$id);
        $kaydet->bindParam("post_id:",$postId);
        $kaydet->bindParam("type:",$tur);
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