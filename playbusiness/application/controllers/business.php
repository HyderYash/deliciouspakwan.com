<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}
class Business extends CI_Controller {

	public function __construct() {
        parent::__construct();
		$this->load->library('db');
		$this->load->model('businesssqlmodel', 'businessSqlModel');
		$this->load->helper('html');
    }
	public function index()
	{
		$data['error_msg'] = '';
		if(isset($_SESSION['ERROR_MSG']) && $_SESSION['ERROR_MSG'] != ''){
			$data['error_msg'] = $_SESSION['ERROR_MSG'];
		}
		$data['home'] = '';
		$data['gameListData'] = $this->businessSqlModel->getGameListData();
		$data['playerListData'] = $this->businessSqlModel->getPlayerListData();
		
		$content = $this->load->view('home', $data, true);
		$this->load_business_template($content,__method__);
	}	
	function logout(){
		unset($_SESSION['CURRENT_GAME_ID']);
		unset($_SESSION['ERROR_MSG']);
		$this->businessSqlModel->customRedirect('/');
	}
	function resetGame(){
		unset($_SESSION['ERROR_MSG']);
		$this->businessSqlModel->resetCurrentGame();
	}
	function deleteGame(){
		$this->businessSqlModel->deleteCurrentGame();
	}
	public function startBusiness()
	{
		
		if(isset($_POST['selectGameAndStartBusiness'])){
			$_SESSION['CURRENT_GAME_ID'] = $_POST['selectedGameId'];
		}
		if(isset($_POST['createGameAndStartBusiness'])){
			$this->businessSqlModel->createNewGame($_POST);
		}		
		$this->showBusinessDashboard();
	}

	public function showBusinessDashboard()
	{
		$this->businessSqlModel->checkGameSession();
		if(isset($_POST['MovePlayerToNewPosition'])){
			
			$this->businessSqlModel->updatePlayerPosition($_POST['playerId'], $_POST['posNumber']);
			$this->businessSqlModel->customRedirect('/startBusiness');
		}
		if(isset($_POST['AddNewPlayerToGame'])){
			$this->businessSqlModel->addNewPlayerToGame($_POST['newPlayerId']);
			$this->businessSqlModel->customRedirect('/startBusiness');
		}		
		$data['gameName'] = $this->businessSqlModel->getGameName();
		$data['ticketBoxData'] = $this->businessSqlModel->getTicketBoxData();
		$data['playerListData'] = $this->businessSqlModel->getPlayerListData($_SESSION['CURRENT_GAME_ID']);
		$data['additonalPlayerListData'] = $this->businessSqlModel->getAdditionalPlayerListData($_SESSION['CURRENT_GAME_ID']);
		
		//Account Related code
		$data['accountListData'] = $this->businessSqlModel->getAccountListData($_SESSION['CURRENT_GAME_ID']);
		
		$content = $this->load->view('startbusiness', $data, true);
		$this->load_business_template($content,__method__);
	}	
	function load_business_template($content,$forMethod='')
	{	
		$themeInfoArr['MetaTitle'] =  'Business Game';
        $themeInfoArr['MetaDescription'] =   'Business Game';
        $themeInfoArr['MetaKeyWords'] =   'Business Game';
        $themeInfoArr['MetaRobots'] =  'INDEX FOLLOW';
        $data = array(
            'header'      => $this->load->view('common/header',$themeInfoArr, true),
            'content'     => $content,
            'footer'      => $this->load->view('common/footer','', true),
			'debug_info'  => '',
        );
        $this->load->view('template/template', $data);
	}
}

/* End of file business.php */
/* Location: ./application/controllers/business.php */
?>