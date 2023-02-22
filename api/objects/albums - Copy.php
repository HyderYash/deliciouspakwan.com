<?php
// required headers
require_once '../config/cors_headers.php';
include_once '../config/database.php';
class Albums{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	// read products with pagination
	public function newAlbums($postData){
		if(trim($postData->ALBUM_FOLDER_TITLE) != "" && trim($postData->ALBUM_DISPLAY_TITLE) != "") {
			$sql = "SELECT ID  
						 FROM `dp_albums` 
						 WHERE LOWER(ALBUM_FOLDER_TITLE) = '" . strtolower(trim($postData->ALBUM_FOLDER_TITLE)) . "'"; //die;
				$chkSqlPtr = $this->db->get_sql_exec($sql);
				$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
				
				if($chkSqlRows == 0){
					//print 'chkSqlRows is zero';
						$qry = "INSERT INTO dp_albums (`ID`, `IMAGE_THUMB_URL`, `ALBUM_FOLDER_TITLE`, `ALBUM_DISPLAY_TITLE`,`ALBUM_DESC`, `UPLOADED_DATE`, `STATUS` )  VALUES 
						(NULL, '" . $postData->IMAGE_THUMB_URL . "', '" . trim($postData->ALBUM_FOLDER_TITLE) . "', '" . trim($postData->ALBUM_DISPLAY_TITLE) . "', '" . addslashes($postData->DESC) . "', '" . $postData->UPLOADED_DATE . "','" . $postData->STATUS . "')";
						//print($qry);
						$ptr = 	$this->db->get_sql_exec($qry);
				}
				if($chkSqlRows == 1){
					//print 'chkSqlRows is one';
					$updatePtr = $this->db->set_multiple_fields('dp_albums', 'IMAGE_THUMB_URL = "' . $postData->IMAGE_THUMB_URL . '",ALBUM_DISPLAY_TITLE = "' . $postData->ALBUM_DISPLAY_TITLE . '",ALBUM_DESC = "' . addslashes($postData->DESC) . '",UPLOADED_DATE = "' . $postData->UPLOADED_DATE . '",STATUS = "' . $postData->STATUS . '"', 'LOWER(ALBUM_FOLDER_TITLE) = "' . strtolower(trim($postData->ALBUM_FOLDER_TITLE)) . '"');
				}
		}
		return true;
	}
	public function getAlbumsList(){
		// select query
		$result = array();
		$sql = "SELECT
				  dp_albums.ID,
				  dp_albums.`ALBUM_FOLDER_TITLE`,
				  dp_albums.`ALBUM_DISPLAY_TITLE`,
				  dp_albums.IMAGE_THUMB_URL,
				  dp_albums.ALBUM_DESC,
				  dp_albums.UPLOADED_DATE,
				  dp_albums.STATUS,
				  COUNT(dp_albums_photos.ALBUM_ID) AS photoCount
				FROM
				  dp_albums
				LEFT JOIN dp_albums_photos ON dp_albums.ID = dp_albums_photos.ALBUM_ID 
				WHERE dp_albums.STATUS = 'Y' 
				GROUP BY dp_albums.ID,dp_albums.`ALBUM_FOLDER_TITLE`,dp_albums.`ALBUM_DISPLAY_TITLE`";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		
		if($chkSqlRows > 0){				
			$result = $this->db->get_multiple_tables_records($sql);
		}
		// return values from database
		return $result;
	}	
	public function addDbRecordsOfPhotosInAlbum($albumId,$fileName){
		$currDate = date('Y-m-d h:i:s');
		$sql = "SELECT ID  
				 FROM `dp_albums_photos` 
				 WHERE ALBUM_ID = " . $albumId . " AND LOWER(IMAGE_THUMB_URL) = '" . strtolower(trim($fileName)) . "'"; //die;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows == 0){
			$qry = "INSERT INTO dp_albums_photos (`ID`, `ALBUM_ID`, `IMAGE_THUMB_URL`,`UPLOADED_DATE` )  VALUES 
			(NULL, '" . $albumId . "', '" . trim($fileName) . "','" . $currDate . "')";
			$ptr = 	$this->db->get_sql_exec($qry);
		}
	}
	public function getAlbumsPhotosList($postData){
		// select query
		$sql = "SELECT * FROM dp_albums_photos WHERE ALBUM_ID = " . $postData;
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function modifyAlbumTitle($postData){
		$updatePtr = $this->db->set_multiple_fields('dp_albums', 'ALBUM_DISPLAY_TITLE = "' . $postData->newAlbumDisplayTitle . '"', 'ID = "' . $postData->AlbumId . '"');
		if($updatePtr > 0){
			return true;
		}else{
			return false;
		}
	}
	public function uploadAlbumThumbnail(){
		$this->cors();
		$albumFolderPath = HTDOCS_PATH . '/images/albums/' . $_REQUEST['albumIntTitle'];
		if (!file_exists($albumFolderPath)) {
			mkdir($albumFolderPath, 0777, true);
		}
		if (file_exists($albumFolderPath)) {
			$albumFolderThumbnailPath = $albumFolderPath . '/thumbnail';
			if (!file_exists($albumFolderThumbnailPath)) {
				mkdir($albumFolderThumbnailPath, 0777, true);
			}
			if (file_exists($albumFolderThumbnailPath)) {
				ini_set("upload_max_filesize", "3200000000");
				// Compress size and upload image 
				 
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $albumFolderThumbnailPath . "/" . $_FILES["file"]["name"])){
					$this->optimizeImageAfterUpload($albumFolderThumbnailPath . "/" . $_FILES["file"]["name"], 200, 75);
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}
	public function optimizeImageAfterUpload($fileName, $imageHeight, $quality) {
		list($width, $height, $type) = getimagesize($fileName);
		$old_image = $this->load_image($fileName, $type);
		if($imageHeight > $height){
			$imageHeight = $height;
		}
		$image_height_fixed = $this->resize_image_to_height($imageHeight, $old_image, $width, $height);
		$this->save_image($image_height_fixed, $fileName, $quality);
	}
	public function uploadAlbumPhotos(){
		ini_set('max_execution_time', '300');
		$this->cors();
		$albumFolderPath = HTDOCS_PATH . '/images/albums/' . $_REQUEST['albumIntTitle'];
		$tmpMsg = '';
		$albumId = $_REQUEST['albumId'];
		foreach ($_FILES as $myFile){
			for($i = 0; $i < count($myFile["name"]); $i++){
				if(move_uploaded_file($myFile["tmp_name"][$i], $albumFolderPath . "/" . $myFile["name"][$i])){
					$this->optimizeImageAfterUpload($albumFolderPath . "/" . $myFile["name"][$i], 800, 75);
					$this->addDbRecordsOfPhotosInAlbum($albumId,$myFile["name"][$i]);
					$tmpMsg = array('Success');
				}else{
					$tmpMsg = array('Failed');
				}
			}	
		}
		$retArr = array(true,$tmpMsg);
		return $retArr;
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
	public function load_image($filename, $type) {
		if( $type == IMAGETYPE_JPEG ) {
			$image = imagecreatefromjpeg($filename);
		}
		elseif( $type == IMAGETYPE_PNG ) {
			$image = imagecreatefrompng($filename);
		}
		elseif( $type == IMAGETYPE_GIF ) {
			$image = imagecreatefromgif($filename);
		}
		return $image;
	}
	public function save_image($new_image, $new_filename, $quality=80) {
		$new_type = pathinfo($new_filename, PATHINFO_EXTENSION);
		if( $new_type == 'jpg' ) {
			imagejpeg($new_image, $new_filename, $quality);
		 }
		 elseif( $new_type == 'png' ) {
			imagepng($new_image, $new_filename);
		 }
		 elseif( $new_type == 'gif' ) {
			imagegif($new_image, $new_filename);
		 }
	}
	public function resize_image($new_width, $new_height, $image, $width, $height) {
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		return $new_image;
	}
	public function resize_image_to_height($new_height, $image, $width, $height) {
		$ratio = $new_height / $height;
		$new_width = $width * $ratio;
		return $this->resize_image($new_width, $new_height, $image, $width, $height);
	}
}