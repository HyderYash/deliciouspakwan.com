<?php
if (!isset($_SESSION)) { 
  session_start();
}
class Ajax extends CI_Controller{

	public function __construct() {
        parent::__construct();
		$this->load->library('db');
		$this->load->model('sqlmodel', 'sqlModel');		
    }
	
	public function modify_pay_day()
	{
		$payday_item_id = str_replace('payday_', '', $_POST['item_id']);
		$curr_payday_val = str_replace(',', '', $_POST['curr_payday_val']);
		$this->sqlModel->modifyPayDayVal($payday_item_id,$curr_payday_val);
		echo $redirect_url = '/showMonthlyStatusCurrent/?showMonth=' . $_SESSION['curr_mon'] . '-' . $_SESSION['curr_yr'];
	}
	public function modify_allocation()
	{
		$allocation_id = str_replace('allocation_', '', $_POST['allocation_id']);
		$allocation_amt = str_replace(',', '', $_POST['allocation_amt']);
		$this->sqlModel->modifyAllocationAmt($allocation_id,$allocation_amt);
		echo $redirect_url = '/showMonthlyStatusCurrent/?showMonth=' . $_SESSION['curr_mon'] . '-' . $_SESSION['curr_yr'];
	}
	
	public function modify_current_bal()
	{
		$acct_id = str_replace('curr_bal_', '', $_POST['acct_id']);
		$curr_bal_amt = str_replace(',', '', $_POST['curr_bal_amt']);
		$this->sqlModel->modifyCurrBalAmt($acct_id,$curr_bal_amt);
		echo $redirect_url = '/';
	}
	public function set_current_theme()
	{
		$this->sqlModel->setCurrentTheme($_POST['themeid']);
		echo $redirect_url = $_SERVER['HTTP_REFERER'];
	}	
}
?>