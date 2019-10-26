<?php
//TODO Begeni 
// TODO SQL hatalı
$link=$_GET["link"];
$aranan=$_GET["aranan"];


$sayfa=@$_GET["s"] ? @$_GET["s"] : 1;
$limit=10;

$offset=($sayfa-1)*$limit;


$arama=$db->prepare("select *,count(b.postId) from post p
                left join begeni b on b.postId=p.id 
                left join topluluk t on t.id=p.toplulukId
                left join uye u on u.id=p.id and b.uyeId=u.id
                where u.durum=0 and p.durum!=0 and p.baslik like aranan=:aranan
                order by p.tarih desc
                limit $offset,$limit");

$arama->bindParam(":aranan",'%'.$aranan.'%');

$basliklar=$sorgu->fetchAll(PDO::FETCH_ASSOC);

$toplamSayfa=$db->prepare("select COUNT(*) from post");
$toplamSayfa->execute();
$toplam=$toplamSayfa->fetch(PDO::FETCH_ASSOC);

echo json_encode(array(
    "postlar"=>$basliklar,
    "toplam"=>$toplam["COUNT(*)"]
));




?>