<?php
// required headers
require_once '../config/cors_headers.php';
// include database and object files
require_once '../objects/nodeDBStarterConnection.php';

// initialize object
$nDBCon = new NodeDBStarterConnection();
 
// products array
$result_arr = array();
$result_arr["records"] = $nDBCon->returnConnectionElements();
http_response_code(200);
// make it json format
echo json_encode($result_arr);
?>