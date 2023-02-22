<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
  $_SESSION['executedFunctionHistoryArr'] = array();
  $_SESSION['currentCalledMethod'] = '';
}
class MY_Business extends CI_Controller {

	public function __construct() {
        parent::__construct();
	
		$this->load->library('db');
		$this->load->model('businessSqlmodel', 'businessSqlModel');
		$this->load->helper('html');

		
    }
	


}