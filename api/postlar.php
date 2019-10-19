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


echo json_encode(array(
    "postlar"=>$basliklar,
    "toplam"=>$toplam
));

// TODO yorum sayısı eklencek