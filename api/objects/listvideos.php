<?php
include_once '../config/database.php';
class ListVideos{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	// read products with pagination
	public function getListVideos(){
		// select query
		$sql = "SELECT * FROM dp_yt_videos_master ORDER BY VIDEO_PUBLISH_DATE DESC";
	 
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
}