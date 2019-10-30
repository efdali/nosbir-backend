<?php
require '../sistem/ayar.php';

$sorgu=$db->prepare("select name,group_seo from groups order by group_id desc");
if($sorgu->execute()){

    $row=$sorgu->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array(
        "durum"=>1,
        "gruplar"=>$row
    ));

}else{
    echo json_encode(array(
        "durum"=>0,
        "mesaj"=>"Teknik bir sorun oluştu"
    ));
}