<?php
require_once("../sistem/ayar.php");
require_once("../yardımcılar/token-dogrula.php");
if ($_POST) {

    $token = json_decode(TokenDogrula::dogrula()); 
    if ($token->{"durum"} == 0) { 
        echo json_encode(array(
            "durum" => 0,
            "mesaj" => "Token doğrulanamadı."
        ));
        die(); 
    }

    $id=$token->{"token"}->{"data"}->{"id"}; 

    $kadi=strip_tags(trim($_POST["kadi"]));
    $sifre=strip_tags(trim($_POST["sifre"]));
    $eposta=strip_tags(trim($_POST["eposta"]));
    

    $sec=$db->prepare("select * from uye where id=:id limit 1");
    $sec->bindParam(":id",$id);
    $sec->execute();
    $row=$sec->fetch(PDO::FETCH_ASSOC);



    
    if($_FILES["resim"]["name"]){
        $boyut=1024*1024*3;
        $uzanti=substr($_FILES["resim"]["name"],-4,4);
        $adi=rand(0,9999999).$uzanti;
        $yol="../resimler/".$adi;

        if($_FILES["resim"]["size"]>$boyut){
            echo json_encode(array(
                "mesaj" => "Dosya boyutu 3 mb'dan fazla olamaz",
                "durum" => 0
            ));       
         }else{
            $tip = ["image/jpeg","image/png","image/jpg","image/gif"];


            if(in_array($_FILES["resim"]["type"],$tip)){

                if(is_uploaded_file($_FILES["resim"]["tmp_name"])){

                    if(move_uploaded_file($_FILES["resim"]["tmp_name"],$yol)){

                        if($row["resim"]){
                            unlink($row["resim"]);
                        }
                    }else{
                        echo json_encode(array(
                            "mesaj" => "Dosya taşınırken bir sorun oluştu.",
                            "durum" => 0
                        ));
                    }

                }else{
                    echo json_encode(array(
                        "mesaj" => "Dosya yuklenirken bir sorun oluştu.",
                        "durum" => 0
                    ));
                }

            }else{
                echo json_encode(array(
                    "mesaj" => "Resim dosya formatı geçersiz.",
                    "durum" => 0
                ));
            }
         }
        
    }



    



    if($sifre){
        $sifre=md5($sifre);
    }else{
        $sifre=$row["sifre"];
    }


    if(!$yol){
        $yol=$row["resim"];
    }

    if($kadi){
        if(is_numeric($kadi)){
            echo json_encode(array(
                "mesaj" => "Kullanıcı adı sadece sayılardan oluşamaz",
                "durum" => 0
            ));
        }
        if(strlen($kadi)>=15){
            echo json_encode(array(
                "mesaj" => "Lutfen kullanıcı adınızı 15 karakterden büyük yapmayın.",
                "durum" => 0
                
            ));
        }
    }else{
        $kadi=$row["kadi"];
    }
    

    if($eposta){
        if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
            echo json_encode(array(
                "mesaj" => "Lutfen gecerli bir eposta giriniz.",
                "durum" => 0
            )); 
        }
    }else{
        $eposta=$row["eposta"];
    }


    



    $guncelle=$db->prepare("update uye set
                        kadi=:kadi,
                        eposta=:eposta,
                        sifre=:sifre,
                        resim=:resim");
    $guncelle->bindParam("kadi:",$kadi);
    $guncelle->bindParam("eposta",$eposta);
    $guncelle->bindParam("sifre",$sifre);
    $guncelle->bindParam("resim",$yol);


    if($guncelle->execute){
        echo json_encode(array(
            "mesaj" => "Profil başarıyla guncellendi.",
            "durum" => 1
        ));
    }else{
        echo json_encode(array(
            "mesaj" => "Profil guncellenirken bir sorun oluştu.",
            "durum" => 0
        ));
    }










    }

?>