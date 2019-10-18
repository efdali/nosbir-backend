<?php

require_once("../sistem/ayar.php");
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

if($_POST){
    $kadi=@strip_tags(trim($_POST["kadi"]));
    $sifre = @md5(strip_tags(trim($_POST["sifre"])));
    

    if(!$kadi || !$sifre){
        echo json_encode(array(
            "mesaj" => "Kullanıcı Adı veya sifre bos bırakılamaz",
            "durum" => 0
        ));
    }else{
        $giris = $db->prepare("SELECT * FROM kullanicilar WHERE kadi= :kadi AND sifre= :sifre");
        $giris->bindParam(":kadi",$kadi);
        $giris->bindParam(":sifre",$sifre);
        $giris->execute();
        if ($giris->rowCount()) {
            $row = $giris->fetch(PDO::FETCH_ASSOC);
            if ($row["durum"] == 2) {
                echo json_encode(array(
                    "mesaj" => "Topluluğa aykırı davranışlarınızdan dolayı engellendiniz",
                    "durum" => 0
                ));
            }else{

                $kadi = $row["kadi"];
                $eposta = $row["eposta"];
                $rutbe = $row["rutbe"];
                $id = $row["id"];
                
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
                    "token" => $jwt,
                    "suresi"=>$expire_claim 
                ));

            }

        }else{

            echo json_encode(array(
                "mesaj" => "Kullanıcı Bilgileri Bulunamadı",
                "durum" => 0
            ));
            
        }
    

    }
}



?>