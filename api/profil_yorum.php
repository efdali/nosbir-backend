<?php
//Kullanıcıya ait yorumları listeler.
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

    $sorgu=$db->prepare("select a.text,a.created_at,u.user_id,u.nick,u.picture from answers a,users u,posts p
                        where a.post_id=p.post_id and a.answer_status=1 and u.user_id=a.user_id and u.user_id=:user_id
                        order by a.created_at desc limit $offset,$limit");

    $sorgu->bindParam(":post_id",$id);

    

    if($sorgu->execute()){
        $yorum=$sorgu->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "yorum"=>$yorum,
            "durum"=>1
        ));
    }else{
        echo json_encode(array(
            "mesaj" =>"Kullanıcıya ait mesajlar listelenirken bir sorun oluştu",
            "durum" =>0

        ));
    }











}
?>