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
		$this->load->model('adminPanelSqlModel', 'adminPanelSqlModel');
		$this->load->helper('html');
		$this->sqlModel->_emptyDebugTable();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		if(isset($_SESSION['curr_user_token']))
		{
			$this->sqlModel->auto_do_default_deposit_for_month(CURRENT_MONTH, CURRENT_YEAR);
			$this->sqlModel->auto_do_add_exp_for_fixed_paid_items(CURRENT_MONTH, CURRENT_YEAR);
			
		}
		$this->sqlModel->_getExecutionTime($funcId);
		
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
        $themeInfoArr['themeInfo'] = $this->sqlModel->getActiveThemeInfo();
		$themeInfoArr['availableThemes'] = $this->sqlModel->getAvailableThemes();
		$this->sqlModel->_getExecutionTime($funcId,'Theme Info');
		$this->sqlModel->getAndSetMetaInfo($forMethod);
		$themeInfoArr['MetaTitle'] =  $_SESSION['MetaTitle'];
        $themeInfoArr['MetaDescription'] =  $_SESSION['MetaDescription'];
        $themeInfoArr['MetaKeyWords'] =  $_SESSION['MetaKeyWords'];
        $themeInfoArr['MetaRobots'] =  $_SESSION['MetaRobots'];
        $data = array(
            'header'      => $this->load->view('common/header',$themeInfoArr, true),
            'content'     => $content,
            'footer'      => $this->load->view('common/footer','', true),
			'debug_info'  => (DEBUG_STATUS == 1) ? $this->sqlModel->_debugScriptForErrors(0,'N','N') : '',
        );
        $this->load->view('template/template', $data);
	}
	function load_admin_template($content,$forMethod='')
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		//header('Content-Type: text/html; charset=utf-8');
        $themeInfoArr['themeInfo'] = $this->sqlModel->getActiveThemeInfo();
		$themeInfoArr['availableThemes'] = $this->sqlModel->getAvailableThemes();
		$this->sqlModel->_getExecutionTime($funcId,'Theme Info');
		$this->sqlModel->getAndSetMetaInfo($forMethod);
		$themeInfoArr['MetaTitle'] =  'Admin Panel - ' . $_SESSION['MetaTitle'];
        $themeInfoArr['MetaDescription'] =  $_SESSION['MetaDescription'];
        $themeInfoArr['MetaKeyWords'] =  $_SESSION['MetaKeyWords'];
        $themeInfoArr['MetaRobots'] =  $_SESSION['MetaRobots'];
        $data = array(
            'header'      => $this->load->view('adminpanel/common/header',$themeInfoArr, true),
            'content'     => $content,
            'footer'      => $this->load->view('adminpanel/common/footer','', true),
			'debug_info'  => (DEBUG_STATUS == 1) ? $this->sqlModel->_debugScriptForErrors(0,'N','N') : '',
        );
		
        $this->load->view('template/adminpanel/template', $data);
		
	}	
}