<?php
//Anasayfada posta tıkladıgında cıkan yer

require_once("../sistem/ayar.php");
//deneme
//Efdal! post baslıgı gonder
$link=$_GET["link"];


// TODO yorumları dahil et
/*
gonderecegim
yorum sayısını ->kayıtlı seolinke ait yorumları getir
seolinke ait postu gonderecegim.

yorum=uyeId

*/
$post=$db->prepare("select * from 
                    post p left join (select * from yorum y where y.durum!=0) y on p.id=y.postId
                    inner join topluluk t on t.id=p.toplulukId 
                    inner join uye u p.uyeId=u.id where u.durum=1 and p.durum!=0 
                    p.seoLink=:link
                    order by p.tarih DESC
                    ");
/*
$post=$db->prepare("select p.id,p.baslik,p.icerik,p.tarih,u.id,u.kadi,u.resim,t.baslik as topluluk,t.logo from post p,uye u,topluluk t,yorum y where
                        y.uyeId=u.id and y.postId=p.id and
                        p.toplulukId=t.id and u.durum=1 and p.durum=1 and p.seoLink=:link");
*/

$post->bindParam(":link",$link);
$post->execute();


if($row=$post->fetch(PDO::FETCH_ASSOC)){
    
    echo json_encode(array(
        "post_detay" => $row
    ));

}else{
    echo json_encode(array(
        "mesaj" => "Boyle bir başlık veritabanında bulunmamaktadır.",
        "durum" => 0
    ));
}




?>