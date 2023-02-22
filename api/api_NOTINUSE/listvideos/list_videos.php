<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/listvideos.php';


// initialize object
$listvideo = new ListVideos();
 
$result_arr = array();
$result_arr["records"] = $listvideo->getListVideos();
// print $postUrl;die;
 // check if more than 0 record found
if($result_arr>0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= "Retrieved Data";
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"] = 'Failed';
	$result_arr["message"] = "Failed to Retrieve Data";
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>