<?php
// required headers
require_once '../config/cors_headers.php';
ini_set('max_execution_time', '300');
// include database and object files
require_once '../objects/video.php';


// initialize object
$video = new Video();
 
$postData = json_decode(file_get_contents("php://input"));
$result_arr = array();
$returnPointer = $video->addEditMultipleVideos($postData);
// print $postUrl;die;
 // check if more than 0 record found
if($returnPointer === 'Done'){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= 'Videos Sucessfully Updated';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"] = 'Failed';
	$result_arr["message"] = $returnPointer;
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>