<?php
//TODO Begeni
// TODO SQL hatalı
require_once("../sistem/ayar.php");
$link=$_GET["link"];
$post=$db->prepare("select p.*,sum(l.type),toplam from posts p 
                    left join (select post_id,COUNT(a.post_id) as toplam from answers a where answer_status=1 GROUP BY a.post_id) a on a.post_id=p.post_id 
                    left join likes l on l.post_id=p.post_id 
                    inner join groups g on p.groups_id=g.group_id
                    inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 and p.seo=:seo
                    group by p.post_id
                    ");
                    
$post->bindParam(":seo",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    $postId=$row["post_id"];
    

        $postYorum=$db->prepare("select a.text,a.created_at,u.user_id,u.nick,u.picture from answers a,posts p,users u
                            where a.post_id=:post_id and a.answer_status=1 and u.user_id=a.user_id
                            order by a.created_at desc");
        $postYorum->bindParam(":post_id",$postId);
        if($postYorum->execute()){
            $yorum=$postYorum->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array(
                "post" => $row,
                "yorum" => $yorum
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
<?php
//TODO Begeni
// TODO SQL hatalı
require_once("../sistem/ayar.php");
$link=$_GET["link"];
$post=$db->prepare("select p.*,sum(l.type),toplam from posts p 
                    left join (select post_id,COUNT(a.post_id) as toplam from answers a where answer_status=1 GROUP BY a.post_id) a on a.post_id=p.post_id 
                    left join likes l on l.post_id=p.post_id 
                    inner join groups g on p.groups_id=g.group_id
                    inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 and p.seo=:seo
                    group by p.post_id
                    ");
                    
$post->bindParam(":seo",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    $postId=$row["post_id"];
    

        $postYorum=$db->prepare("select a.text,a.created_at,u.user_id,u.nick,u.picture from answers a,posts p,users u
                            where a.post_id=:post_id and a.answer_status=1 and u.user_id=a.user_id
                            order by a.created_at desc");
        $postYorum->bindParam(":post_id",$postId);
        if($postYorum->execute()){
            $yorum=$postYorum->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(array(
                "durum"=>1,
                "post" => array(
                    "data"=>$row,
                    "yorum"=>$yorum
                )
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