<?php
// required headers
require_once '../config/cors_headers.php';
include_once '../config/database.php';
class Shorts{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	public function getShortsList(){
		// select query
		$result = array();
		$sql = "SELECT `ID`, `VIDEO_ID`, `VIDEO_TITLE`, `VIDEO_THUMB_URL`, `VIDEO_PUBLISH_DATE` FROM `dp_yt_videos_master` WHERE `VIDEO_TITLE` LIKE '%#shorts%'";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows > 0){				
			$result = $this->db->get_multiple_tables_records($sql);
		}
		return $result;
	}
}