<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/getshortsbyapi.php';

// initialize object
$shorts = new Shorts();
 
// products array
$result_arr=array();
$result_arr["records"]=$shorts->getAllShortsAndStoreInFile();
print($result_arr["records"] . "hello");die;
 
// check if more than 0 record found
if(count($result_arr["records"])>0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= 'Shorts has been updated in the file...';
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]='Failed to update shorts in the file...';
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>