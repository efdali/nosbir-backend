<?php
require_once("../sistem/ayar.php");


if($_POST){
        //uye ip bul.
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
        $ip = $_SERVER['REMOTE_ADDR'];
        }

        // TODO resim işlemleri
        

        $kadi=@strip_tags(trim($_POST["kadi"]));
        $sifre=@md5(strip_tags(trim($_POST["sifre"])));
        $eposta=strip_tags(trim($_POST["eposta"]));

        if(!$kadi ||  !$sifre || !$eposta ){
            echo json_encode(array(
                "mesaj" => "Lutfen tum alanları doldurun.",
                "durum" => 0
                
            ));
        }else if(is_numeric($kadi)){
            echo json_encode(array(
                "mesaj" => "Kullanıcı adı sadece sayılardan oluşamaz.",
                "durum" => 0
                
            ));
        }else if(strlen($kadi)>=15){
            echo json_encode(array(
                "mesaj" => "Lutfen kullanıcı adınızı 15 karakterden büyük yapmayın.",
                "durum" => 0
                
            ));
        }else if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
            echo json_encode(array(
                "mesaj" => "Lutfen gecerli bir eposta giriniz.",
                "durum" => 0
                
            )); 
        }else{

            // TODO aynı maille giriş var mı kontrol edilecek..
            $mailtekrar=$db->prepare("SELECT * FROM uye WHERE eposta=:eposta");
            $mailtekrar->bindParam(":eposta",$eposta);
            $mailtekrar->execute();
            if($mailtekrar->rowCount()){
                echo json_encode(array(
                    "mesaj" => "Bu eposta adresini kullanamazsınız."
                ));
            }else{


            
            $uyetekrar = $db->prepare("SELECT * FROM uye WHERE kadi=:kadi limit 0,1");
            $uyetekrar->bindParam(":kadi",$kadi);
            $uyetekrar->execute();
            $row=$uyetekrar->fetch(PDO::FETCH_ASSOC);
            if ($uyetekrar->rowCount()) {
                echo json_encode(array(
                    "mesaj" =>"Bu kullanıcı adını kullanamazsınız.",
                    "durum" => 0
                ));
            }else{
                $iptekrar = $db->prepare("SELECT * FROM uye WHERE ip= :ip");
                $iptekrar->bindParam(":ip", $ip);
                $iptekrar->execute();
                $row=$iptekrar->fetch(PDO::FETCH_ASSOC);
                if ($iptekrar->rowCount() > 3) {
                    echo json_encode(array(
                        "mesaj" => "Aynı ip ile daha fazla hesap açamazsınız.",
                        "durum" => 0
                    ));
                }else{

                    $ekle = $db->prepare("INSERT INTO uye SET
                    kadi=:kadi,
                    sifre= :sifre,
                    eposta= :eposta,
                    ip= :ip,
                    durum= :durum,
                    rutbe= :rutbe");
                    $durum = 1;
                    $rutbe = 1;
                    $ekle->bindParam(":kadi",$kadi);
                    $ekle->bindParam(":sifre",$sifre);
                    $ekle->bindParam(":eposta",$eposta);
                    $ekle->bindParam(":ip",$ip);
                    $ekle->bindParam(":durum", $durum);
                    $ekle->bindParam(":rutbe", $rutbe);

                    if($ekle->execute()){
                       
                        echo json_encode(array(
                            "mesaj" => "Basarıyla Kayıt edildi.",
                            "durum" => 1
                            
                        ));

                    }else{
                        echo json_encode(array(
                            "mesaj" => $ekle->errorInfo(),
                            "durum" => 0
                        ));
                    }
                    
            
                
                }

            }
        }
            
        }

}






?>