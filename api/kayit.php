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

        $kadi=@strip_tags(trim($_POST["kadi"]));
        $isim=@strip_tags(trim($_POST["isim"]));
        $sifre=@md5(strip_tags(trim($_POST["sifre"])));
        $sifretekrar=@md5(strip_tags(trim($_POST["sifretekrar"])));
        $eposta=strip_tags(trim($_POST["eposta"]));


        if(!$isim || !$sifre || !$sifretekrar || !$eposta ){
            echo json_encode(array(
                "mesaj" => "Lutfen tum alanları doldurun"
                
            ));
        }else if(is_numeric($isim)){
            echo json_encode(array(
                "mesaj" => "Kullanıcı adı sadece sayılardan oluşamaz"
                
            ));
        }else if(strlen($isim)>=15){
            echo json_encode(array(
                "mesaj" => "Lutfen kullanıcı adınızı 15 karakterden büyük yapmayın"
                
            ));
        }else if($sifre != $sifretekrar){
            echo json_encode(array(
                "mesaj" => "Sifreler birbiriyle uyuşmuyor"
                
            ));
        }else if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
            echo json_encode(array(
                "mesaj" => "Lutfen gecerli bir eposta giriniz"
                
            )); 
        }else{
            //aynı isimle kayıtlı uye var mı?
            $uyetekrar=$db->prepare("SELECT * FROM nosbir WHERE uye_kadi=:kadi limit 0,1");
            $uyetekrar->bindParam(":kadi",$kadi);
            $uyetekrar->execute();
            $row=$uyetekrar->fetch(PDO::FETCH_ASSOC);
            if($row->rowCount()){
                echo json_encode(array(
                    "mesaj" =>"Bu kullanıcı adını kullanamazsınız"
                ));
            }else{
                $iptekrar=$db->prepare("SELECT * FROM nosbir WHERE uye_ip= :ip");
                $iptekrar->bindParam(":id",$ip);
                $iptekrar->execute();
                $row=$iptekrar->fetch(PDO::FETCH_ASSOC);
                if($row->rowCount()>3){
                    echo json_encode(array(
                        "mesaj" => "Aynı ip ile daha fazla hesap açamazsınız",
                        "durum" => 0
                    ));
                }else{

                    $ekle=$db->prepare("INSERT INTO nosbir SET
                    uye_kadi=:kadi,
                    uye_isim = :isim,
                    uye_sifre= :sifre,
                    uye_eposta= :eposta,
                    uye_ip= :ip,
                    uye_durum= :durum,
                    uye_rutbe= :rutbe");

                    $ekle->bindParam(":kadi",$kadi);
                    $ekle->bindParam(":isim",$isim);
                    $ekle->bindParam(":sifre",$sifre);
                    $ekle->bindParam(":eposta",$eposta);
                    $ekle->bindParam(":ip",$ip);
                    $ekle->bindParam(":durum",1);
                    $ekle->bindParam(":rutbe",1);

                    if($ekle->execute()){
                       
                        echo json_encode(array(
                            "mesaj" => "Basarıyla Kayıt edildi",
                            "durum" => 1
                            
                        ));

                    }else{
                        echo json_encode(array(
                            "mesaj"=>"Veritabanına Kayıt Edilirken Bir Sorun Oluştu.",
                            "durum" => 0
                        ));
                    }
                    
            
                
                }

            }

            
        }

}






?>