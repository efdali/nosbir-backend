<?php
//TODO Begeni
// TODO SQL hatalı
require_once("../sistem/ayar.php");

$sayfa=@$_GET["s"] ? @$_GET["s"] : 1;
$limit=10;

$offset=($sayfa-1)*$limit;
$sorgu=$db->prepare("select p.*,t.id,t.baslik as topluluk,t.logo,u.kadi,u.resim,COUNT(y.id) as yorum
                from post p left JOIN 
                (select * from yorum where yorum.durum!=0) y on p.id=y.postId 
                INNER JOIN topluluk t on p.toplulukId=t.id
                INNER JOIN uye u on p.uyeId=u.id where u.durum =1 and p.durum!=0
                GROUP BY y.postId order by p.tarih DESC 
                limit $offset,$limit");

$sorgu->execute();
$basliklar=$sorgu->fetchAll(PDO::FETCH_ASSOC);

$toplamSayfa=$db->prepare("select COUNT(*) from post");
$toplamSayfa->execute();
$toplam=$toplamSayfa->fetch(PDO::FETCH_ASSOC);

echo json_encode(array(
    "postlar"=>$basliklar,
    "toplam"=>$toplam["COUNT(*)"]
));



?>