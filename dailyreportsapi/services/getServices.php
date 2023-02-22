<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/services.php';

// initialize object
$services = new Services();
 
// products array
$result_arr=array();
$result_arr["records"]=$services->getServices();
 
// check if more than 0 record found
if(count($result_arr["records"])>0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= count($result_arr["records"]) . ' products found.';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]='No products found.';
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>