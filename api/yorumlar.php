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

    
    $icerik=strip_tags(trim($_POST["icerik"]));
    $postId=strip_tags(trim($_POST["postId"]));


    if(!$icerik){
        echo json_encode(array(
            "mesaj" => "İcerik kısmı boş bırakılamaz",
            "durum" => 0
        ));
    }else{
        //Efdal! bu kodla adam yorum attıktan 30 saniye sonra yorum atabilecek
        //kalsın mı? boylece bir fonksiyon yazıp saniye başı mesaj atamıyacak
        $yorumAyar=$db->prepare("select * from yorum y where
                                    y.tarih>now() - interval 30 second 
                                    and uyeId=:uyeId");
                                    
        $yorumAyar->bindParam(":uyeId",$id);
        $yorumAyar->execute();

        if($row=$yorumAyar->fetch(PDO::FETCH_ASSOC)){
            echo json_encode(array(
                "mesaj" => "30 saniye içinde birden fazla yorum yazamassınız.",
                "durum" => 0
            ));
        }else{
            $yorumEkle=$db->prepare("insert into yorum set 
            uyeId=:uyeId,
            icerik=:icerik,
            postId=:postId");
            
            $yorumEkle->bindParam(":uyeId",$id);
            $yorumEkle->bindParam(":icerik",$icerik);
            $yorumEkle->bindParam(":postId",$postId);

            if($yorumEkle->execute()){
                echo json_encode(array(
                    "mesaj" => "Yorum başarıyla kaydedildi",
                    "durum" => 1
                ));
            }else{
                echo json_encode(array(
                    "mesaj" => "Yorum kaydedilirken bir hata oluştu.",
                    "durum" => 0
                ));
            }

            

        }
        
    }





}
?>

