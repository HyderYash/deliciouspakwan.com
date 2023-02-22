<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/atCon.php';

// initialize object
$atCon = new ActivityTracerConnection();
 
// products array
$result_arr = array();
$result_arr["records"] = $atCon->returnConnectionElements();
http_response_code(200);
// make it json format
echo json_encode($result_arr);
?>