<?php

if($_POST){

    //Tum zamanlar
    if($_POST["durum"]==1){
    
    $listele=$db->prepare("select *,count(y.postId) ys from post p
                        left join yorum y on y.postId=p.id
                        group by p.id
                        order by ys desc
                        limit 10");
    $listele->execute();
    $row=$listele->fetchAll(PDO::FETCH_ASSOC);
    
    if($row){
        echo json_encode(array(
            "tumpop"=> $row
        ));
        
    }else{
        echo json_encode(array(
            "mesaj" => "Tum zamanların en populer 10 postu listelenirken bir sorun oluştu.",
            "durum" => 0
        ));
        
    }
    
    
    
    
    //Bugün
    }if($_POST["durum"]==0){
    //TODO INTERVAL kısmı :DATE_SUB(CURDATE(), INTERVAL 0 DAY)
    $listele=$db->prepare("select *,count(yorum.postId) from post 
                            left join yorum on yorum.postId=post.id
                            where post.tarih >= curdate()
                            group by post.id
                            order by post.tarih 
                            limit 10");

    $listele->execute();

    $row=$listele->fetchAll(PDO::FETCH_ASSOC);


    if($row){
        echo json_encode(array(
            "bugunpop" => $row
        ));
    }else{
        echo json_encode(array(
            "mesaj" => "Bugunun en populer 10 postu listelenirken bir sorun oluştu",
            "durum" => 0
        ));
    }




    }















}


















?>