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
    $baslik = strip_tags(trim($_POST["baslik"]));
    $icerik = strip_tags(trim($_POST["icerik"]));
    $toplulukId = strip_tags(trim($_POST["toplulukId"]));


    if (!$baslik || !$icerik || !$toplulukId) {
        echo json_encode(array(
            "mesaj" => "Lutfen bos kısım bırakmayın",
            "durum" => 0
        ));
    } else {
        
        
                $ekle = $db->prepare("INSERT INTO posts SET
                title=:title,
                content=:content,
                groups_id=:groups_id,
                user_id=:user_id");

                $ekle->bindParam(":title", $baslik);
                $ekle->bindParam(":content", $icerik);
                $ekle->bindParam(":groups_id", $toplulukId);
                $ekle->bindParam(":user_id", $id);

                $kontrol = $ekle->execute();
                if ($kontrol) {
                    echo json_encode(array(
                        "mesaj" => "soru basarıyla eklendi",
                        "durum" => 1,
                        
                    ));
                } else {
                    echo json_encode(array(
                        "mesaj" => "Sorunuzu eklerken bir hata oluştu",
                        "durum" => 0
                    ));
                }
            
        }
    }





?>