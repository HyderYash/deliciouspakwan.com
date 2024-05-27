<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/rasan.php';

// initialize object
$rasan = new Rasan();
 
// products array
$result_arr=array();
// get id of settings to be edited
$postData = json_decode(file_get_contents("php://input"));
$returnPointer = $rasan->saveRasanList($postData);
 
// check if more than 0 record found
if($returnPointer > 0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= 'Data Uploaded';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]='Failed To Upload Data';
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>