<?php
//TODO Begeni
// TODO SQL hatalı
require_once("../sistem/ayar.php");

$link=$_GET["link"];






$post=$db->prepare("select *,count(b.postId) from post p
                    left join begeni b on b.postId=p.id 
                    left join topluluk t on t.id=p.toplulukId
                    left join uye u on u.id=p.id and b.uyeId=u.id
                    where p.seoLink=:link and u.durum=0 and p.durum!=0
                    ");
                    

$post->bindParam(":link",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    $postId=$row["id"];
    $postBegeni=$db->prepare("select * from post p
                        inner join begeni b on b.postId=p.id
                        where p.seoLink=:link
                        ");
    $postBegeni->bindParam(":link",$link);
    if($postBegeni->execute()){

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
            "mesaj" => "Post begeniler gonderilirken bir hata oluştu",
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