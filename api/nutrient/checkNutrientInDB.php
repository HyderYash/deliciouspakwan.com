<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/nutrient.php';

// initialize object
$nutrient = new Nutrient();
 
// products array
$result_arr=array();
// get id of settings to be edited
$postData = json_decode(file_get_contents("php://input"));
$returnArr = $nutrient->checkNutrientInDB($postData);
 
// check if more than 0 record found
// print $result_pointer;die;
if($returnArr[0] > 0 && $returnArr[1] == true){
	$result_arr["status"]='Success';
	$result_arr["message"]= 'Food Nutrient already exist';
	$result_arr["FoodId"]= $returnArr[0];
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($result_arr);
} 
else{
	$result_arr["status"]='Failed';
	$result_arr["message"]='Call the Food Nutrition API';
	$result_arr["FoodId"]= $returnArr[0];
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user products does not exist
    echo json_encode($result_arr);
}
?>