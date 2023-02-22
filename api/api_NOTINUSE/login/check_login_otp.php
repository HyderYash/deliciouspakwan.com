<?php
// required headers
require_once '../config/cors_headers.php';

// include database and object files
require_once '../objects/login.php';


// initialize object
$login = new Login();
 
$postData = json_decode(file_get_contents("php://input"));
$result_arr = array();
$returnPointer = $login->checkLoginOTP($postData);
// print $postData;die;
 // check if more than 0 record found
if($returnPointer === true){
 
	$result_arr["status"]='Success';
	$result_arr["message"]= 'OTP Success';
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
}
 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]='OTP Failed';
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>