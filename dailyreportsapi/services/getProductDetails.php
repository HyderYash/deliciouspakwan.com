<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/services.php';

// initialize object
$services = new Services();
 
$result_arr=array();
// get id of settings to be edited
$postData = json_decode(file_get_contents("php://input"));
$result_arr = $services->getProductDetails($postData);

 
	$result_arr["status"]='Success';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);

?>