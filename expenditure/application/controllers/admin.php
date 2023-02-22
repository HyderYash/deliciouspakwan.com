<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}
class Admin extends MY_Controller {

	public function __construct() {
        parent::__construct();
		//$this->sqlModel->_setFunctionHistory(__method__);
    }
	function userRegistration ()
	{
		//$this->checkSignIn();
		//$this->sqlModel->_setFunctionHistory(__method__);
		$data['userList'] = $this->sqlModel->admin_getUserList();
		echo json_encode($data['userList']);
	}
	function getCategoryList ()
	{
		//$this->checkSignIn();
		$this->sqlModel->_setFunctionHistory(__method__);
		$data['userList'] = $this->sqlModel->getCategoryList();
		echo json_encode($data['userList']);
	}	
	function comQuesListWithAns ()
	{
		$this->checkSignIn();
		$this->sqlModel->_setFunctionHistory(__method__);
		$data['quesList'] = $this->sqlModel->getQuesListWithAns();
		$content = $this->load->view('word/ques_list', $data, true);
		$this->load_template($content);	
	}	

}