<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/addvideostoalbum.php';

// initialize object
$addvideostoalbum = new AddVideosToAlbum();
 
// products array
$result_arr=array();
// get id of settings to be edited

//$postData = file_get_contents("php://input");
$returnPointer = $addvideostoalbum->uploadVideoAndUpdateInDB();
 
// check if more than 0 record found
if($returnPointer[0] == true){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= $returnPointer[1];
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"] = 'Failed';
	$result_arr["message"] = $returnPointer[1];
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>