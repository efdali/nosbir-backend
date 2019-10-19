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
    $metin = strip_tags(trim($_POST["metin"]));
    $grupId = strip_tags(trim($_POST["grupId"]));


    if (!$baslik || !$metin || !$grupId) {
        echo json_encode(array(
            "mesaj" => "Lutfen bos kısım bırakmayın",
            "durum" => 0
        ));
    } else {
        //grup kontrol-burayı ben mi yapcam.
        $grup = $db->prepare("SELECT DISTINCT(grup_isim) FROM postlar");
        $grup->execute();

        while ($row = $grup->fetchAll(PDO::FETCH_ASSOC)) {
            if ($row["grupId"] == $grupId) {

            } else {
                $ekle = $db->prepare("INSERT INTO postlar SET
                post_baslik=:baslik,
                post_metin=:metin,
                post_grup_isim=:grup_isim,
                post_ekleyen=:uye_id");

                $ekle->bindParam(":baslik", $baslik);
                $ekle->bindParam(":metin", $metin);
                $ekle->bindParam(":grup_isim", $grupId);
                $ekle->bindParam(":uye_id", $uye_id);//session nasıl alıyoruz.

                $kontrol = $ekle->execute();
                if ($kontrol) {
                    echo json_encode(array(
                        "mesaj" => "soru basarıyla eklendi",
                        "durum" => 1,
                        //veri nasıl gondercem-bence gondermeme gerek yok, sonucta veri tabanına kaydediyorum
                    ));
                } else {
                    echo json_encode(array(
                        "mesaj" => "Sorunuzu eklerken bir hata oluştu",
                        "durum" => 0
                    ));
                }
            }
        }
    }


}


?>