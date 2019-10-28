<?php
//TODO Begeni
// TODO SQL hatalı
require_once("../sistem/ayar.php");

$sayfa=@$_GET["s"] ? @$_GET["s"] : 1;
$topluluk=$_GET["topluluk"];
$limit=10;

$offset=($sayfa-1)*$limit;

if(!$topluluk){
    $sorgu=$db->prepare("select p.*,sum(l.type),toplam from posts p 
                        left join (select post_id,COUNT(a.post_id) as toplam from answers a GROUP BY a.post_id) a on a.post_id=p.post_id 
                        left join likes l on l.post_id=p.post_id 
                        inner join groups g on p.groups_id=g.group_id
                        inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 
                        group by p.post_id
                        order by created_at desc
                        limit $offset,$limit");


}else{
    $sorgu=$db->prepare("select p.*,sum(l.type),toplam from posts p 
                        left join (select post_id,COUNT(a.post_id) as toplam from answers a GROUP BY a.post_id) a on a.post_id=p.post_id 
                        left join likes l on l.post_id=p.post_id 
                        inner join groups g on p.groups_id=g.group_id
                        inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 
                        and g.group_id=:group_id
                        group by p.post_id
                        order by created_at desc
                        limit $offset,$limit");


                        $arama->bindParam(":group_id",$topluluk);


}

 

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