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
    $sifre=strip_tags(trim($_REQUEST["sifre"]));
    $sifreyeni=strip_tags(trim($_REQUEST["sifreyeni"]));

    $sec=$db->prepare("select * from users where user_id= :user_id limit 1");
    $sec->bindParam(":user_id",$id);
    $sec->execute(); 
    $row=$sec->fetch(PDO::FETCH_ASSOC);

    if($row["passwd"]==$sifre){

        if($sifreyeni){
            if(strlen($sifreyeni)<=6){ 
                echo json_encode(array( 
                    "mesaj"=>"Yeni şifreniz 6 karakterden az olamaz", 
                    "durum"=>0 )); 
                } 
        }else{
            $sifreyeni=$row["passwd"];
        }
    
        $guncelle->prepare("update users set
                            passwd=:passwd");
    
        $guncelle->bindParam(":passwd",$sifreyeni);
    
        if($guncelle->execute()){
            echo json_encode(array(
                "mesaj" => "Sifre başarıyla güncellendi",
                "durum" => 1
            ));
        }else{
            echo json_encode(array(
                "mesaj" => "Sifre guncellenirken bir hata oluştu",
                "durum" => 0 
            ));
        }

    }else{

        echo json_encode(array(
            "mesaj" => "Sifrenizi yanlış girdiniz.",
            "durum" => 0
        ));

    }

    












}
?>