<?php
include_once '../config/database.php';
class Video{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }


	// read products with pagination
	public function addEditVideo($postUrl){
		//print 'here ->';print_r($postUrl);die;
		$videoId = str_replace("https://www.youtube.com/watch?v=","",$postUrl);
		$videoId = str_replace("https://youtube.com/watch?v=","",$videoId);
		$videoId = str_replace("https://m.youtube.com/watch?v=","",$videoId);
		$videoId = str_replace("https://youtu.be/","",$videoId);
		$result = $this->oneTimeApiHitForGettingMissingVideosInDB(array($videoId));
		return $result;
	}
	public function addEditMultipleVideos($postData){
		$result = $this->oneTimeApiHitForGettingMissingVideosInDB($postData->videoArr);
		return $result;
	}
	public function oneTimeApiHitForGettingMissingVideosInDB($missingVideosArr){
		$apiMsg = '';
		//CALLING API FOR MISSING VIDEOS
		foreach($missingVideosArr as $key => $val){
			$data['videoId'] = $val;
			//CALLING API FOR VIDEO DETAILS
			$videoDetails = file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $data['videoId'] .'&key=' . DP_YT_API_KEY);
			$videoDetails = json_decode($videoDetails);
			$videoDetails = $this->objectToArray($videoDetails);			
			$data['videoURL'] = "https://www.youtube.com/watch?v=" . $data['videoId']; 
			$data['videoTitle'] = $videoDetails['items'][0]['snippet']['title'];
			$data['videoThumbnailUrl'] = $videoDetails['items'][0]['snippet']['thumbnails']['medium']['url'];
			$data['videoDuration'] = 10.00;
			$data['videoDescription'] = $videoDetails['items'][0]['snippet']['description'];	
			$data['videoPublishDate'] =	$videoDetails['items'][0]['snippet']['publishedAt'];				
			$data['channelId'] = $videoDetails['items'][0]['snippet']['channelId'];

			//CALLING API FOR VIDEO STATISTICS
			$videoStatistics = file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=statistics&id=' . $data['videoId'] .'&key=' . DP_YT_API_KEY);
			$videoStatistics = json_decode($videoStatistics);
			print '<pre>';print_r($videoStatistics);
			$videoStatistics = $this->objectToArray($videoStatistics);
			$data['videoViews'] = $videoStatistics['items'][0]['statistics']['viewCount'];
			$data['videoComments'] = $videoStatistics['items'][0]['statistics']['commentCount'];
			$data['videoLikes'] = $videoStatistics['items'][0]['statistics']['likeCount'];
			$disLikes = $videoStatistics['items'][0]['statistics']['dislikeCount'];
			$totalLikeDislike = $data['videoLikes'] + $disLikes;
			if($data['videoLikes'] > 0){
				$data['videoLikesPercent'] = ($data['videoLikes'] * 100) / $totalLikeDislike;
				$data['videoLikesPercent'] = number_format($data['videoLikesPercent'],0);
			}else{
				$data['videoLikesPercent'] = 100;
			}
			//print '<pre>';print_r($videoDetails);die;
			if($data['channelId'] === 'UCg22-16kmYWZTUQF9wVkqFA'){
				$this->updateYtVideosDataToTable($data);
				$apiMsg = 'Done';
			}else{
				$apiMsg = 'Video not associated with Delicious Pakwan';
			}
		}
		return $apiMsg;
	}
	function updateYtVideosDataToTable($data){
		$sql = "SELECT ID  
				FROM `dp_yt_videos_master` 
				WHERE VIDEO_ID = '" . $data['videoId'] . "'"; //die;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		$actionTaken = $data['videoTitle'] . ' <=====> ';
		if($chkSqlRows == 0)
		{
			$qry = "INSERT INTO dp_yt_videos_master (`ID`, `VIDEO_ID`, `VIDEO_TITLE`, `VIDEO_DESC`, `VIDEO_YT_URL`, `VIDEO_THUMB_URL`, `VIDEO_PUBLISH_DATE`, `VIDEO_DURATION`, `VIDEO_VIEWS`, `VIDEO_COMMENTS`, `VIDEO_LIKES`, `VIDEO_LIKE_PERCENT`, `VIDEO_DP_URL` )  VALUES 
			(NULL, '" . $data['videoId'] . "', '" . $data['videoTitle'] . "', '" . addslashes($data['videoDescription']) . "',  '" . $data['videoURL'] . "', '" . $data['videoThumbnailUrl'] . "', '" . str_replace(' Published','',$data['videoPublishDate']) . "', '" . $data['videoDuration'] . "', '" . $data['videoViews'] . "', '" . $data['videoComments'] .  "', '" . $data['videoLikes'] .  "', '" . $data['videoLikesPercent'] . "', '" . $this->buildDpWatchUrlForDb('video',$data['videoId'],$data['videoTitle']) . "')";
			$ptr = 	$this->db->get_sql_exec($qry);
			$actionTaken .= 'INSERTED';
		}
		if($chkSqlRows == 1)
		{
			$updatePtr = $this->db->set_multiple_fields('dp_yt_videos_master', 'VIDEO_TITLE = "' . $data['videoTitle'] . '",VIDEO_DESC = "' . addslashes($data['videoDescription']) . '",VIDEO_THUMB_URL = "' . $data['videoThumbnailUrl'] . '",VIDEO_VIEWS = "' . $data['videoViews'] . '",VIDEO_COMMENTS = "' . $data['videoComments'] . '",VIDEO_LIKES = "' . $data['videoLikes'] . '",VIDEO_LIKE_PERCENT = "' . $data['videoLikesPercent'] . '",VIDEO_DP_URL = "' . $this->buildDpWatchUrlForDb('video',$data['videoId'],$data['videoTitle']) . '"', 'VIDEO_ID = "' . $data['videoId'] . '"');
			$actionTaken .= 'UPDATED';
		}
		//return $actionTaken;		
	}
	function buildDpWatchUrlForDb($type, $videoorplaylistId,$videoorplaylistTitle){
		$customDpUrl = '';
		switch($type)
		{
			case 'video':
				$customDpUrl = $this->getBuildURLAccordingToType($type, $videoorplaylistId,$videoorplaylistTitle);
			break;
			case 'playlistDetails':
				$customDpUrl = $this->getBuildURLAccordingToType($type, $videoorplaylistId,$videoorplaylistTitle);
			break;						
			default:
				$customDpUrl = '/';
			break;
		}
		return $customDpUrl;				
	}
	function getBuildURLAccordingToType($type,$videoorplaylistId,$videoorplaylistTitle) {
		$tmp = $videoorplaylistTitle;
		$tmp = str_replace(" ", "-", $tmp);
		$tmp = str_replace(' ', '', $tmp);
		$tmp = preg_replace("/\s+/", "", $tmp);
		$tmp = str_replace("|", "_",$tmp);
		$tmp = preg_replace('/[^a-zA-Z0-9-_]/','', strtolower($tmp));
		
		$tmp = preg_replace('/-+/', '-', $tmp);
		$pos = strpos($tmp, '_');
		if ($pos == 1) {
			$tmp = substr_replace($tmp, '', $pos, 1);
		}
		
		$pos = strpos($tmp, '_-');
		if ($pos == 1) {
			$tmp = substr_replace($tmp, '', 0, 2);
		}
		
		$tmp = str_replace("-_-", "_",$tmp);
		$tmp = str_replace("__-", "_",$tmp);
		$tmp = str_replace("/-", "",$tmp);
		
		$tmp = str_replace("--", "",$tmp);
		$tmp = str_replace("slashplaceholder", "/",$tmp);
		$tmp = str_replace("/_", "/",$tmp);

		//$tmp = str_replace("_", "/",$tmp);
		$tmp = '/' . $type . '/' . $videoorplaylistId . '/'. $tmp . '.html'; 
		return $tmp;
	}	
	function objectToArray($data) {
		if (is_array($data) || is_object($data))
		{
			$result = array();
			foreach ($data as $key => $value)
			{
				$result[$key] = $this->objectToArray($value);
			}
			return $result;
		}
		return $data;
	}	
	function updateVideoKeywordsById($postData) {
		$data= json_decode(json_encode($postData), true);
		foreach($data as $rec) {
			$updatePtr = $this->db->set_multiple_fields('dp_yt_videos_master', 'VIDEO_SEARCH_KEYWORDS = "' . $rec['videoSearchKeywords'] . '"', 'VIDEO_ID = "' . $rec['videoId'] . '"');
		}
		return true;
	}
}