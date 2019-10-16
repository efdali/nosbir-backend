<?php

require_once("sistem/ayar.php");

header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");

//echo json_encode(array("result"=>"ok"));

require_once("api/index.php");


?>