
<?php 
  
function file_get_contents_curl($url) { 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    $data = curl_exec($ch); 
    curl_close($ch); 
  
    return $data; 
} 
  
// $data = file_get_contents( 
// 'https://i.ytimg.com/vi/PsyRzKWNNh4/mqdefault.jpg'); 
  
// $fp = __DIR__ . '/images/videothumbnails/PsyRzKWNNh4.jpg'; 

// $down = file_put_contents( $fp, $data ); 
// var_dump($down);
// echo "File downloaded!"
  
  
  	function getBuildURL($videoorplaylistTitle) {
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
		//$tmp = '/' . $type . '/' . $videoorplaylistId . '/'. $tmp . '.html'; 
		
		return $tmp;
	}
	$videoorplaylistTitle = "इमली की खट्टी मीठी चटनी | Imli Ki Chatni Recipe | Street Style Chutney | Delicious Pakwan";
	print getBuildURL($videoorplaylistTitle);die;
?> 