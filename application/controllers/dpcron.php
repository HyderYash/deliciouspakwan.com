<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}
class Dpcron extends MY_Controller {

	public function __construct() {
        parent::__construct();
	}
	// BELOW FUNCTIONS ARE BEING USED ONLY FOR API CALLS, PLEASE DO NOT MODIFY
	// =======================================================================

	public function downloadVideoThumbnailsFromYoutube(){
		 // is_cli_request() is provided by default input library of codeigniter
        if($this->input->is_cli_request())
        { 
			$videoIdArr = $this->sqlModel->getVideoIds();
			$i = 0;
			echo "Processing Start..." . "\n";
			foreach($videoIdArr as $video){
				$chkStr = "SELECT ID  
				FROM dp_yt_thumbnails 
				WHERE VIDEO_ID = '" . $video['VIDEO_ID'] . "'";
				$chkSqlPtr = $this->db->get_sql_exec($chkStr);
				$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
				if($chkSqlRows == 0){				
					$ytvideoThumbUrl = 'https://i.ytimg.com/vi/' . $video['VIDEO_ID'] . '/mqdefault.jpg';
					$videoThumb = file_get_contents($ytvideoThumbUrl); 
					$fp = HTDOCS_PATH . '/images/videothumbnails/' . $video['VIDEO_ID'] .'.jpg'; 
					$downloded = file_put_contents( $fp, $videoThumb );
					echo $i . "====>" . $video['VIDEO_ID'] .'.jpg is Downloaded' . "\n\n";
					$qry = "INSERT INTO dp_yt_thumbnails (`ID`, `VIDEO_ID`,`THUMBNAIL_PATH`)  VALUES 
					(NULL, '" . $video['VIDEO_ID'] . "', '" . str_replace(HTDOCS_PATH, '', $fp) . "')";
					$ptr = 	$this->db->get_sql_exec($qry);					
				}
				$i++;
			}
			echo "Downloads completed..." . "\n";
		}
        else
        {
            echo "You dont have access";
        }		
	}
	
	public function myDpCron($action='Ram'){
        // is_cli_request() is provided by default input library of codeigniter
        if($this->input->is_cli_request())
        {            
			$data['tmpMsg'] = $this->sqlModel->updateTmpTable($action);
			echo $data['tmpMsg'];
        }
        else
        {
            echo "You dont have access";
        }	

	}
	public function ytApiRun(){
        // is_cli_request() is provided by default input library of codeigniter
        if($this->input->is_cli_request())
        {	
			$actionArr = array('ytApi','ytPlaylists','ytPlaylistvideos','ytCommentDetails');
			$taskStatus = 'API Processing Starts.....';
			foreach($actionArr as $action)
			{
				if($action == 'viewSource'){
					require_once HTDOCS_PATH . '/simple_html_dom.php';
					$buffer = file_get_contents(HTDOCS_PATH . "/tmpytdata.txt");
					$html = '<<<html ' . $buffer . ' html';
					$dom = str_get_html($html);
					$data['tmpArray'] = array();
					foreach($dom->find('#row-container') as $rowContainer)
					{		
						$data['videoId'] = $rowContainer->find('a[class=style-scope ytcp-video-list-cell-video remove-default-style]',0)->href;
						$data['videoId'] = str_replace('/video/','',$data['videoId']);
						$data['videoId'] = str_replace('/edit','',$data['videoId']);
						$data['videoURL'] = "https://www.youtube.com/watch?v=" . $data['videoId']; 
						$data['videoTitle'] = $rowContainer->find('#video-title',0);
						$data['videoTitle'] = $data['videoTitle']->plaintext;
						//$data['videoThumbnailUrl'] = $rowContainer->find('img[id=img-with-fallback]',0)->src;
						$data['videoThumbnailUrl'] = "https://i.ytimg.com/vi/" . $data['videoId'] . "/mqdefault.jpg";
						$data['videoDuration'] = $rowContainer->find('div[class=label style-scope ytcp-thumbnail]',0);
						$data['videoDuration'] = $data['videoDuration']->plaintext;
						$data['videoDescription'] = $rowContainer->find('div[class=style-scope ytcp-video-list-cell-video description]',0);
						$data['videoDescription'] = $data['videoDescription']->innertext;
						$data['videoViews'] = $rowContainer->find('div[class=style-scope ytcp-video-row cell-body tablecell-views sortable right-align]',0);
						$data['videoViews'] = $data['videoViews']->plaintext;
						$data['videoComments'] = $rowContainer->find('a[class=style-scope ytcp-video-row remove-default-style]',0);
						$data['videoComments'] = $data['videoComments']->plaintext;
						$data['videoLikes'] = $rowContainer->find('div[class=style-scope ytcp-video-row likes-label]',0);
						$data['videoLikes'] = $data['videoLikes']->plaintext;
						$data['videoLikesPercent'] = $rowContainer->find('div[class=style-scope ytcp-video-row percent-label]',0);
						$data['videoLikesPercent'] = $data['videoLikesPercent']->plaintext;
						$data['videoPublishDate'] = $rowContainer->find('div[class=style-scope ytcp-video-row cell-body tablecell-date sortable column-sorted]',0);
						$data['videoPublishDate'] = $data['videoPublishDate']->plaintext;
						$data['tmpArray'][] = $this->sqlModel->updateYtVideosDataToTable($data);
					}		
				}
				if($action == 'ytApi'){
					//CALLING API FOR CHANNEL DETAILS
					$channelDetails = file_get_contents('https://www.googleapis.com/youtube/v3/search?key=' . DP_YT_API_KEY . '&channelId=UCg22-16kmYWZTUQF9wVkqFA&part=snippet,id&order=date&maxResults=5');
					$channelDetails = json_decode($channelDetails);
					$channelDetails = $this->objectToArray($channelDetails);
					foreach($channelDetails['items'] as $key => $val){
						if(isset($val['id']['videoId'])){
							$data['videoId'] = $val['id']['videoId'];
							$data['videoURL'] = "https://www.youtube.com/watch?v=" . $data['videoId']; 
							$data['videoTitle'] = $val['snippet']['title'];
							$data['videoThumbnailUrl'] = $val['snippet']['thumbnails']['medium']['url'];
							$data['videoDuration'] = 10.00;
							$data['videoDescription'] = $val['snippet']['description'];	
							$data['videoPublishDate'] =	$val['snippet']['publishedAt'];				
							//CALLING API FOR VIDEO DETAILS
							$videoDetails = file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=statistics&id=' . $data['videoId'] .'&key=' . DP_YT_API_KEY);
								$videoDetails = json_decode($videoDetails);
								$videoDetails = $this->objectToArray($videoDetails);
								$data['videoViews'] = $videoDetails['items'][0]['statistics']['viewCount'];
								$data['videoComments'] = $videoDetails['items'][0]['statistics']['commentCount'];
								$data['videoLikes'] = $videoDetails['items'][0]['statistics']['likeCount'];
								$disLikes = $videoDetails['items'][0]['statistics']['dislikeCount'];
								$data['videoLikesPercent'] = 100;
								$data['tmpArray'][] = $this->sqlModel->updateYtVideosDataToTable($data);
						}
					}
					$taskStatus .= $action . ' Completed....';
				}
				if($action == 'ytPlaylists') { 
					//CALLING API FOR PLAYLIST DETAILS
					$playlistDetails = file_get_contents('https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId=UCg22-16kmYWZTUQF9wVkqFA&key=' . DP_YT_API_KEY . '&maxResults=500');
					$playlistDetails = json_decode($playlistDetails);
					$playlistDetails = $this->objectToArray($playlistDetails);
					foreach($playlistDetails['items'] as $key => $val){ 
						$data['playlistId'] = $val['id'];
						$data['playlistTitle'] = $val['snippet']['title'];
						$data['playlistDesc'] = $val['snippet']['description'];
						$data['playlistThumbURL'] = $val['snippet']['thumbnails']['medium']['url'];
						$data['playlistPublishDate'] = $val['snippet']['publishedAt'];
						$data['playlistYtUrl'] = 'https://www.youtube.com/playlist?list=' . $val['id'];
						$data['tmpArray'][] = $this->sqlModel->updateYtPlaylistDataToTable($data);
					}
					$taskStatus .= $action . ' Completed....';
				}
				if($action == 'ytPlaylistvideos') { 
					//CALLING API FOR PLAYLIST DETAILS
					$playlistIds = $this->sqlModel->getPlaylistIds();
					foreach($playlistIds as $playlistId) {
						// print $playlistId['PLAYLIST_ID']  . "<br>"; 
						$playlistVideoDetails = file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=100&playlistId=' . $playlistId['PLAYLIST_ID'] . '&key=' . DP_YT_API_KEY);
						$playlistVideoDetails = json_decode($playlistVideoDetails);
						$playlistVideoDetails = $this->objectToArray($playlistVideoDetails);
						foreach($playlistVideoDetails['items'] as $key => $val){ 
							$data['playlistId'] = $val['snippet']['playlistId'];
							$data['videoId'] = $val['snippet']['resourceId']['videoId'];
							$data['videoPos'] = $val['snippet']['position'];
							$data['tmpArray'][] = $this->sqlModel->updateYtPlaylistVideosDataToTable($data);
						}
					}
					$taskStatus .= $action . ' Completed....';				
				}
				if($action == 'ytCommentDetails') { 
					//CALLING API FOR COMMENTS DETAILS
					$allVideoIds = $this->sqlModel->getAllVideoIds();
					foreach($allVideoIds as $videoId) {
						$allCommentsForVideoId = file_get_contents('https://www.googleapis.com/youtube/v3/commentThreads?key=' . DP_YT_API_KEY . '&textFormat=plainText&part=snippet&videoId=' . $videoId['VIDEO_ID'] . '&maxResults=500');
						$allCommentsForVideoId = json_decode($allCommentsForVideoId);
						$allCommentsForVideoId = $this->objectToArray($allCommentsForVideoId);
						foreach($allCommentsForVideoId['items'] as $key => $val){ 
							$data['commentId'] = $val['id'];
							if(isset($val['snippet']['topLevelComment']['snippet']['authorChannelId']['value'])){
								$data['authorChannelId'] = $val['snippet']['topLevelComment']['snippet']['authorChannelId']['value'];
							}else{
								$data['authorChannelId'] = '#';
							}
							
							$data['comment'] = $val['snippet']['topLevelComment']['snippet']['textOriginal'];
							$data['authorName'] = $val['snippet']['topLevelComment']['snippet']['authorDisplayName'];
							$data['authorIcon'] = $val['snippet']['topLevelComment']['snippet']['authorProfileImageUrl'];
							$data['authorChannelUrl'] = $val['snippet']['topLevelComment']['snippet']['authorChannelUrl'];
							$data['commentLikeCount'] = $val['snippet']['topLevelComment']['snippet']['likeCount'];
							$data['commentPublished'] = $val['snippet']['topLevelComment']['snippet']['publishedAt'];
							$data['videoId'] = $videoId['VIDEO_ID']; 
							$data['tmpArray'][] = $this->sqlModel->updateYtCommentsDataToTable($data);
						}
					}
					$taskStatus .= $action . ' Completed....';				
				}
			}
			echo $taskStatus;
		}
        else
        {
            echo "You dont have access";
        }		
	}
	function objectToArray($data)
	{
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
	// ABOVE FUNCTIONS ARE BEING USED ONLY FOR API CALLS, PLEASE DO NOT MODIFY
	// =======================================================================	
}

/* End of file dpcron.php */
/* Location: ./application/controllers/dpcron.php */	