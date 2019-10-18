<?php
require_once("../sistem/ayar.php");

if($_POST){

    $baslik=strip_tags(trim($_POST["baslik"]));
    $metin=strip_tags(trim($_POST["metin"]));
    $grup_isim=strip_tags(trim($_POST["grup_isim"]));



    if(!$baslik || !$metin || !$grup){
        echo json_encode(array(
            "mesaj" => "Lutfen bos kısım bırakmayın",
            "durum" => 0
        ));
    }else{
        //grup kontrol-burayı ben mi yapcam.
        $grup=$db->prepare("SELECT DISTINCT(grup_isim) FROM postlar");
        $grup->execute();
        
        while($row=$grup->fetchAll(PDO::FETCH_ASSOC)){
            if($row["grup_isim"]==$grup_isim){

            }else{
                $ekle=$db->prepare("INSERT INTO postlar SET
                post_baslik=:baslik,
                post_metin=:metin,
                post_grup_isim=:grup_isim,
                post_ekleyen=:uye_id");

                $ekle->bindParam(":baslik",$baslik);
                $ekle->bindParam(":metin",$metin);
                $ekle->bindParam(":grup_isim",$grup_isim);
                $ekle->bindParam(":uye_id",$uye_id);//session nasıl alıyoruz.

                $kontrol=$ekle->execute();
                if($kontrol){
                    echo json_encode(array(
                        "mesaj"=>"soru basarıyla eklendi",
                        "durum" => 1,
                        //veri nasıl gondercem-bence gondermeme gerek yok, sonucta veri tabanına kaydediyorum
                    ));
                }else{
                    echo json_encode(array(
                        "mesaj"=>"Sorunuzu eklerken bir hata oluştu",
                        "durum" => 0
                    ));
                }
            }
        }
    }





}







?>