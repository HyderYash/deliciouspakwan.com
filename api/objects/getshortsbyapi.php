<?php
// class Shorts{
// function file_get_contents_ssl($url) {
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // curl_setopt($ch, CURLOPT_HEADER, false);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_REFERER, $url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
    // curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
    // $result = curl_exec($ch);
    // curl_close($ch);
    // return $result;
// }
	// public function getAllShortsAndStoreInFile(){
		// $arrContextOptions=array(
    // "ssl"=>array(
        // "verify_peer"=>false,
        // "verify_peer_name"=>false,
    // ),
// );  
		// $url = 'https://yt.lemnoslife.com/channels?part=shorts&id=UCg22-16kmYWZTUQF9wVkqFA';
			// $videoDetails = file_get_contents($url, false, stream_context_create($arrContextOptions));
			// $videoDetails = json_decode($videoDetails);
// print($videoDetails);
		// return true;
	// }	
// }
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://yt.lemnoslife.com/channels?part=shorts&id=UCg22-16kmYWZTUQF9wVkqFA/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
if(curl_errno($curl)){
    echo 'Curl error: ' . curl_error($curl);
}
curl_close($curl);
// print_r($response);
?>