<?php

require_once("../sistem/ayar.php");
require_once("../yardımcılar/token-dogrula.php");
if (isset($_GET)) {

    $token = json_decode(TokenDogrula::dogrula()); 
    if ($token->{"durum"} == 0) { 
        echo json_encode(array(
            "durum" => 0,
            "mesaj" => "Token doğrulanamadı."
        ));
        die(); 
    }

    $id=$token->{"token"}->{"data"}->{"id"}; 


    $sayfa=@$_GET["s"] ? @$_GET["s"] : 1;
    $limit=10;
    $offset=($sayfa-1)*$limit;


    $sorgu=$db->prepare("select p.*,u.*,sum(l.type),toplam from posts p 
                        left join (select post_id,COUNT(a.post_id) as toplam from answers a GROUP BY a.post_id) a on a.post_id=p.post_id 
                        left join likes l on l.post_id=p.post_id 
                        inner join groups g on p.groups_id=g.group_id
                        inner join users u on p.user_id=u.user_id where p.post_statu=1 and u.user_status=1 
                        and u.user_id=:user_id
                        group by p.post_id
                        order by created_at desc
                        limit $offset,$limit");

    $sorgu->bindParam(":user_id",$id);

    if($sorgu->execute()){
        $uyevepost=$sorgu->fetchAll(PDO::FETCH_ASSOC);


        $postSay=$db->prepare("select count(*) from posts");

        if($postSay->execute()){

            echo json_encode(array(
                "uyevepost"=>$uyevepost,
                "postsay" => $postSay["COUNT(*)"]
                
            ));

        }else{

            echo json_encode(array(
                "mesaj" => "Post sayısı hesaplanırken bir sorun oluştu",
                "durum" => 0
            ));
            
        }

        


    }else{

        echo json_encode(array(
            "mesaj" => "Uye bilgileri getirilirken bir sorun oluştu",
            "durum" => 0
        ));

    }








}
?>