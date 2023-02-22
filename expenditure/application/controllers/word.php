<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}
class Word extends MY_Controller {

	public function __construct() {
        parent::__construct();
		$this->sqlModel->_setFunctionHistory(__method__);
    }
	function comWordListWithSent ()
	{
		$this->checkSignIn();
		$this->sqlModel->_setFunctionHistory(__method__);
		$data['wordList'] = $this->sqlModel->getWordListWithSent();
		$content = $this->load->view('word/word_list', $data, true);
		$this->load_template($content);	
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