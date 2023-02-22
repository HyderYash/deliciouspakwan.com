<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/shorts.php';

// initialize object
$shorts = new Shorts();
 
$result_arr=array();
$result_arr["records"]=$shorts->getShortsList();
 
// check if more than 0 record found
if(count($result_arr["records"])>0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= count($result_arr["records"]);
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]='No Shorts found.';
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>