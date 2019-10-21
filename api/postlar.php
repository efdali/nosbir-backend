<?php

require_once("../sistem/ayar.php");

$sayfa=@$_GET["s"] ? @$_GET["s"] : 1;
$limit=10;

$offset=($sayfa-1)*$limit;
$sorgu=$db->prepare("select p.id,p.baslik,p.icerik,p.tarih,t.id,t.baslik,t.logo,u.id,u.kadi,u.resim
                                    from post p, topluluk t, uye u
                                    where p.toplulukId=k.id and p.uyeId=u.id and u.durum!=2  order by p.tarih desc 
                                    limit $offset,$limit ");
$sorgu->execute();
$basliklar=$sorgu->fetchAll(PDO::FETCH_ASSOC);
$toplam=$sorgu->rowCount();


// TODO yorum sayısı eklencek
if($toplam){
    foreach($toplam as $row){
        $yorum=$db->prepare("select * from uye u,post p,yorum y on
                            y.uyeId=u.id and y.postId=p.id and p.uyeId=u.id and p.toplulukId=t.id
                            where y.postId=:p.id ");
    
    $yorum->bindParam("p.id:",$row["p.id"]);
    $yorum->execute();
    $yorums=$yorum->fetchAll(PDO::FETCH_ASSOC);
    $yorumSayisi=$yorum->rowCount();

    }
    
}


echo json_encode(array(
    "postlar"=>$basliklar,
    "toplam"=>$toplam,
    "yorumSayi"=>$yorumSayi
));

