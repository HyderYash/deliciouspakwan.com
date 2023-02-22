<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/carWashDBConnection.php';

// initialize object
$cwDBCon = new CarWashDBConnection();
 
// products array
$result_arr = array();
$result_arr["records"] = $cwDBCon->returnConnectionElements();
http_response_code(200);
// make it json format
echo json_encode($result_arr);
?>