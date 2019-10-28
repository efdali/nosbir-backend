<?php
//TODO Begeni 
// TODO SQL hatalı
$link=$_GET["link"];
$aranan=$_GET["aranan"];
$topluluk=@$_GET["topluluk"];


$sayfa=@$_GET["s"] ? @$_GET["s"] : 1;
$limit=10;

$offset=($sayfa-1)*$limit;


if(!$topluluk){

}else{
    
}


$arama=$db->prepare("select p.*,sum(l.type),toplam from posts p 
                    left join (select post_id,COUNT(a.post_id) as toplam from answers a GROUP BY a.post_id) a on a.post_id=p.post_id 
                    left join likes l on l.post_id=p.post_id 
                    inner join groups g on p.groups_id=g.group_id
                    inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 and p.title like aranan=:aranan
                    group by p.post_id
                    order by created_at 
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