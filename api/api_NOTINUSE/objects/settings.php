<?php
include_once '../config/database.php';
class Settings{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	// read products with pagination
	public function getSettings(){
		// select query
		$sql = "SELECT
					ID,	DISPLAY_NAME,MOBILE,DESKTOP 
				FROM
					dp_yt_display_settings";
	 
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}	
	// update the product
	function updateSettings($postData){
		$updatePtr = $this->db->set_multiple_fields('dp_yt_display_settings', 'MOBILE = "' . $postData->MOBILE . '",DESKTOP = "' . $postData->DESKTOP . '"', 'ID = "' . $postData->ID . '"');
		// execute the query
		if($updatePtr == 1){
			return true;
		}
		return false;
	}
}