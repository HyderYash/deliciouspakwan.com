<?php
include_once '../config/database.php';
class MenuItems{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	// read products with pagination
	public function getMenuItems(){
		// select query
		$sql = "SELECT
					* 
				FROM
					react_menu ORDER BY LIST_SEQ";
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}	
}