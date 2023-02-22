<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/services.php';
// initialize object
$services = new Services();
 
$postData = json_decode(file_get_contents("php://input"));
$result_arr = array();
$returnPointer = $services->checkUserProductsSettings($postData);

 // check if more than 0 record found
if($returnPointer > 0){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= 'Products Found';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"] = 'Failed';
	$result_arr["message"] = 'No Products Found';
    // set response code - 404 Not found
    http_response_code(200);
     // tell the user products does not exist
    echo json_encode($result_arr);
}
?>