<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
  $_SESSION['executedFunctionHistoryArr'] = array();
  $_SESSION['currentCalledMethod'] = '';
}
class MY_Controller extends CI_Controller {

	public function __construct() {
        parent::__construct();
	
		$this->load->library('db');
		$this->load->model('sqlmodel', 'sqlModel');
		$this->load->helper('html');
		// $this->sqlModel->_emptyDebugTable();
		$this->setDefaultMetaData();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$this->sqlModel->_getExecutionTime($funcId);
		
	}
	public function setDefaultMetaData() {
		$_SESSION['DP_META_TITLE'] = 'Delicious Pakwan';
		$_SESSION['DP_META_DESC'] = 'This is my Cooking Channel named Delicious Pakwan. Here I upload Easy and Delicious recipes that are easy to make at home.';
		$_SESSION['DP_META_KEYWORDS'] = 'cooking recipe,quick recipe,lockdown recipe,breakfast,lunch,dinner,fast cooking,easy cooking,lunch recipe,dinner recipe,breakfast recipe,foodlover,chefs,special cooking,easy cooking,cooking love,recipe of,recipeof,breakfast special,recipe in hindi,recipe of dinner,recipe of snacks,recipe of bread samosa,quick snacks to make at home,quick snacks,quick recipes,quick breakfast ideas,food recipes,up recipes,special recipes,special dinner recipes,DeliciousPakwan,DeliciousPakwan,Delicious Pakwan,RinkuSharma,Rinkusharma,Rinku sharma,eggroll,malpua,mojito,kadhi,summerdrinks,chiaseeds,manchurian,chickenpopcorn,vegmanchurian,laukikofta,kadukofta,kaddukofta,youtubechannel,hebbar,skitchen,NishaMadhulika,kabita,skitchen,shecooks,cookwithparul,India,recipes,Pune,Delhi,Mumbai,Hyderabad,food,pakwan,delicious,tasty,recipe,mangoshake,milkshake,kabitaskitchen,simple easy authentic recipes,home style food,indian food recipes,simple recipes,easy recipes,restaurant style indian food,food channel,indian food channel';
		$_SESSION['DP_CANONICAL_URL'] = BASEURL;
	}
	public function correctPattern($what, $str) {
		$retStr = "";
		if($what == "title") {
			if($str !== "") {
				$retStr = " | " . str_replace(",", "|", $str);
			}
		} else if($what == "keywords") {
			if($str !== "") {
				$retStr = " , " . str_replace("|", ",", $str);
			}
		}
		return $retStr;
	}
	public function overrideMetaDataInfo($dataArr=array(), $what='', $currentVideoId=0) {
		$title = $_SESSION['DP_META_TITLE'];
		$desc = $_SESSION['DP_META_DESC'];
		$keywords = $_SESSION['DP_META_KEYWORDS'];
		$canonical = BASEURL;		
		//print $what;print $currentVideoId;print_r($dataArr);die;
		switch($what) {
			case 'video':
			if(is_array($dataArr) && !empty($dataArr)) {
				$i = 0;
				foreach($dataArr as $rec) {
					if($i == $currentVideoId) {
						$title = $rec['VIDEO_TITLE'];
						$desc = $rec['VIDEO_DESC'];
						$keywords = strtolower(str_replace("|", "," , $rec['VIDEO_TITLE'])) . $this->correctPattern("keywords", $rec['VIDEO_SEARCH_KEYWORDS']);
						$canonical = BASEURL . $this->sqlModel->tmpRandomUrl($rec['VIDEO_DP_URL'],$what,'main');
					}
					$i++;
				}
			}
			break;
			case 'playlists':
				$tmp = '';
				if(is_array($dataArr) && !empty($dataArr)) {
					foreach($dataArr as $rec) {
						$tmp .= strtolower($rec['PLAYLIST_TITLE']) . ',';
					}
				}
				$title = 'Delicious Pakwan Playlists';
				$desc = 'Here you can find many Playlists which contains lots of tasty and delicious videos recipes to make at home...';
				$keywords = $tmp;
				$canonical = BASEURL . '/' . $what;				
			break;
			case 'playlistVideo':
				if(is_array($dataArr) && !empty($dataArr)) {
					$i = 0;
					foreach($dataArr as $rec) {
						if($rec['VIDEO_ID'] == $currentVideoId) {
							$title = $rec['VIDEO_TITLE'];
							$desc = $rec['VIDEO_DESC'];
							$keywords = strtolower(str_replace("|", "," , $rec['VIDEO_TITLE'])) . $this->correctPattern("keywords", $rec['VIDEO_SEARCH_KEYWORDS']);
							//print $rec['VIDEO_DP_URL'] . '<br>';
							$canonical = BASEURL . $this->sqlModel->tmpRandomUrl($rec['VIDEO_DP_URL'],'video','main');
						}
						$i++;
					}
				}
			break;
			case 'pageNotFound':
				$title = 'Page Not Found';
				$desc = 'Page Not Found';
				$keywords = 'Page Not Found';
				$canonical = BASEURL . '/' . $what;
			break;
			case 'Sitemap':
				$title = 'Sitemap | Delicious Pakwan';
				$tmp = '';
				if(is_array($dataArr) && !empty($dataArr)) {
					foreach($dataArr as $rec) {
						$tmp .= strtolower($rec['SITEMAP_LABEL']) . ',';
					}
				}				
				$desc = $title;
				$keywords = $tmp;
				$canonical = BASEURL . '/sitemap.html';
			break;			
			default:
				$title = $_SESSION['DP_META_TITLE'];
				$desc = $_SESSION['DP_META_DESC'];
				$keywords = $_SESSION['DP_META_KEYWORDS'];
				$canonical = BASEURL;				
			break;
		}
		$_SESSION['DP_META_TITLE'] = substr($title, 0, 69);
		$_SESSION['DP_META_DESC'] = substr($desc, 0, 159);
		$_SESSION['DP_META_KEYWORDS'] = $keywords;
		$_SESSION['DP_CANONICAL_URL'] = $canonical;
	}	
	function checkSignIn()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		if(!isset($_SESSION['curr_user_token']))
		{
			$this->sqlModel->customRedirect('/index.php');
		}
		$this->sqlModel->_getExecutionTime($funcId);
	}
	public function checkLogin($uname,$upwd)
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$chk = $this->sqlModel->checkAndSetUserLogin($uname,$upwd);
		$this->sqlModel->_getExecutionTime($funcId);
		return $chk;
	}
	public function isLogged()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$chk = $this->sqlModel->checkIfUserLogged();
		$this->sqlModel->_getExecutionTime($funcId);
		return $chk;
	}
	function getActiveThemeInfoFromDb()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$chk = $this->sqlModel->getActiveThemeInfo();
		$this->sqlModel->_getExecutionTime($funcId);
		return $chk;
	}	
	function load_template($content,$forMethod='')
	{	
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		//header('Content-Type: text/html; charset=utf-8');
        //$themeInfoArr['themeInfo'] = $this->sqlModel->getActiveThemeInfo();
		//$themeInfoArr['availableThemes'] = $this->sqlModel->getAvailableThemes();
		$this->sqlModel->_getExecutionTime($funcId,'Theme Info');
		//$this->sqlModel->getAndSetMetaInfo($forMethod);
		$themeInfoArr['MetaTitle'] =  'Delicious Pakwan';
        $themeInfoArr['MetaDescription'] =  'Delicious Pakwan';
        $themeInfoArr['MetaKeyWords'] =  'Delicious Pakwan';
        $themeInfoArr['MetaRobots'] =  'INDEX FOLLOW';;
        $data = array(
            'header'      => $this->load->view('common/header',$themeInfoArr, true),
            'content'     => $content,
            'footer'      => $this->load->view('common/footer','', true),
			'debug_info'  => (DEBUG_STATUS == 1) ? $this->sqlModel->_debugScriptForErrors(0,'N','N') : '',
        );
        $this->load->view('template/template', $data);
	}

}