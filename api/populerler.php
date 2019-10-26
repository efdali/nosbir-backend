<?php

if($_POST){

    //Tum zamanlar
    if($_POST["durum"]==1){
    
    $listele=$db->prepare("select *,count(y.postId) ys from post p
                        left join yorum y on y.postId=p.id
                        group by p.id
                        order by ys desc
                        limit 10");
    
    if($listele->execute()){
        echo json_encode(array(
            "mesaj" => "Tum zamanların en populer 10 yorumu başarıyla listelendi.",
            "durum" => 1
        ));
        
    }else{
        echo json_encode(array(
            "mesaj" => "Tum zamanların en populer 10 yorumu listelenirken bir sorun oluştu.",
            "durum" => 0
        ));
        
    }
    
    
    
    
    //Bugün
    }if($_POST["durum"]==0){







    }















}


















?>