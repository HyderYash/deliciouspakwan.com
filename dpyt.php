<?php
require '../includes/env_constants.php';
require 'simple_html_dom.php';
$buffer = file_get_contents(HTDOCS_PATH . "/tmpytdata.txt");
$html =<<<html
print $buffer;
html;

$dom = str_get_html($html);

// Find all <li> in <ul>
foreach($dom->find('#row-container') as $rowContainer)
{
		$videoTitle = $rowContainer->find('#video-title',0);
		$videoThumbnailUrl = $rowContainer->find('img[id=img-with-fallback]',0)->src;
		$videoDuration = $rowContainer->find('div[class=label style-scope ytcp-thumbnail]',0);
		$videoDescription = $rowContainer->find('div[class=style-scope ytcp-video-list-cell-video description]',0);
		$videoViews = $rowContainer->find('div[class=style-scope ytcp-video-row cell-body tablecell-views sortable right-align]',0);
		$videoComments = $rowContainer->find('a[class=style-scope ytcp-video-row remove-default-style]',0);
		$videoLikes = $rowContainer->find('div[class=style-scope ytcp-video-row likes-label]',0);
		$videoLikesPercent = $rowContainer->find('div[class=style-scope ytcp-video-row percent-label]',0);
		$videoID = $rowContainer->find('a[class=style-scope ytcp-video-list-cell-video remove-default-style]',0)->href;
		$videoPublishDate = $rowContainer->find('div[class=style-scope ytcp-video-row cell-body tablecell-date sortable column-sorted]',0);
		
			print "<br>=========videoTitle===============<br>";
			print $videoTitle->plaintext;
			print "<br>=======videoThumbnailUrl=================<br>";
			print $videoThumbnailUrl;
			print "<br>=========videoDuration===============<br>";
			print $videoDuration->plaintext;
			print "<br>===========videoDescription=============<br>";
			print $videoDescription;
			print "<br>=========videoViews===============<br>";
			print $videoViews->plaintext;
			print "<br>=========videoComments===============<br>";
			print $videoComments->plaintext;	
			print "<br>=========videoLikes===============<br>";
			print $videoLikes->plaintext;
			print "<br>=========videoLikesPercent===============<br>";
			print $videoLikesPercent->plaintext;			
			print "<br>=======videoID=================<br>";
			print $videoID;
			print "<br>=========videoPublishDate===============<br>";
			print $videoPublishDate->plaintext;			
			print "<br>++++++++++++++++++++++++++++++++++++++++++==<br>";
}

?>