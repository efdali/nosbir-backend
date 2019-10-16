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
                "message" => "Lutfen tum alanları doldurun"
                
            ));
        }else if(is_numeric($isim)){
            echo json_encode(array(
                "message" => "Kullanıcı adı sadece sayılardan oluşamaz"
                
            ));
        }else if(strlen($isim)<=7 || strlen($isim)>=30){
            echo json_encode(array(
                "message" => "Lutfen kullanıcı adınızı 7 karakterden küçük ve 30 karakterden büyük yapmayın"
                
            ));
        }else if($sifre != $sifretekrar){
            echo json_encode(array(
                "message" => "Sifreler birbiriyle uyuşmuyor"
                
            ));
        }else if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
            echo json_encode(array(
                "message" => "Lutfen gecerli bir eposta giriniz"
                
            ));
        }else{
            //aynı isimle kayıtlı uye var mı?
            $uyetekrar=$db->prepare("SELECT * FROM nosbir WHERE uye_kadi=?");
            $uyetekrar->execute(array(
                $kadi
            ));
            $row=$uyetekrar->fetch(PDO::FETCH_ASSOC);
            if($row->rowCount()){
                echo json_encode(array(
                    "message" =>"Bu kullanıcı adını kullanamazsınız"
                
                ));
            }else{
                //ayni ip ile kayıtlı sayısı=?
                $iptekrar=$db->prepare("SELECT * FROM nosbir WHERE uye_ip=?");
                $iptekrar->execute(array(
                    $ip
                ));
                $row=$iptekrar->fetch(PDO::FETCH_ASSOC);
                if($row->rowCount()>3){
                    echo json_encode(array(
                        "message" => "Aynı ip ile daha fazla hesap açamazsınız"
                        
                    ));
                }else{

                    
                    $ekle=$db->prepare("INSERT INTO nosbir SET
                    uye_kadi=?,
                    uye_isim,
                    uye_sifre=?,
                    uye_eposta=?
                    uye_ip=?,
                    uye_durum=?");

                    $ekle->execute(array(
                        $kadi,
                        $isim,
                        $sifre,
                        $eposta,
                        $ip,
                        1));

                    $decoded = JWT::decode($kadi,$isim,$eposta,$rutbe,$id array('HS256'));
                    //[Efdal!Decoded true,false diye deger donduruyor mu?]
                    if($decoded){
                        http_response_code(200);
                        echo json_encode(array(
                            "message" => "Basarıyla Kayıt edildi",
                            "data" => $decoded->data
                        ));

                    }else{
                        echo json_encode(array(
                            "message"=>"Veritabanına Kayıt Edilirken Bir Sorun Oluştu."
                        ))
                    }
                    
            
                
                }

            }

            
        }

}






?>