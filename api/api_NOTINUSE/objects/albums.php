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
						$qry = "INSERT INTO dp_albums (`ID`, `IMAGE_THUMB_URL`, `ALBUM_FOLDER_TITLE`, `ALBUM_DISPLAY_TITLE`,`ALBUM_DESC`, `UPLOADED_DATE`, `STATUS`, `IS_DP_ALBUMS` )  VALUES 
						(NULL, '" . $postData->IMAGE_THUMB_URL . "', '" . trim($postData->ALBUM_FOLDER_TITLE) . "', '" . trim($postData->ALBUM_DISPLAY_TITLE) . "', '" . addslashes($postData->DESC) . "', '" . $postData->UPLOADED_DATE . "','" . $postData->STATUS . "', 'N')";
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
	public function getAlbumsDetails($postData){
		$sql = "SELECT *  
				 FROM `dp_albums` 
				 WHERE ID = " . $postData->albumId;
		$chkResult = $this->db->get_one_record($sql);	
		return $chkResult;		 
	}
	public function updatePhotoStatus ($postData){
		$updatePtr = $this->db->set_multiple_fields('dp_albums_photos', 'PHOTO_STATUS = "' . $postData->PHOTO_STATUS . '"', 'ID = "' . $postData->ID . '"');
		if($updatePtr > 0){
			return true;
		}else{
			return false;
		}		
	}
	public function getAlbumsList($postData){
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
				LEFT JOIN dp_albums_photos ON dp_albums.ID = dp_albums_photos.ALBUM_ID ";
		if($postData->DISPLAY == 'FE'){
			$sql .= " WHERE dp_albums.STATUS = 'Y'";
		}
		$sql .= " GROUP BY dp_albums.ID,dp_albums.`ALBUM_FOLDER_TITLE`,dp_albums.`ALBUM_DISPLAY_TITLE` ORDER BY dp_albums.UPLOADED_DATE DESC";
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
			$qry = "INSERT INTO dp_albums_photos (`ID`, `ALBUM_ID`, `IMAGE_THUMB_URL`,`UPLOADED_DATE`,`PHOTO_STATUS` )  VALUES 
			(NULL, '" . $albumId . "', '" . trim($fileName) . "','" . $currDate . "','Y')";
			$ptr = 	$this->db->get_sql_exec($qry);
		}
	}
	public function getAlbumsPhotosList($postData){
		$result = array();
		// select query
		// $sql = "SELECT * FROM dp_albums WHERE ID = " . $postData->ALBUM_ID . " AND ALBUM_FOLDER_TITLE = '" . $postData->ALBUM_FOLDER_TITLE . "'";
		$sql = "SELECT * FROM dp_albums WHERE ID = " . $postData->ALBUM_ID;
		if($postData->DISPLAY == 'FE'){
			$sql .= " AND STATUS = 'Y'";
		}
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows == 1){
			$chkResult = $this->db->get_one_record($sql);
			$result[] = $chkResult['ALBUM_DISPLAY_TITLE'];
			$sql = "SELECT * FROM dp_albums_photos WHERE ALBUM_ID = " . $postData->ALBUM_ID;
			if($postData->DISPLAY == 'FE'){
				$sql .= " AND PHOTO_STATUS = 'Y'";
			}
			$sql .= " ORDER BY ID DESC";
			$result[] = $this->db->get_multiple_tables_records($sql);
			// return values from database
		}
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
					$this->optimizeImageAfterUpload($albumFolderThumbnailPath . "/" . $_FILES["file"]["name"], 200, 80);
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}

	public function uploadAlbumPhotos(){
		ini_set('max_execution_time', '300');
		$this->cors();
		$albumFolderPath = HTDOCS_PATH . '/images/albums/' . $_REQUEST['albumIntTitle'];
		$tmpMsg = '';
		$albumId = $_REQUEST['albumId'];
		$newFileName = date('Ymdhms') . $_FILES["myFiles"]['name'];
		if(move_uploaded_file($_FILES["myFiles"]["tmp_name"], $albumFolderPath . "/" . $newFileName)){
			$this->optimizeImageAfterUpload($albumFolderPath . "/" . $newFileName, 800, 80);
			$this->addDbRecordsOfPhotosInAlbum($albumId,$newFileName);
			$tmpMsg = array($newFileName . ' Success');
		}else{
			$tmpMsg = array($newFileName . ' Failed');
		}
		$retArr = array(true,$tmpMsg);
		return $retArr;
	}
	public function downloadDPAlbumThumbnail() {
		ini_set('max_execution_time', '300');
		$str = "SELECT
					VIDEO_ID
				FROM
					dp_yt_videos_master WHERE VIDEO_ADDED_DP_ALBUM = 'N'";
		$result = $this->db->get_multiple_tables_records($str);
		$lowResVideoThumb = array('wnH4OxncJ08', '516KGrCCMUU','P4JozCeA2gM');
		if(count($result) > 0){
			foreach($result as $rec ) {
				$imageResType = "maxresdefault.jpg";
				if (in_array($rec['VIDEO_ID'], $lowResVideoThumb)){
					$imageResType = "hqdefault.jpg";
				}
				$sql = "SELECT ID,  ALBUM_FOLDER_TITLE 
						 FROM `dp_albums` 
						 WHERE IS_DP_ALBUMS = 'Y'";
				$chkResult = $this->db->get_one_record($sql);
				$downloadFolderPath = HTDOCS_PATH . '/images/albums/' . $chkResult['ALBUM_FOLDER_TITLE'];
				$ytvideoThumbUrl = 'https://i.ytimg.com/vi/' . $rec['VIDEO_ID'] . '/' . $imageResType;
				$videoThumb = file_get_contents($ytvideoThumbUrl);
				$fp = $downloadFolderPath . '/' . $rec['VIDEO_ID'] . '.jpg';
				$fileName = $rec['VIDEO_ID'] . '.jpg';
				$downloaded = file_put_contents( $fp, $videoThumb );
				if($downloaded != false){
					$updatePtr = $this->db->set_multiple_fields('dp_yt_videos_master', 'VIDEO_ADDED_DP_ALBUM = "Y"', 'VIDEO_ID = "' . $rec['VIDEO_ID'] . '"');
				}
				$this->addDbRecordsOfPhotosInAlbum($chkResult['ID'],$fileName);
			}
		}
		return true;
	}
	public function getReactCarouselTypeSetting(){
		$result = array();
		$sql = "SELECT * FROM dp_yt_display_settings WHERE DISPLAY_NAME = 'ALBUM_CAROUSEL_TYPE'";

		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows == 1){
			$chkResult = $this->db->get_one_record($sql);
			return $chkResult;
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
	public function optimizeImageAfterUpload($fileName, $imageHeight, $quality) {
		list($width, $height, $type) = getimagesize($fileName);
		$old_image = $this->load_image($fileName, $type);
		$image_height_fixed = $this->resize_image_to_height($imageHeight, $old_image, $width, $height,$fileName);
		$this->save_image($image_height_fixed, $fileName, $quality);
	}	

	/*public function resize_image_to_height($imageHeight, $image, $width, $height) {
		if($imageHeight > $height){
			$new_height = $height;
			$new_width = $width;
		}else{
			$new_width = $this->customRatio($imageHeight,$width, $height);
			$new_height = $imageHeight;
		}
		return $this->resize_image($new_width, $new_height, $image, $width, $height);
	}*/
	
	public function resize_image_to_height($imageHeight, $image, $width, $height,$fileName) {
		if($imageHeight > $height){
			$new_height = $height;
			$new_width = $width;
		}else{
			$exif = exif_read_data($fileName);
			if (!empty($exif['Orientation'])) {
				switch ($exif['Orientation']) {
					case 6:
						$tmp = $height;
						$height = $width;
						$width = $tmp;
						break;
				}
			}
			$new_height = $imageHeight;
			$new_width = round(($width / $height) * $imageHeight, 2);			
			if (!empty($exif['Orientation'])){
				if($exif['Orientation'] == 3){
					$image = imagerotate($image, 180, 0);
				}					
				if($exif['Orientation'] == 6){
					$image = imagerotate($image, -90, 0);
				}
			}
		}
		return $this->resize_image($new_width, $new_height, $image, $width, $height);
	}	
	public function customRatio_not_inuse($imageHeight, $width, $height){
		$newWidth = (($width / $height) * $imageHeight);
		return $newWidth;
	}	
	public function fit_box_NOT_IN_USE($box = 200, $x = 100, $y = 100)
	{
		$scale = min($box / $x, $box / $y, 1);
		return array(round($x * $scale, 0), round($y * $scale, 0));
	}	
	public function resize_image($new_width, $new_height, $image, $width, $height) {
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		return $new_image;
	}	
	
}