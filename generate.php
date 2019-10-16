<?php
require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
 
date_default_timezone_set('Europe/Istanbul');
 
if (!empty($_POST)) {
    $key = "Örnek Key"; // bu bizim oluşturacağımız bi nevi şifremiz
    $iss = "http://localhost:80";
    $aud = "http://localhost:80";
    $iat = 1356999524;
    $nbf = 1357000000;
 
    $token = [
        'iss' => $iss,
        'aud' => $aud,
        'iat' => $iat,
        'nbf' => $nbf,
        'data' => [
            'id' => 1,
            'firstname' => 'test',
            'email' => 'test'
        ]
    ];
 
    http_response_code(200);
 
    $jwt = JWT::encode($token,$key);
    echo $jwt;
}
?>