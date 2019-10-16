<?php

require_once("../sistem.php");
use \Firebase\JWT\JWT;

if($_POST){
    $kadi=@strip_tags(trim($_POST["kadi"]);
    $sifre=@md5(strip_tags(trim($_POST["sifre"])));

    if(!$kadi || !$sifre){
        echo json_encode(array(
            "message" => "Kullanıcı Adı veya sifre bos bırakılamaz"
            
        ));
    }else{

        $giris=$db->prepare("SELECT * FROM nosbir WHERE uye_kadi=? AND uye_sifre=?");
        $giris->execute(array(
            $kadi,
            $sifre
        ));
        $row=$giris->fetch(PDO::FETCH_ASSOC);
        $kontrol=$row->rowCount();

        if($row["uye_durum"]==2){
            echo json_encode(array(
                "message" => "Topluluğa aykırı davranışlarınızdan dolayı engellendiniz"
                
            ));
        }else{

        if($kontrol){
            $kadi=$row["uye_kadi"];
            $isim=$row["uye_isim"];
            $eposta=$row["uye_eposta"];
            $rutbe=$row["uye_rutbe"];
            $id=$row["uye_id"];
            $decoded = JWT::decode($kadi,$isim,$eposta,$rutbe,$id array('HS256'));
            
            
            echo json_encode(array(
                "message" => "Erişim Sağlandı.",
                "data" => $decoded->data
            ));

        }else{

            
 
        
            echo json_encode(array(
            "message" => "Kullanıcı Bilgileri Bulunamadı"
            
        ));
            
        }

    }
}

}





?>