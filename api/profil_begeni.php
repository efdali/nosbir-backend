<?php
//Kullanıcıya ait begenileri listeler.
require_once("../sistem/ayar.php");
require_once("../yardımcılar/token-dogrula.php");


if ($_REQUEST) {

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

    $offset=$sayfa*$limit;

    $sorgu=$db->prepare("select a.text,a.created_at,u.user_id,u.nick,u.picture from answers a,users u,posts p,likes l
    where a.post_id=p.post_id and a.answer_status=1 and u.user_id=a.user_id and l.user_id=u.user_id and l.user_id=:user_id
    order by a.created_at desc limit $offset,$limit");

    $sorgu->binParam(":user_id",$id);

    
    
    if($sorgu->execute()){
            $begeni=$sorgu->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(array(
                "yorum"=>$begeni,
                "durum"=>1
            ));
    }else{
            echo json_encode(array(
                "mesaj" =>"Kullanıcının begenilen postlar listelenirken bir sorun oluştu",
                "durum" =>0
    
            ));
        }
    

    





}
?>