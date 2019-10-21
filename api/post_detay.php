<?php
//Anasayfada posta tıkladıgında cıkan yer

require_once("../sistem/ayar.php");

//Efdal! post baslıgı gonder
$link=$_GET["link"];


// TODO yorumları dahil et
$post=$db->prepare("select p.id,p.baslik,p.icerik,p.tarih,u.id,u.kadi,u.resim,t.baslik as topluluk,t.logo from post p,uye u,topluluk t where
                        p.toplulukId=t.id and u.durum=1 and p.durum=1 and p.seoLink=:link");

$post->bindParam(":link",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    
    echo json_encode(array(
        "post_detay" => $row
    ));

}else{
    echo json_encode(array(
        "mesaj" => "Boyle bir başlık veritabanında bulunmamaktadır.",
        "durum" => 0
    ));
}




?>