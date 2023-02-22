<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/addvideostoalbum.php';

// initialize object
$addvideostoalbum = new AddVideosToAlbum();
 
// products array
$postData = json_decode(file_get_contents("php://input"));
$result_arr=array();
$returnPointer=$addvideostoalbum->updateVideoStatus($postData);
 
// check if more than 0 record found
if($returnPointer > 0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= 'Video Status Updated';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]= 'Failed to Update Video Status';
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>