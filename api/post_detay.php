<?php
//Anasayfada posta tıkladıgında cıkan yer

require_once("../sistem/ayar.php");

//Efdal! post baslıgı gonder
$link=$_GET["link"];


//TODO secilen postu getir
$post=$db->prepare("select * from post p,uye u,topluluk t on
                        p.uyeId=u.id and p.toplulukId=t.id 
                        where p.baslik=:baslik");

                       

$post->bindParam(":baslik",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    //TODO post durum 1#acık 2#banlı

    if($row["p.durum"==2]){
        echo json_encode(array(
            "mesaj" => "bu post engelli bir kullanıcıya ait",
            "durum" =>0
        ));
    }else{
   
    //TODO secilen posta kayıtlı yorumları getir.
    $yorum=$db->prepare("select * from uye u,post p,yorum y on
                            y.uyeId=u.id and y.postId=p.id and p.uyeId=u.id and p.toplulukId=t.id
                            where y.postId=:p.id ");
    
    $yorum->bindParam("p.id:",$row["p.id"]);
    $yorum->execute();
    //Efdal! burada if yapmam gerekiyor mu? sonucta hic yorum yoksa da basarısız olmus sayılmaz.
    $toplamYorum=$yorum->fetchAll(PDO::FETCH_ASSOC);
 
    echo json_encode(array(
        "post_detay" => $post,
        "yorum" => $yorum,
        "toplamYorum" => $toplamYorum
    ));

}

}else{
    echo json_encode(array(
        "mesaj" => "Boyle bir başlık veritabanında bulunmamaktadır.",
        "durum" => 0
    ));
}




?>