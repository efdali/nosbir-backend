<?php
//require_once("../yardımcılar/token-dogrula.php");
require_once("../sistem/ayar.php");

//grup isimlerini gondereyim mi?
$grup=$db->prepare("SELECT DISTINCT(grup_isim) FROM postlar");
$grup->execute();
$grup->fetchAll(PDO::FETCH_ASSOC);





$sayfa= @$_GET["s"] ? @$_GET["s"] : 1 ;

$sayi=$db->prepare("SELECT post_id FROM postlar");

$sayi->execute();
$sayi->fetchAll(PDO::FETCH_ASSOC);
$say=$sayi->rowCount();
$limit=5;
$sayfa_sayisi=ceil($say/$limit);
$baslangic=$sayfa*$limit-$limit;

//bunları sana gonderiyim mi?
@$onceki=$onceki>1?$onceki-1:1;
@$sonraki=$sonraki<$sayfa_sayisi?$sonraki+1:$sayfa_sayisi;



$listele=$db->prepare("SELECT * FROM postlar
INNER JOIN uyeler on uyeler.uye_id=postlar.post_ekleyen
ORDER BY post_id DESC
LIMIT $baslangic,$limit");

$listele->execute();
$listele->fetchAll(PDO::FETCH_ASSOC);
$kontrol=$listele->rowCount();

if($kontrol==0){
    
    foreach($listele as $row){
        $yorum=$db->prepare("SELECT * FROM yorumlar WHERE yorum_post_id=:yorum_post_id");
        $yorum->bindParam(":yorum_post_id",$row["post_id"]);
        $yorum->execute();
        $yorumrow=$yorum->fetch(PDO::FETCH_ASSOC);
        $yorumsay=$yorumrow->rowCount();        
    }
    
    
    echo json_encode(array(
        "mesaj" => "Sayfa basarıyla listelendi",
        "durum" => 1,
        
        "data" =>array(
            "sayfa_sayisi" => $sayfa_sayisi,
            "onceki" => $onceki,
            "sonraki" => $sonraki,
            "sayfa" => $sayfa,
            "yorumsay"=> $yorumsay
            //veri nasıl gondercem-boylemi
            /*
            "data" => array(
                "soru_baslik" => $row["post_baslik"],
                "soru_icerik " => $row["post_icerik"]               
            )
            */
        )
        
    ));


}else{
    echo json_encode(array(
        "mesaj" => "Sayfalar listelenirken bir hata olustu.",
        "durum" => 0
    ));
}



?>