<?php
//TODO Begeni
// TODO SQL hatalı
require_once("../sistem/ayar.php");

$link=$_GET["link"];



$post=$db->prepare("select p.*,sum(l.type),toplam from posts p 
                    left join (select post_id,COUNT(a.post_id) as toplam from answers a GROUP BY a.post_id) a on a.post_id=p.post_id 
                    left join likes l on l.post_id=p.post_id 
                    inner join groups g on p.groups_id=g.group_id
                    inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 and p.seo=:seo
                    group by p.post_id
                    ");
                    
$post->bindParam(":seo",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    $postId=$row["id"];
    

        $postYorum=$db->prepare("select * ,count(p.id) from yorum y,post p
                            where y.postId=:$postId
                            group by y.id
                            order by y.tarih desc");
        $postYorum->bindParam(":link",$link);
        if($postYorum->execute()){
            echo json_encode(array(
                "post_detay" => $row,
                "postYorum" => $postYorum,
                "postBegeni" => $postBegeni
            ));
        }else{
            echo json_encode(array(
                "mesaj" => "Yorumlar gonderilirken bir hata oluştu.",
                "durum" => 0
            ));
        }

   
}else{
    echo json_encode(array(
        "mesaj" => "Boyle bir başlık bulunmamaktadır.",
        "durum" => 0
    ));
}



?>