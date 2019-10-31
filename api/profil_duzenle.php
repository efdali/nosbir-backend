<?php
require_once("../sistem/ayar.php");
require_once("../yardımcılar/token-dogrula.php");

if ($_REQUEST) {

    $token = json_decode(TokenDogrula::dogrula()); 
    if ($token->{"durum"} == 0) { 
        echo json_encode(array(
            "durum" => 0,
            "mesaj" => "Token doğrulanamadı."
        ));
        die(); 
    }
    $id=$token->{"token"}->{"data"}->{"id"}; 
    $kadi=strip_tags(trim($_REQUEST["kadi"]));

    $sec=$db->prepare("select * from users where user_id= :user_id limit 1");
    $sec->bindParam(":user_id",$id);
    $sec->execute(); 
    $row=$sec->fetch(PDO::FETCH_ASSOC);

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
    if($_FILES["resim"]["name"]){
        echo "var";
        $boyut=1024*1024*3;
        $uzanti=explode(".",$_FILES["resim"]["name"]);
        $uzanti=$uzanti[count($uzanti)-1]; 
        $adi=$kadi."-".date('m-d-Y')."-".rand(0,9999999).".".$uzanti;
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
                    if(!move_uploaded_file($_FILES["resim"]["tmp_name"],$yol)){
                                        
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
        
    }else{
        $yol=$row["picture"];
    }

    $guncelle=$db->prepare("update users set
                        nick=:nick,
                        picture=:picture
                        where user_id = :user_id");

    $guncelle->bindParam(":user_id",$id);
    $guncelle->bindParam(":nick",$kadi);
    $guncelle->bindParam(":picture",$yol);


    if($guncelle->execute()){
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