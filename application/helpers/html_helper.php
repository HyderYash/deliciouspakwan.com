<?php
if (!isset($_SESSION)) { 
  session_start();
}
/* --- Tiny Helper Function -Start- */
function _getHrLine(){
	$tmp = '';
	$tmp .= '<div id="yt_dp_button" style="display:flex;">';
		$tmp .= '<hr style="border: 0;height: 2px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);width:99%;" align="center">';
	$tmp .= '</div>';
	return $tmp;
}
function _getCategoryHeading($title='Delicious Pakwan', $size='big',$extraCss='',$titleType='h2'){
	$tmp = '';
	if($size == 'big'){
		$style = 'padding-left:35px;' . $extraCss;
	}else{
		$style = $extraCss;
	}
	$tmp .= '<' . $titleType . ' class="manage-big-screen" style="' . $style . '"><span style="border-radius: 5px;padding:5px;font-size:16px;background: linear-gradient(120deg, #8fe7e39e 0%, #ebcde7 100%);">' . $title . '</span>' . '</' . $titleType . '>';

	return $tmp;
}
function _getYtVideoImage($videoId,$hrefUrl,$title,$imgUrl ){
	$videoId = str_replace('https://i.ytimg.com/vi/', '', $videoId);
	$videoId = str_replace('/mqdefault.jpg', '', $videoId);
	//$videoId = 'B-SjNTTDSf4';
	//$imgUrl = '/images/videothumbnails/' . trim($videoId) . '.jpg';
	$tmp = '';
	$tmp .= '<a href="' . $hrefUrl .'" title="' . $title .'">';
	$tmp .= '<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="' . $imgUrl .'" class="img" alt="' . $title . '"></a>';
	//$tmp .= '<img src="' . $imgUrl .'" class="img" alt="' . $title . '"></a>';
	return $tmp;
}
function _getYtSubscriptionLinkBox($type='round',$hrefUrl,$title,$txt='Watch on YouTube' ){
	$tmp = '';
	if($type == 'round'){
		$tmp .= '<a href="' . $hrefUrl . '" title="' . $title .'" target="_blank"><button type="button" class="btn btn-blog">' . $txt . '</button></a>';
	}
	if($type == 'button'){
		$tmp .= '<a href="https://www.youtube.com/c/DeliciousPakwan?sub_confirmation=1" title="' . $txt .'" target="_blank"><button type="button" class="btn post-btn">' . $txt . ' &nbsp; <i class="fas fa-arrow-right"></i></button></a>';
	}
	return $tmp;
}
function _getYtVideoLinkBox($type='round',$hrefUrl,$title,$txt='Watch on YouTube' ){
	$tmp = '';
	if($type == 'round'){
		$tmp .= '<a href="' . $hrefUrl . '" title="' . $title .'" target="_blank"><button type="button" class="btn btn-blog">' . $txt . '</button></a>';
	}
	if($type == 'button'){
		$tmp .= '<a href="' . $hrefUrl . '" title="' . $title .'" target="_blank"><button type="button" class="btn post-btn">' . $txt . '</button></a>';
	}
	return $tmp;
}
function _getDpVideoLinkBox($type='round', $hrefUrl,$title,$txt='Watch here' ){
	$tmp = '';
	if($type == 'round'){
		$tmp .= '<a href="' . $hrefUrl . '" title="' . $title .'"><button type="button" class="btn btn-blog">' . $txt . '</button></a>';
	}
	if($type == 'button'){
		$tmp .= '<a href="' . $hrefUrl . '" title="' . $title .'"><button type="button" class="btn post-btn">' . $txt . '</button></a>';
	}	
	return $tmp;
}
function _getMoveToTopButton($recId,$type='button'){
	$tmp = '';
		if($type =='button'){
			$css = 'margin-top:5px !important;';
		}else{
			$css = 'margin-left: 5px;margin-top: 13px;';
		}	
		$tmp .= '<a href="#" title="Go to Top" onclick="javascript:moveToTop(' . $recId .');"><div class="move-up custom-class-mar-left" style="' . $css . '">';

		$tmp .= '<div style="" class="sprite_up-arrow-blue-bg"></div>';
	$tmp .= '</div></a>';
	return $tmp;
}
function _getvideoStatistics($date, $views, $likes, $comments, $likepercent) {
	$tmp = '';
	$video_publish_date = date_create($date);
	$tmp.= '<div style="display:flex;margin-top: 10px !important;margin-bottom: 10px !important; font-size: 0.7rem !important;line-height: 16px;">';
		$tmp .= '<div class="sprite_calender" style="margin-right:5px;"></div>';
		$tmp .= '<div style="margin-right:14px;">' . date_format($video_publish_date,"d/m/Y") . '</div>';
		$tmp .= '<div class="sprite_eye" style="margin-right:5px;"></div>';
		$tmp .= '<div style="margin-right:14px;">' . $views . '</div>';
		$tmp .= '<div class="sprite_like" style="margin-right:5px;"></div>';
		$tmp .= '<div style="margin-right:14px;">' . $likes . '</div>';
		$tmp .= '<div class="sprite_comment" style="margin-right:5px;"></div>';
		$tmp .= '<div style="margin-right:14px;">' . $comments . '</div>';
		$tmp .= '<div class="sprite_heart" style="margin-right:5px;"></div>';
		$tmp .= '<div>' . $likepercent . '%</div>';
	$tmp .= '</div>';
	return $tmp;
}
function _getPlaylistsStatistics($date,$dp_url) {
	$CI =& get_instance();
	$tmp = '';
	$playlist_publish_date = date_create($date);
	$tmp.= '<div style="display:flex;margin-top: 10px !important;margin-bottom: 10px !important; font-size: 0.7rem !important;line-height: 16px;">';
		$tmp .= '<div class="sprite_calender" style="margin-right:5px;"></div>';
		$tmp .= '<div style="margin-right:14px;">' . date_format($playlist_publish_date,"d/m/Y") . '</div>';
		$tmp .= '<div class="sprite_eye" style="margin-right:5px;"></div>';
		$tmp .= '<div style="margin-right:14px;"><a href="' . $dp_url . '" title="">View all the videos in this Playlist</a></div>';
	$tmp .= '</div>';	
	return $tmp;
}
function _getYtVideoTitle($hrefUrl,$title, $showType='full',$titleType='h3'){
	$tmp = '';
	if($showType != 'full'){
		//$title = substr($title, 0, 80) . '...';
		$title = str_replace ('| Delicious Pakwan', '', $title);
	}
	
	$tmp .= '<' . $titleType . '>' . '<a href="' . $hrefUrl .'" title="' . $title .'">' . $title . '</a>' . '</' . $titleType . '>';
	return $tmp;
}
function _getVideoSmallDesc($desc,$title,$hrefUrl){
	$tmp = '';
	$tmp .= '<p style="font-size:12px !important;color:darkblue;margin-top:5px;padding-left:2px;padding-right:2px;">';
		if($desc == ''){
			$tmp .= 'Here you can find Tasty ' . $title . '  which you can make very easily...';
		}else{
			$tmp .= substr($desc, 0, 200) . '...<a style="font-size:12px;font-weight:bold;" href="' . $hrefUrl . '" title="' . $title . '">full description</a>';
		}
	$tmp .= '</p>';
	return $tmp;
}

function _getVideoFullDesc($desc,$title,$hrefUrl){
	$tmp = '';
	$tmp .= '<p style="font-size:12px !important;padding-left:5px;">';
		if($desc == ''){
			$tmp .= 'Here you can find Tasty ' . $title . '  which you can make very eas...';
		}else{
			$tmp .= nl2br($desc);
				$extaSuffix = '
				Thanks for watching this video..!!
			
				Please watch our other Delicious Recipes Playlists on YouTube:
				Veg Snacks Playlist Link: https://www.youtube.com/watch?v=CLK9sbN_Yu0&list=PL-kl_Bdh6Wn4_89okhcq1czADt1CKmKFS
				Curry Recipes Playlist Link: https://www.youtube.com/watch?v=JrLgJvqk-fE&list=PL-kl_Bdh6Wn6xXnOms0q5MXCKrJ6XbbrR
				Sweet Dishes Playlist Link: https://www.youtube.com/watch?v=QmKDxIha5tE&list=PL-kl_Bdh6Wn5FANQthlfPz_Z0EaTAsD8P
				Chinese Dishes Playlist Link: https://www.youtube.com/watch?v=oV7hWvdI1Xg&list=PL-kl_Bdh6Wn6sxYOkV9REkpEWduHfvzyW
				Homemade Recipes Playlist Link: https://www.youtube.com/watch?v=wnH4OxncJ08&list=PL-kl_Bdh6Wn50KQLkduT98cvGsCHdIwvS
				Nonveg Curry Playlist Link: https://www.youtube.com/watch?v=X66NFvp8hzQ&list=PL-kl_Bdh6Wn7wOO-fzJuEwCWjNhGOmOYw
				Summer Drinks Playlist Link: https://www.youtube.com/watch?v=i5SjDE-zK7M&list=PL-kl_Bdh6Wn7U9R4ZwB2hJO6guglFqvaR
				Healthy Food-Playlist Link: https://www.youtube.com/watch?v=YAc5Rnn-FnY&list=PL-kl_Bdh6Wn52SuxH6aUZ5glHIcQCTmUm
				Nonveg Snacks Playlist Link: https://www.youtube.com/watch?v=fynZILAO6fM&list=PL-kl_Bdh6Wn7A71zTOsD1rTj-mSxjRfr-

				Please support my channel by SUBSCRIBE to my channel and share my videos in your Social Network TimeLines.

				Don\'t Forget to Follow me on all Social Network,

				Website Link: http://www.deliciouspakwan.com
				Facebook Link: https://www.facebook.com/deliciouspakwan
				Instagram Link: https://www.instagram.com/deliciouspakwan
				Twitter Link: https://twitter.com/PakwanDelicious
				My Youtube Channel: https://bit.ly/3gJY3mQ				
				';
				
			//$tmp .= nl2br($extaSuffix);
			$tmp = preg_replace(
              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
              "<a href=\"\\0\" target=\"_blank\" style=\"font-size:12px !important;color:darkblue;\">\\0</a>", 
              $tmp);
			
		}
	$tmp .= '</p>';
	return $tmp;
}
function _getYtAuthorName($hrefUrl,$title ){
	$tmp = '';
	$tmp .= '<h3 style="font-size:14px !important;padding:5px;"><a href="' . $hrefUrl .'" title="' . $title .'" target="_blank">' . $title . '</a></h3>';
	return $tmp;
}
function _getAuthorComments($desc,$commentDate){
	$tmp = '';
	$date=date_create($commentDate);
	$tmp .= '<p style="font-size:13px !important;padding-left:5px;">';
		$tmp .= date_format($date,"d-M-Y") . ': ';
		$tmp .= $desc;
	$tmp .= '</p>';
	return $tmp;
}
function _getYtIframeEmbedBox($videId){
	$tmp = '';
	$tmp .= '<iframe id="playlistVideoAutoStart" src="https://www.youtube.com/embed/' . $videId . '?autoplay=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
	return $tmp;
}

/* --- Tiny Helper Function -End- */

function displaySliderCardForRecentVideos($recentVideos){
	$CI =& get_instance();
	$tmp = '';
	$tmp .= '<section class="scroll_recipe fixed_height">';
		$tmp .= '<div class="blog">';
			$tmp .= '<div class="container">';
				$tmp .= '<div class="owl-carousel owl-theme blog-post" id="draw">';
					$i = 0;
					$randHOne = rand($i, count($recentVideos));
					foreach($recentVideos as $rec){
						$randomSiteUrl = $CI->sqlModel->createRandomSiteUrls('video',$rec);
						$tmp .= '<div class="blog-content">';
							$tmp .= _getYtVideoImage($rec['VIDEO_ID'],$randomSiteUrl,$rec['VIDEO_TITLE'],$rec['VIDEO_THUMB_URL']);
							$tmp .= '<div class="blog-title">';
							if($i == 0){
								$tmp .= _getYtVideoTitle($randomSiteUrl,$rec['VIDEO_TITLE'],'small', 'h1');
							}else{
								$tmp .= _getYtVideoTitle($randomSiteUrl,$rec['VIDEO_TITLE'],'small');
							}	
								$tmp .= _getVideoSmallDesc($rec['VIDEO_DESC'],$rec['VIDEO_TITLE'],$randomSiteUrl);
							$tmp .= '</div>';
							$tmp .= '<div style="display:flex;text-align:center;justify-content:center;">';
								$tmp .= _getYtVideoLinkBox('round',$rec['VIDEO_YT_URL'],$rec['VIDEO_TITLE']);
								$tmp .= _getDpVideoLinkBox('round',$randomSiteUrl,$rec['VIDEO_TITLE']);
							$tmp .= '</div>';
							$tmp .= '<div style="display:flex;text-align:center;justify-content:center;">';
								$tmp .= _getvideoStatistics($rec['VIDEO_PUBLISH_DATE'], $rec['VIDEO_VIEWS'], $rec['VIDEO_LIKES'], $rec['VIDEO_COMMENTS'], $rec['VIDEO_LIKE_PERCENT']);
							$tmp .= '</div>';
						$tmp .= '</div>';
						$i++;
					}
				$tmp .= '</div>';
				$tmp .= '<div class="owl-navigation">';
					$tmp .= '<span class=
					"owl-nav-prev"><div class="sprite_left-black-arrow"></div></span>';
					$tmp .= '<span class="owl-nav-next"><div class="sprite_black-right-arrow"></div></span>';
				$tmp .= '</div>';
			$tmp .= '</div>';
		$tmp .= '</div>';
	$tmp .= '</section>';
	return $tmp;	
}
function displaySliderCardForPlaylist($listOfPlaylist){
	$CI =& get_instance();
	$tmp = '';
	$tmp .= '<section class="scroll_recipe fixed_height">';
		$tmp .= '<div class="blog">';
			$tmp .= '<div class="container">';
				$tmp .= '<div class="owl-carousel owl-theme blog-post" id="draw">';
				if(is_array($listOfPlaylist) && count($listOfPlaylist) > 0) {
					$i = 0;
					$randHOne = rand($i, count($listOfPlaylist));					
					foreach($listOfPlaylist as $rec) {

						$randomSiteUrl = $CI->sqlModel->createRandomSiteUrls('playlistDetails',$rec);
						$tmp .= '<div class="blog-content" data-aos="fade-right" data-aos-delay="200">';
							$tmp .= _getYtVideoImage($rec['PLAYLIST_THUMB_URL'],$randomSiteUrl,$rec['PLAYLIST_TITLE'],$rec['PLAYLIST_THUMB_URL']);
							$tmp .= '<div class="blog-title" style="padding-bottom:14px !important;">';
							if($i == $randHOne){
								$tmp .= _getYtVideoTitle($randomSiteUrl,$rec['PLAYLIST_TITLE'] , '', 'h1');
							}else{
								$tmp .= _getYtVideoTitle($randomSiteUrl,$rec['PLAYLIST_TITLE']);
							}							
							$tmp .= _getVideoSmallDesc($rec['PLAYLIST_DESC'],$rec['PLAYLIST_TITLE'],$randomSiteUrl);
							$tmp .= '</div>';
							$tmp .= '<div style="display:flex;text-align:center;justify-content:center;">';
								$tmp .= _getYtVideoLinkBox('round',$rec['PLAYLIST_YT_URL'],$rec['PLAYLIST_TITLE']);
								$tmp .= _getDpVideoLinkBox('round',$randomSiteUrl,$rec['PLAYLIST_TITLE']);
							$tmp .= '</div>';
							$tmp .= '<div style="display:flex;justify-content:center;">';
								$tmp .= _getPlaylistsStatistics($rec['PLAYLIST_PUBLISH_DATE'],$randomSiteUrl);
							$tmp .= '</div>';
						$tmp .= '</div>';
						$i++;
					}
				} else {
					$tmp .= '<div style="height: 300px;" class="blog-content">';
					$tmp .= '<div class="blog-title" style="padding-bottom:14px !important;">Playlists are coming soon...</div>';
					$tmp .= '</div>';
				}
				$tmp .= '</div>';
				$tmp .= '<div class="owl-navigation">';
					$tmp .= '<span class=
					"owl-nav-prev"><div class="sprite_left-black-arrow"></div></span>';
					$tmp .= '<span class="owl-nav-next"><div class="sprite_black-right-arrow"></div></span>';
				$tmp .= '</div>';
			$tmp .= '</div>';
		$tmp .= '</div>';
	$tmp .= '</section>';
	return $tmp;	
}

function popularVideosSideBar($rec) {
	$CI =& get_instance();
	$randomSiteUrl = $CI->sqlModel->createRandomSiteUrls('video',$rec);
	$tmp = '';
		$tmp .= '<div class="post-content">';
			$tmp .= '<div class="post-image">';
				$tmp .= '<div>';
					$tmp .= _getYtVideoImage($rec['VIDEO_ID'],$randomSiteUrl,$rec['VIDEO_TITLE'],$rec['VIDEO_THUMB_URL']);
				$tmp .= '</div>';
				$tmp .= '<div class="post-info flex-row">';
					$tmp .= _getvideoStatistics($rec['VIDEO_PUBLISH_DATE'], $rec['VIDEO_VIEWS'], $rec['VIDEO_LIKES'], $rec['VIDEO_COMMENTS'], $rec['VIDEO_LIKE_PERCENT']);
				$tmp .= '</div>';
			$tmp .= '</div>';
			$tmp .= '<div class="post-title remove-pad">';
				$tmp .= _getYtVideoTitle($randomSiteUrl,$rec['VIDEO_TITLE'] . $CI->correctPattern("title", $rec['VIDEO_SEARCH_KEYWORDS']));
				$tmp .= _getVideoSmallDesc($rec['VIDEO_DESC'],$rec['VIDEO_TITLE'],$randomSiteUrl);
			$tmp .= '</div>';
			$tmp .= '<div style="display:flex;text-align:center;justify-content:center;">';
				$tmp .= _getYtVideoLinkBox('round',$rec['VIDEO_YT_URL'],$rec['VIDEO_TITLE']);
				$tmp .= _getDpVideoLinkBox('round',$randomSiteUrl,$rec['VIDEO_TITLE']);
				$tmp .= _getMoveToTopButton($rec['ID'],'round');
			$tmp .= '</div>';
			$tmp .= _getHrLine();
		$tmp .= '</div>';
	return $tmp;
}
function popularPlaylistsSideBar($rec) {
	$CI =& get_instance();
	$randomSiteUrl = $CI->sqlModel->createRandomSiteUrls('playlistDetails',$rec);
	$tmp = '';
		$tmp .= '<div class="post-content">';
			$tmp .= '<div class="post-image">';
				$tmp .= '<div>';
					$tmp .= _getYtVideoImage($rec['PLAYLIST_THUMB_URL'],$randomSiteUrl,$rec['PLAYLIST_TITLE'],$rec['PLAYLIST_THUMB_URL']);
				$tmp .= '</div>';
				$tmp .= '<div class="post-info flex-row">';
					$tmp .= _getPlaylistsStatistics($rec['PLAYLIST_PUBLISH_DATE'],$randomSiteUrl);
				$tmp .= '</div>';
			$tmp .= '</div>';
			$tmp .= '<div class="post-title remove-pad">';
				$tmp .= _getYtVideoTitle($randomSiteUrl,$rec['PLAYLIST_TITLE'] . $CI->correctPattern("title", ''));
				$tmp .= _getVideoSmallDesc($rec['PLAYLIST_DESC'],$rec['PLAYLIST_TITLE'],$randomSiteUrl);
			$tmp .= '</div>';
			$tmp .= '<div style="display:flex;text-align:center;justify-content:center;">';
				$tmp .= _getYtVideoLinkBox('round',$rec['PLAYLIST_YT_URL'],$rec['PLAYLIST_TITLE']);
				$tmp .= _getDpVideoLinkBox('round',$randomSiteUrl,$rec['PLAYLIST_TITLE']);
				$tmp .= _getMoveToTopButton($rec['ID'],'round');
			$tmp .= '</div>';
			$tmp .= _getHrLine();
		$tmp .= '</div>';
	return $tmp;
}
function _getListOfPlaylists($listOfPlaylist,$listOfNutritionFacts) {
	$CI =& get_instance();
	$tmp = '';	$i = 0;
	foreach($listOfPlaylist as $rec) {
		$videoDpUrl = $CI->sqlModel->createRandomSiteUrls('playlistDetails',$rec);
		$tmp .=  '<li class="list-items" data-aos="fade-left" data-aos-delay="100">';
			$tmp .=  '<a href="' . $videoDpUrl .'" title="' . $rec['PLAYLIST_TITLE'] .'">' . $rec['PLAYLIST_TITLE'] .'</a>';
			$tmp .=  '&nbsp;<span>&nbsp;(' . $rec['VIDEO_COUNT'] . ')</span>';
		$tmp .=  '</li>';		if($i == 4) {			$tmp .= _getListOfNutritionFact($listOfNutritionFacts);		}		$i++;
	}
	return $tmp;
}function _getListOfNutritionFact($listOfNutritionFacts) {	$tmp = '';	$tmp .= '<div class="category">';			$tmp .= _getCategoryHeading('Nutrition Facts Information', 'small');		$tmp .= '<table class="nutri_table">';		  foreach($listOfNutritionFacts as $rec) {		  $tmp .= '<tr>			<th class="nutri_th" colspan="2"><span class="nutri_item">' . $rec["FOOD_NAME"] . '</span> <span class="nutri_credit" align="right">Sources include: <a href="https://fdc.nal.usda.gov" target="_blank">USDA</a></span></th>		  </tr>';		  foreach($rec['FOOD_NUTRI'] as $nutri) {		  $tmp .= '<tr>			<td class="nutri_info">' . $nutri["NUTRIENT_NAME"] . '</td>			<td class="nutri_info" style="text-align:right !important;">' . $nutri["NUTRIENT_VALUE"] . ' ' . $nutri["NUTRIENT_UNIT"] . '</td>		  </tr>';		  }		   $tmp .= '<tr>			<th class="nutri_desc" colspan="2">' . $rec["FOOD_NUTRI"][0]["NUTRIENT_DESC"] . '</th>		  </tr>';		}  	$tmp .= '</table>	</div>';	return $tmp;}
function displayBigZoominCards($iframe='no',$rec,$forWhat='video') {
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$videoDpUrl = $CI->sqlModel->createRandomSiteUrls($forWhat,$rec);
	$tmp = '';
	$tmp .= '<div class="post-content">';
		$tmp .= '<div class="post-image">';
			$tmp .= '<div id="play">';
			if($iframe == 'no'){
					$tmp .= _getYtVideoImage($rec['VIDEO_ID'],$videoDpUrl,$rec['VIDEO_TITLE'],$rec['VIDEO_THUMB_URL']);
				}else{
					$tmp .= _getYtIframeEmbedBox($rec['VIDEO_ID']);
				}
			$tmp .= '</div>';
		$tmp .= '</div>';
		$tmp .= '<div class="post_publish">';
			$tmp .= _getvideoStatistics($rec['VIDEO_PUBLISH_DATE'], $rec['VIDEO_VIEWS'], $rec['VIDEO_LIKES'], $rec['VIDEO_COMMENTS'], $rec['VIDEO_LIKE_PERCENT']);
		$tmp .= '</div>';
		$tmp .= '<div class="post-title">';
			$tmp .= _getYtVideoTitle($videoDpUrl,$rec['VIDEO_TITLE'] . $CI->correctPattern("title", $rec['VIDEO_SEARCH_KEYWORDS']));
			$tmp .= _getVideoSmallDesc($rec['VIDEO_DESC'],$rec['VIDEO_TITLE'],$videoDpUrl);
		$tmp .= '</div>';	
		$tmp .= '<div id="yt_dp_button" style="display:flex;">';
			$tmp .= _getYtVideoLinkBox('button',$rec['VIDEO_YT_URL'],$rec['VIDEO_TITLE']);
			$tmp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			$tmp .= _getDpVideoLinkBox('button',$videoDpUrl,$rec['VIDEO_TITLE']);
			$tmp .= _getMoveToTopButton($rec['ID']);
			
		$tmp .= '</div>';
		$tmp .= _getHrLine();
	$tmp .= '</div>';
	return $tmp;
}

function displayIframeZoominCards($rec,$forWhat='video') {
	$CI =& get_instance();
	$videoDpUrl = $CI->sqlModel->createRandomSiteUrls($forWhat,$rec);
	$tmp = '';
	$tmp .= '<div class="post-content">';
		$tmp .= '<div class="post-image">';
			$tmp .= '<div id="play">';
				$tmp .= _getYtIframeEmbedBox($rec['VIDEO_ID']);
			$tmp .= '</div>';
		$tmp .= '</div>';
		$tmp .= '<div class="post_publish">';
			$tmp .= _getvideoStatistics($rec['VIDEO_PUBLISH_DATE'], $rec['VIDEO_VIEWS'], $rec['VIDEO_LIKES'], $rec['VIDEO_COMMENTS'], $rec['VIDEO_LIKE_PERCENT']);
		$tmp .= '</div>';
		$tmp .= '<div class="post-title">';
			$tmp .= _getYtVideoTitle($videoDpUrl,$rec['VIDEO_TITLE'] . $CI->correctPattern("title", $rec['VIDEO_SEARCH_KEYWORDS']),'','h1');
			$tmp .= _getVideoFullDesc($rec['VIDEO_DESC'],$rec['VIDEO_TITLE'],$videoDpUrl);
		$tmp .= '</div>';	
		$tmp .= '<div id="yt_dp_button" style="display:flex;">';
			$tmp .= _getYtSubscriptionLinkBox('button',$rec['VIDEO_YT_URL'],$rec['VIDEO_TITLE'],'Subscribe Us on YouTube');
			$tmp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			$tmp .= _getMoveToTopButton($rec['ID']);
			
		$tmp .= '</div>';
		$tmp .= _getHrLine();
	$tmp .= '</div>';
	return $tmp;
}
function showCommentsOnVideoDetailsPage($showCommentOfCurrentVideos) {
	$tmp = '';
	if(is_array($showCommentOfCurrentVideos) && count($showCommentOfCurrentVideos) > 0) {
		$i = 1;
		$tmp .= _getCategoryHeading('Recent Comments on this video','small');
		$tmp .= '<div style="width: 100%; min-height: 100px; overflow-y: scroll;">';
			$tmp .= '<div class="post-content">';
			foreach($showCommentOfCurrentVideos as $rec){
				$tmp .= '<div class="post-content">';
					$tmp .= _getYtAuthorName($rec['AUTHOR_CHANNEL_URL'],$rec['AUTHOR_NAME']);
					$tmp .= _getAuthorComments($rec['COMMENT'],$rec['COMMENT_PUBLISHED']);
				$tmp .= '</div>';		
			}
			$tmp .= '</div>';
		$tmp .= '</div>';
	}else{
		$tmp .= '<div>' . _getCategoryHeading('No comments in this video','small') . '</div>';
	}		
	return $tmp;
}

/*==============================================================================================*/
function _getSectionHeader($sectionTitle='',$sectionDesc='',$pageStyle='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$themeInfo = $CI->sqlModel->getActiveThemeInfo();
	$temp = '';
	$temp = $themeInfo['THEME_HEADER_HTML'];
	$temp = str_replace('[SECTION_TITLE]',$sectionTitle,$temp);
	$temp = str_replace('[SECTION_DESC]',$sectionDesc,$temp);
	$temp = str_replace('[PAGE_STYLE]',$pageStyle,$temp);
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);	
	return $temp;
}
function _getSectionFooter($sectionFootDesc='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$themeInfo = $CI->sqlModel->getActiveThemeInfo();	
	$temp = '';
	$temp = $themeInfo['THEME_FOOTER_HTML'];
	if($sectionFootDesc=='')
	{
		$sectionFootDesc = 'Created on <strong>' . date("d-m-Y") . ' ' . date("h:i:sa") . '</strong>';
	}	
	$temp = str_replace('[SECTION_FOOT_DESC]',$sectionFootDesc,$temp);
	$CI->sqlModel->_getExecutionTime($funcId);
	return $temp;		
}

function _getSectionHeader1($sectionTitle='',$sectionDesc='',$pageStyle='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	$temp .= '<div class="container" style="' . $pageStyle . '">
				<h2>' . $sectionTitle . '</h2>
				<div class="container-body">
					<p>' . $sectionDesc . '</p>
					';
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);				
	return $temp;
}
function _getSectionFooter1($sectionFootDesc='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	if($sectionFootDesc=='')
	{
		$sectionFootDesc = 'Created on <strong>' . date("d-m-Y") . ' ' . date("h:i:sa") . '</strong>';
	}
	$temp .= ' 			
				</div>
				<p class="footer">' . $sectionFootDesc . '</p>
			</div>';
	$CI->sqlModel->_getExecutionTime($funcId);		
	return $temp;		
}

function _getSectionHeader2($sectionTitle='',$sectionDesc='',$pageStyle='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	$temp .= '<div class="floatLeft">
				<div class="sticker" style="width: 97%;">
					<div class="stickerHeading titillium"><h1>' . $sectionTitle . '</h1></div>
					<p>' . $sectionDesc . '</p>';
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);				
	return $temp;
}
function _getSectionFooter2($sectionFootDesc='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	if($sectionFootDesc=='')
	{
		$sectionFootDesc = 'Created on <strong>' . date("d-m-Y") . ' ' . date("h:i:sa") . '</strong>';
	}
	$temp .= '		<p class="footer">' . $sectionFootDesc . '</p>
			</div></div>';
	$CI->sqlModel->_getExecutionTime($funcId);
	return $temp;		
}
function _getYearListWithForm($locationpath='',$formname='exp_item_form', $selectname='expitemyear',$jsfunc='showThisYearData',$yrsessname='exp_item_year')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$yearListArr = array();
	for($i = 2017; $i <= CURRENT_YEAR; $i++)
	{
		$yearListArr[] = array('year' => $i);
	}
	$tmp = '';
	
	$tmp .= '<div style="float:left;"><form name="' .  $formname . '" id="' .  $formname . '" action="" method="post">
		<select id="' .  $selectname . '" name="' .  $selectname . '" onchange="javascript:' .  $jsfunc . '(this.value,\'' .$locationpath . '\');">';
			$tmp .= '<option value="all">Show All</option>';
			foreach ($yearListArr as $yearkey => $yearval)
			{
				$tmp .= '<option value="' . $yearval['year'] . '"';
				if($yearval['year'] == $_SESSION['exp_item_year']) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $yearval['year'] . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form></div>';	
	$CI->sqlModel->_getExecutionTime($funcId,$locationpath);
	return $tmp;
}
function _showRemainingLinks($result,$href='',$what='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$tmp = '';
	if($what == 'item'){
		$tmp .= '<div style="clear:both;"></div>';
		$tmp .= '<div style="width:100%;margin-top:10px;">';
		$limit = 5;
	}else{
		$limit = 60;
		$tmp .= '<div style="float:right;">';
	}
	if(count($result) > 0)
	{
		$i=0;
		foreach ($result as $linkList)
		{	
			if($i > $limit)
			{
				$tmp .= '<div style="float:left:width:100%;">&nbsp;</div>';$i = 0;
			}
			$tmp .= '<span style="padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;background-color:' . $linkList["BG_COLOR"] . ';font-color:' . $linkList["FONT_COLOR"] . ';font-size: 1.17em;font-weight: bold;text-shadow: 2px 2px 4px #ccc;">';
			if($what == 'account')
				$tmp .= '<a href="/' . $href . '/' . $linkList['ID'] . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $linkList["EXP_ACCOUNT"]; 
			if($what == 'category')
				$tmp .= '<a href="/' . $href . '/' . $linkList['ID'] . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $linkList["EXP_ITEM_CAT"]; 
			if($what == 'item')
				$tmp .= '<a href="/' . $href . '/' . $linkList['ITEM_ID'] . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $linkList["EXP_ITEM_NAME"];		
			$tmp .= '</a></span>';
			
			$i++;
		}
	}
	$tmp .= '</div>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}
function _getNoDataErrorMsg ($msg='NO DATA')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmp .= '<div class="rTableRow rTableHeading">';
			$tmp .= '<div class="rTableHead" align="center"><span class="no-data-error">' . $msg . '</span></div>';
	$tmp .= '</div>';
	$CI->sqlModel->_getExecutionTime($funcId,$msg);	
	return $tmp;
}
function _getExpDisplayFormat($depo,$exp,$separator = "<br>")
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$show = $_SESSION['EXP_DISPLAY_TYPE'];
	$depoStr = '<span style="font-weight:bold;font-size:12px;color:green;">' . number_format($depo,2) . '</span>';
	$expStr = '<span style="font-weight:bold;font-size:12px;color:navy;">' . number_format($exp,2) . '</span>';
	$balStr = '<span style="font-weight:bold;font-size:12px;color:red;">' . number_format(($depo - $exp),2) . '</span>';

	if($show == 1){
		$tmp .= $depoStr;
	}elseif($show == 2){
		$tmp .= $expStr;
	}elseif($show == 3){
		$tmp .= $balStr;
	}elseif($show == 12){
		$tmp .= $depoStr . $separator . $expStr;
	}elseif($show == 123){
		$tmp .= $depoStr . $separator . $expStr . $separator . $balStr;
	}else{
		$tmp .= $expStr;
	}
	$CI->sqlModel->_getExecutionTime($funcId,$show);
	return $tmp;
	
}
function _getOptionLinkHtml($arr)
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$activeDivStyle = '"background: #823874;border-radius: 30px;clear: both;color: #fff;font-size: 14px;font-weight: bold;margin: 0 auto;text-align: center;width: 280px;cursor: pointer;text-decoration: none;padding:10px;margin-left:15px;"';
		
	foreach ($arr as $key => $val)
	{
		$explodedArr = explode('?', $val);
		$exploded = explode('/', $explodedArr[0]);
		//print_r($exploded);
		$option_id = end($exploded);
		if(isset($_SESSION['EXP_DISPLAY_TYPE']) && $option_id == $_SESSION['EXP_DISPLAY_TYPE']){
			$tmp .= '<span style=' . $activeDivStyle . '><a style="color:#fff;" href="' . $val . '">' . $key . '</a></span>';
		}
		else{
			$tmp .= '<span style="padding:10px;margin-left:15px;"><a href="' . $val . '">' . $key . '</a></span>';
		}
		
	}
	$CI->sqlModel->_getExecutionTime($funcId,'',$arr);
	return $tmp;
	
}
function _displayHeaderDataSection($monthArr,$mainColumnHeading='',$allCase='N', $what='', $mainColumnHeadingArr=array())
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	//$tmp = '<div id="scrolly">';
	$tmp .= '<div class="rTableRow rTableHeading">';
		$tmp .= '<div class="rTableHead">SL</div>';
		$tmp .= '<div class="rTableHead">' . $mainColumnHeading . '</div>';
		if($allCase == 'Y')	{
			if($what == 'ALL ITEMS'){
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[0] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[1] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[2] . '</div>';
			}else{
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[0] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[1] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[2] . '</div>';
			}
		}
		foreach ($monthArr as $monYearData){
			foreach ($monYearData as $monData){
				$tmp .= '<div class="rTableHead" style="background-color:#B6DDE8;"><a href="/showMonthlyStatusCurrent/?showMonth='. $monData['month_number'] . '-' . $monData['year'] . '">' . $monData['month_name'] . " " . $monData['year'] . '</a></div>';
			}
		}
		$tmp .= '<div class="rTableHead">G. Total</div>';
	$tmp .= '</div>';
	$CI->sqlModel->_getExecutionTime($funcId,$what,$monthArr);
	return $tmp;
}
function _displayMiddleDataSection($dataArr,$linkArr=array(),$allCase='N', $what='', $middleLinkArr=array(),$middleLinkLabelArr=array())
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmArr = array();
	foreach ($dataArr as $etDataKey => $etDataVal){
		$cnt = count($etDataVal['exp_details']);
		for($c = 0; $c < $cnt; $c++){
			$tmArr[$etDataVal['exp_details'][$c]['month_name'] . $etDataVal['exp_details'][$c]['year']] = array('EXP' => 0, 'DEPO' => 0);
		}
	}
	$sl = 0;
	foreach ($dataArr as $etDataKey => $etDataVal){
		$sl++;
		if(isset($etDataVal['item_status'])){
			$curr_item_id = $etDataVal['item_id'];
		}else{
			$curr_item_id = 0;
		}
		if(isset($etDataVal['item_status']) && $etDataVal['item_status'] == 'Y'){
			$tmpBgColor = '#000';$tmpFontColor = '#fff !important';$strikeText = '<strike>' . $etDataKey . '</strike> EXPIRED';
		}else{
			$tmpBgColor = $etDataVal['bg_color'];$tmpFontColor = $etDataVal['font_color'];
			if($allCase == 'Y')	{
				$strikeText = '<a href="/' . $middleLinkArr[0] . '/' .  $etDataVal[$middleLinkArr[1]] . '/' . $curr_item_id  . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $etDataKey . '</a>';
			}else{
				$strikeText = '<a href="/' . $linkArr[0] . '/' .  $etDataVal[$linkArr[1]] . '/' . $curr_item_id  . '">' . $etDataKey . '</a>';
			}
		}
		$tmp .= '<div class="rTableRow" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">';
			$tmp .= '<div class="rTableCell" style="width:10px !important;">' . $sl . '</div>';
			if($allCase == 'Y')	{
				$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . $strikeText . '</div>';
				$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . $etDataVal['pay_day'] . '</div>';
				if($what == 'ALL ITEMS'){
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[0] . '/' . $etDataVal[$middleLinkArr[1]] . '">' . $etDataVal[$middleLinkLabelArr[0]] . '</a></div>';
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[2] . '/' . $etDataVal[$middleLinkArr[3]] . '">' . $etDataVal[$middleLinkLabelArr[1]] . '</a></div>';
				}else{
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[0] . '/' . $etDataVal[$middleLinkArr[1]] . '">' . $etDataVal[$middleLinkLabelArr[0]] . '</a></div>';
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[2] . '/' . $etDataVal[$middleLinkArr[3]] . '">' . $etDataVal[$middleLinkLabelArr[1]] . '</a></div>';
				}
			}else{
				$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $linkArr[0] . '/' . $etDataVal[$linkArr[1]] . '">' . $etDataKey . '</a></div>';
			}
			$exp_total = 0;
			$depo_total = 0;
			foreach ($etDataVal['exp_details'] as $eTmonData){
				foreach($tmArr as $tmArrKey => $tmArrVal){
					if($tmArrKey == trim($eTmonData['month_name'] . $eTmonData['year'])){
						$tmArr[$tmArrKey]['EXP'] += $eTmonData['exp_amt'];
						$tmArr[$tmArrKey]['DEPO'] += $eTmonData['depo_amt'];
					}
				}
				$exp_total += $eTmonData['exp_amt'];
				$depo_total += $eTmonData['depo_amt'];
				$tmp .= '<div class="rTableCell" align="right" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . _getExpDisplayFormat($eTmonData['depo_amt'], $eTmonData['exp_amt']) . '</div>';
			}
			$tmp .= '<div class="rTableCell" align="right" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . _getExpDisplayFormat($depo_total, $exp_total) . '</div>';
		$tmp .= '</div>';
	}
	/***Storing $tmArr to SESSION for use later **/
	$_SESSION['tmArr'] = $tmArr;
	$CI->sqlModel->_getExecutionTime($funcId,$what,$linkArr);
	return $tmp;	
}

function _displayBottomDataSection($allCase='N', $what='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmArr = $_SESSION['tmArr'];
	$tmp = '';
		$tmp .= '<div class="rTableRow rTableFoot">';
			$tmp .= '<div class="rTableCell">&nbsp;</div>';
			$tmp .= '<div class="rTableCell">Total</div>';
			if($allCase == 'Y')	{
				$tmp .= '<div class="rTableCell">&nbsp;</div>';
				$tmp .= '<div class="rTableCell">&nbsp;</div>';				
				if($what == 'ALL ITEMS'){
					$tmp .= '<div class="rTableCell">&nbsp;</div>';	
				}else{
					$tmp .= '<div class="rTableCell">&nbsp;</div>';
				}	
			}
			$exp_total = 0;
			$depo_total = 0;
			foreach($tmArr as $tmArrKey => $tmArrVal){
				$exp_total += $tmArr[$tmArrKey]['EXP'];
				$depo_total += $tmArr[$tmArrKey]['DEPO'];
				$tmp .= '<div class="rTableCell" align="right">' . _getExpDisplayFormat($tmArr[$tmArrKey]['DEPO'], $tmArr[$tmArrKey]['EXP']) . '</div>';
			}
			$tmp .= '<div class="rTableCell" align="right">' . _getExpDisplayFormat($depo_total, $exp_total) . '</div>';
		$tmp .= '</div>';
		//$tmp .= '</div>';//Scrolly Div End
		$CI->sqlModel->_getExecutionTime($funcId,$what);
	return $tmp;
}
function _startTable()
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmp .= '<div class="rTable">';
	$CI->sqlModel->_getExecutionTime($funcId);
	return $tmp; 
}
function _endTable()
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmp .= '</div>';
	$CI->sqlModel->_getExecutionTime($funcId);
	return $tmp; 
}

/****************AdminPanel HTML Helper functions Starts***************/ 
function _getAdminSectionHeader($sectionTitle='',$panelTitle='',$msg='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$temp = '';
    $temp .= '<div id="page-wrapper">';
    $temp .= '   <div class="row">';
    $temp .= '       <div class="col-lg-12">';
    $temp .= '            <h1 class="page-header">' . $sectionTitle . '</h1>';
    $temp .= '       </div>';
    $temp .= '       <!-- /.col-lg-12 -->';
    $temp .= '  </div>';

    $temp .= '        <div class="row">';
    $temp .= '            <div class="col-lg-12">';
    $temp .= '                <div class="panel panel-default">';
    $temp .= '                    <div class="panel-heading">' . $panelTitle;
									if (isset($msg) && $msg != '') { 
	$temp .= '					 	 <div class="alert alert-success alert-dismissable">';
    $temp .= '                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    $temp .=                             $msg;
    $temp .= '                          </div>';
									}                        
    $temp .= '                    </div>';
    $temp .= '                   <!-- /.panel-heading -->';

	$temp .= '<form id="postDataForm" method="post" action="/postData">';
	$temp .= '	<div class="panel-body">';	
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);	
	return $temp;
}
function _getAdminSetionFooter()
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
    $temp = '';

	$temp .= '<!-- /.table-responsive -->';
	$temp .= '</div>	<!-- /.panel-body -->';
	$temp .= '</form>';	
	$temp .= '				</div>';
    $temp .= '                <!-- /.panel -->';
    $temp .= '            </div>';
    $temp .= '            <!-- /.col-lg-12 -->';
    $temp .= '        </div>';
    $temp .= '        <!-- /.row -->';
    $temp .= '            <!-- /.col-lg-6 -->';
    $temp .= '        </div>';
    $temp .= '        <!-- /.row -->';
    $temp .= '    </div>';
    $temp .= '    <!-- / page-wrapper -->';	
	$CI->sqlModel->_getExecutionTime($funcId,'');	
	return $temp;	
}
function _getSelectCombo($result,$selectName,$selectedId,$action){
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	
	$tmp .= '<select id="' .  $selectName . '" name="' .  $selectName . '"';
	if($action == 'delete'){$tmp .= ' disabled';}
	$tmp .= '>';
			foreach ($result as $rec)
			{
				$tmp .= '<option value="' . $rec['ID'] . '"';
				if($rec['ID'] == $selectedId) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $rec['selectItem'] . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}
function _getSelectColorCombo($result,$selectName,$selectedId,$action){
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$colorKey = $result[0]['selectItem'];
	if($selectedId > 0)
	{
		foreach ($result as $rec)
		{
			
			if($rec['ID'] == $selectedId) 
			{
				$colorKey = $rec['selectItem'];break;
			}
			
		}
	}//print'<pre>';print_r($result);die;
	$tmp .= '<select ' . $colorKey . ' name="' .  $selectName . '"';
	if($action == 'delete'){$tmp .= ' disabled';}
	$tmp .= ' id="' .  $selectName . '" onchange="colourFunction()">';
			foreach ($result as $rec)
			{
				$tmp .= '<option ' . $rec['selectItem'] . ' value="' . $rec['ID'] . '"';
				if($rec['ID'] == $selectedId) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $rec['selectItemText'] . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}
function _getActiveSelectCombo($selectName,$selectedId,$action,$num=''){
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	if($num == 'number')
	{
		$result = array();
		for($i = 0; $i <= 31; $i++)
		{
			$result[] = $i;
		}
	}else{
		$result = array('Y' => 'Yes', 'N' => 'No');
	}
	$tmp = '';
	
	$tmp .= '<select name="' .  $selectName . '"';
	if($action == 'delete'){$tmp .= ' disabled';}
	$tmp .= '>';
			foreach ($result as $reckey => $recval)
			{
				$tmp .= '<option value="' . $reckey . '"';
				if($reckey == $selectedId) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $recval . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}




/****************AdminPanel HTML Helper functions End***************/ 
			

function busi_get_ticketBox($index,$ticketBoxData, $showOuterDiv='')
{
	$tmp = '';
	foreach ($ticketBoxData as $tData){
		if($index == $tData['ticket_sl']){
			if($showOuterDiv != ''){
				$tmp .= '<div style="float:left;margin:0px;padding:0px;width:9.95%;">';
				$rotateStyle = '';
			}else{
				$rotateStyle = '-ms-transform: rotate(90eg);transform: rotate(90deg);';
			}
				$tmp .= '<div id="' . $tData['ticket_sl'] . '" style="float:left;margin:3px;padding:0px;width:92%;border:3px solid ' . $tData['ticket_color'] . ';border-radius:10px;background-color:' . $tData['ticket_color'] . ';' . $rotateStyle .'" align="center">';
					$tmp .= '<div style="text-align:center;width:100%;margin:0px;padding-top:7px;padding-bottom:7px;font-size:16px;font-family:arial;font-weight:700;color:#fff;">' .$tData['ticket_name'] .'</div>';
					$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:green">2 House</div>';
					$tmp .= '<div valign="middle" align="center" style="text-align:center;min-height:100px;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#000;background-color:#eeeeee;vertical-align:middle;">';
						$tmp .= '<div style="background-color:pink;text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#000;">Rinku Arrived</div>';
						$tmp .= '<div style="background-color:yellow;text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#000;">Yash Arrived</div>';
						$tmp .= '<div style="background-color:green;text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#000;">Aakash Arrived</div>';
					$tmp .= '</div>';
					$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:red">Sold to Yash</div>';

					$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding:0px;padding-top:5px;padding-bottom:5px;font-size:16px;font-family:arial;font-weight:700;color:#000;">';
						$tmp .= '$ ' . $tData['ticket_price'];
						$tmp .= '</div>';
				$tmp .= '</div>';			
			

			if($showOuterDiv != ''){
				$tmp .= '</div>';
			}
		}
	}	

	
	return $tmp;
}


?>