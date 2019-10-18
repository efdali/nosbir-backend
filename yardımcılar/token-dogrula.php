<?php

require "vendor/autoload.php";
use \Firebase\JWT\JWT;
class TokenDogrula{

    public function dogrula(){
        
        $secret_key="nosbirciler";
        $header=apache_request_headers();
        $token=$header["Authorization"];

        if($token){
            try{
                 $decoded=JWT::decode($token,$secret_key,array("HS256"));
                 return json_encode(array(
                     "durum"=>1,
                     "decoded"=>$decoded
                 ));
            }catch (Exception $e){
                return json_encode(array(
                    "durum"=>0
                ));
            }
         }else{
             http_response_code(401);
             return json_encode(array(
                 "durum"=>0       
             ));
         }
        

    }

}