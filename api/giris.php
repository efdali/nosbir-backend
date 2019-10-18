<?php

require_once("../sistem/ayar.php");
use \Firebase\JWT\JWT;

if($_POST){
    $kadi=@strip_tags(trim($_POST["kadi"]);
    $sifre=@md5(strip_tags(trim($_POST["sifre"])));

    if(!$kadi || !$sifre){
        echo json_encode(array(
            "mesaj" => "Kullanıcı Adı veya sifre bos bırakılamaz"
        ));
    }else{
        $giris=$db->prepare("SELECT * FROM nosbir WHERE uye_kadi= :kadi AND uye_sifre= :sifre");
        $giris->bindParam(":kadi",$kadi);
        $giris->bindParam(":sifre",$sifre);
        $giris->execute();
        $row=$giris->fetch(PDO::FETCH_ASSOC);
        $kontrol=$row->rowCount();
        if($kontrol){
            if($row["uye_durum"]==2){
                echo json_encode(array(
                    "mesaj" => "Topluluğa aykırı davranışlarınızdan dolayı engellendiniz"
                ));
            }else{
            
                $kadi=$row["uye_kadi"];
                $eposta=$row["uye_eposta"];
                $rutbe=$row["uye_rutbe"];
                $id=$row["uye_id"];
                
                $secret_key="nosbirciler";
                $issuer_claim = "nosbir.com"; // this can be the servername
                $audience_claim = "THE_AUDIENCE";
                $issuedat_claim = time(); // issued at
                $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                $expire_claim = $issuedat_claim + 120; // token süresi
                $token = array(
                    "iss" => $issuer_claim,
                    "aud" => $audience_claim,
                    "iat" => $issuedat_claim,
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                        "id" => $id,
                        "kadi"=>$kadi,
                        "rutbe"=>$rutbe,
                        "mail" => $eposta
                    )
                );

                $jwt=JWT::encode($token,$secret_key);

                echo json_encode(array(
                    "mesaj"=> "Giriş Başarılı",
                    "token" => $token,
                    "suresi"=>$expire_claim 
                ));

            }

        }else{

            echo json_encode(array(
                "mesaj" => "Kullanıcı Bilgileri Bulunamadı"
            ));
            
        }
    

    }
}



?>