<?php
include_once '../config/database.php';
class AddVideosToAlbum{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	public function getVideos() {
		// select query
		$sql = "SELECT * FROM dp_videos";
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function getVideosDataByID($postData) {
		// select query
		$sql = "SELECT * FROM dp_videos WHERE ID = " . $postData->VIDEO_ID;
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function updateVideoStatus ($postData){
		$updatePtr = $this->db->set_multiple_fields('dp_videos', 'VIDEO_TITLE = "' . $postData->VIDEO_TITLE . '",VIDEO_DESC = "' . $postData->VIDEO_DESC . '",VIDEO_STATUS = "' . $postData->VIDEO_STATUS . '"', 'ID = "' . $postData->VIDEO_ID . '"');
		if($updatePtr > 0){
			return true;
		}else{
			return false;
		}		
	}
	public function uploadVideoAndUpdateInDB(){
		ini_set('max_execution_time', '300');
		$this->cors();
		ini_set("upload_max_filesize", "3200000000000000000");
		$videoFolderPath = HTDOCS_PATH . '/images/uploadedVideos';
		move_uploaded_file($_FILES["videoFile"]["tmp_name"], $videoFolderPath . "/" . $_FILES["videoFile"]["name"]);
		$this->addVideoDataToDB($_REQUEST, $_FILES["videoFile"]["name"]);
		$tmpMsg = "Video Uploaded and Inserted in DB";
		$retArr = array(true,$tmpMsg);
		return $retArr;
	}
	public function addVideoDataToDB($dataArray, $videoName) {
		$currDate = date('Y-m-d h:i:s');
		if(trim($dataArray['videoTitle']) != "") {
			$sql = "SELECT ID  
					 FROM `dp_videos` 
					 WHERE LOWER(VIDEO_TITLE) = '" . strtolower(trim($dataArray['videoTitle'])) . "'"; //die;
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			
			if($chkSqlRows == 0){
				//print 'chkSqlRows is zero';
					$qry = "INSERT INTO dp_videos (`ID`, `VIDEO_TITLE`,`VIDEO_DESC`, `VIDEO_NAME`, `UPLOADED_DATE`,`VIDEO_STATUS`)  VALUES 
					(NULL, '" . $dataArray['videoTitle'] . "', '" . $dataArray['videoDesc'] . "', '" . $videoName . "', '" . $currDate . "', '" . $dataArray['videoStatus'] . "')";
					//print($qry);
					$ptr = 	$this->db->get_sql_exec($qry);
			}
			if($chkSqlRows == 1){
				//print 'chkSqlRows is one';
				$updatePtr = $this->db->set_multiple_fields('dp_videos', 'VIDEO_TITLE = "' . $dataArray['videoTitle'] . '",VIDEO_DESC = "' . $dataArray['videoDesc'] . '",VIDEO_NAME = "' . $videoName . '",UPLOADED_DATE = "' . $currDate . '",VIDEO_STATUS = "' . $dataArray['videoStatus'] . '"');
			}
		}
	}
	function cors() {
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
			exit(0);
		}
		//echo "You have CORS!";
	}
}