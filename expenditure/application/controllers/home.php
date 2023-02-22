<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}
class Home extends MY_Controller {

	public function __construct() {
        parent::__construct();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$this->sqlModel->_getExecutionTime($funcId);
    }
	public function index()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$data['loginmsg'] = 'Enter Username and Password to Login..';
		if((isset($_POST['uname']) && $_POST['uname'] != '') && (isset($_POST['psw']) && $_POST['psw'] != ''))
		{
			if($this->checkLogin($_POST['uname'],$_POST['psw']))
			{
				$this->sqlModel->_getExecutionTime($funcId);
				$this->showDashboard();
			}
			else
			{
				$data['loginmsg'] = 'Invalid Login Details!!';
				$content = $this->load->view('login', $data, true);
				$this->load_template($content,__method__);
			}			
		}
		else
		{
			if($this->isLogged())
			{
				$this->sqlModel->_getExecutionTime($funcId);
				$this->showDashboard();
			}
			else
			{
				$this->sqlModel->_getExecutionTime($funcId);
				$content = $this->load->view('login', $data, true);
				$this->load_template($content,__method__);
			}
			
		}
		
	}
	public function recoverPassword()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$data['loginmsg'] = 'Enter details to recover Password process..';
		$content = $this->load->view('forget_password', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);		
	}
	public function signUp()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		if((isset($_POST['uname']) && $_POST['uname'] != '') && (isset($_POST['psw']) && $_POST['psw'] != '')  && (isset($_POST['uemail']) && $_POST['uemail'] != '') && (isset($_POST['addques']) && $_POST['addques'] != '') && (isset($_POST['addans']) && $_POST['addans'] != ''))
		{
			if($this->sqlModel->checkUserNameForSignUp($_POST['uname'],$_POST['uemail']))
			{
				$rightAns = $this->sqlModel->getAnswer($_POST['addques']);
				if($rightAns == $_POST['addans']){
					$msg = $this->sqlModel->createNewUser($_POST['uname'],$_POST['psw'],$_POST['uemail']);
					$data['loginmsg'] = $msg . '<br>SignUp process successful!! Please Login to continue..';
					$content = $this->load->view('login', $data, true);
					$this->sqlModel->_getExecutionTime($funcId);
					$this->load_template($content,__method__);
				}else{
					$data['loginmsg'] = 'Answer is wrong!! Try Agian..';
					$content = $this->load->view('signup', $data, true);
					$this->sqlModel->_getExecutionTime($funcId);
					$this->load_template($content,__method__);
				}
			}
			else
			{
				$data['loginmsg'] = 'Username or Email already exists!!';
				$content = $this->load->view('signup', $data, true);
				$this->sqlModel->_getExecutionTime($funcId);
				$this->load_template($content,__method__);
			}
		}
		else{
			$data['loginmsg'] = 'Enter Username and Password to begin SignUp process..';
			$content = $this->load->view('signup', $data, true);
			$this->sqlModel->_getExecutionTime($funcId);
			$this->load_template($content,__method__);
		}
		
	}
	
		
	public function doLogout()
	{
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$this->sqlModel->userLogout();
		$this->sqlModel->_getExecutionTime($funcId);
	}	
	public function showDashboard()
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$data['dashboardData'] = $this->sqlModel->getDashboardData();
		
		$timeDuration = CURRENT_MONTH . "-" . CURRENT_YEAR;
		if(isset($_REQUEST['timeDuration']) && $_REQUEST['timeDuration'] != '')
		{
			$timeDuration = $_REQUEST['timeDuration'];
		}
		$showMonthlyItem = 'Accounts';
		//print_r($_REQUEST); 
		if(isset($_REQUEST['Category']) && $_REQUEST['Category'] != '')
		{
			$showMonthlyItem = 'Category';
		}
		if(isset($_REQUEST['Accounts']) && $_REQUEST['Accounts'] != '')
		{
			$showMonthlyItem = 'Accounts';
		}
		if(isset($_REQUEST['Items']) && $_REQUEST['Items'] != '')
		{
			$showMonthlyItem = 'Items';
		}
		$data['showMonthlyItem'] = $showMonthlyItem;
		$data['timeDuration'] = $timeDuration;
		$data['monthlselect'] = $this->sqlModel->_get_list_of_months();		
		$data['currMonthDomData'] = $this->sqlModel->getCurrentMonthDomData($showMonthlyItem, $timeDuration);
		$data['transferData'] = $this->sqlModel->getTransferData();
		$data['catExpPercentData'] = $this->sqlModel->getExpenditureByCatInPercent();
		$data['allMonthsData'] = $this->sqlModel->allMonthsData();
		$data['acctTotalAmt'] = $this->sqlModel->_get_total_exp_amount_by_account();
		if(isset($_REQUEST['showItemYear']) && $_REQUEST['showItemYear'] != '')
		{
			$_SESSION['exp_item_year'] = $_REQUEST['showItemYear'];
		}
		else
		{
			$_SESSION['exp_item_year'] = CURRENT_YEAR;			
		}		
		$content = $this->load->view('home', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);
		
	}
	public function showExpByItemCategoryMonthly($exp_display_type=DEFAULT_EXP_DISPLAY)
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$_SESSION['EXP_DISPLAY_TYPE'] = $exp_display_type;
		if(isset($_REQUEST['showItemYear']) && $_REQUEST['showItemYear'] != '')
		{
			$_SESSION['exp_item_year'] = $_REQUEST['showItemYear'];
		}
		else
		{
			$_SESSION['exp_item_year'] = CURRENT_YEAR;			
		}		
		$data['listOfMonthsByExpTypeExpenditure'] = $this->sqlModel->_get_list_of_months_by_exp_type_expenditure();
		$data['catTotalExpAmt'] = $this->sqlModel->_get_total_exp_amount_by_category();
		$data['catTotalExpAmtActual'] = $this->sqlModel->_get_total_exp_amount_by_category(0,'expire');
		$data['catTotalDepoAmt'] = $this->sqlModel->_get_total_depo_amount_by_category();
		$data['catTotalDepoAmtActual'] = $this->sqlModel->_get_total_depo_amount_by_category(0,'expire');
		$data['expByItemCategoryMonthly'] = $this->sqlModel->getExpByItemCategoryMonthly();
		$content = $this->load->view('expenditure_by_category_type', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);
	}
	public function showExpForCategoryByAccountsMonthly($cat_id=0,$item_id=0,$exp_display_type=DEFAULT_EXP_DISPLAY)
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$_SESSION['EXP_DISPLAY_TYPE'] = $exp_display_type;
		if(isset($_REQUEST['showItemYear']) && $_REQUEST['showItemYear'] != '')
		{
			$_SESSION['exp_item_year'] = $_REQUEST['showItemYear'];
		}
		else
		{
			$_SESSION['exp_item_year'] = CURRENT_YEAR;			
		}		
		$data['itemCatName'] = $this->sqlModel->_get_item_cat_name_by_id($cat_id);
		$data['itemCatTotalExpAmt'] = $this->sqlModel->_get_total_exp_amount_by_category($cat_id,$item_id);
		$data['itemCatTotalExpAmtActual'] = $this->sqlModel->_get_total_exp_amount_by_category($cat_id,$item_id,'expire');
		$data['itemCatTotalDepoAmt'] = $this->sqlModel->_get_total_depo_amount_by_category($cat_id,$item_id);
		$data['itemCatTotalDepoAmtActual'] = $this->sqlModel->_get_total_depo_amount_by_category($cat_id,$item_id,'expire');
		$data['catId'] = $cat_id;
		$data['itemId'] = $item_id;
		$data['listOfMonthsByExpTypeExpenditure'] = $this->sqlModel->_get_list_of_months_by_exp_type_expenditure();
		$data['expForItemCategoryByAccountsMonthly'] = $this->sqlModel->getExpForCategoryByAccountsMonthly($cat_id,$item_id);
		$data['restOfCategories'] = $this->sqlModel->_get_categories_list($cat_id);
		if($item_id > 0)
		$data['restOfItems'] = $this->sqlModel->_get_items_list_with_skip($cat_id,$item_id,'category');
		$content = $this->load->view('view_exp_for_category_by_accounts_monthly', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);	
	}
	public function showExpForCategoryByItemsMonthly($cat_id=0,$item_id=0,$exp_display_type=DEFAULT_EXP_DISPLAY)
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$_SESSION['EXP_DISPLAY_TYPE'] = $exp_display_type;
		if(isset($_REQUEST['showItemYear']) && $_REQUEST['showItemYear'] != '')
		{
			$_SESSION['exp_item_year'] = $_REQUEST['showItemYear'];
		}
		else
		{
			$_SESSION['exp_item_year'] = CURRENT_YEAR;			
		}		
		$data['itemCatName'] = $this->sqlModel->_get_item_cat_name_by_id($cat_id);
		$data['itemCatTotalExpAmt'] = $this->sqlModel->_get_total_exp_amount_by_category($cat_id,$item_id);
		$data['itemCatTotalExpAmtActual'] = $this->sqlModel->_get_total_exp_amount_by_category($cat_id,$item_id,'expire');
		$data['itemCatTotalDepoAmt'] = $this->sqlModel->_get_total_depo_amount_by_category($cat_id,$item_id);
		$data['itemCatTotalDepoAmtActual'] = $this->sqlModel->_get_total_depo_amount_by_category($cat_id,$item_id,'expire');
		$data['catId'] = $cat_id;
		$data['itemId'] = $item_id;
		$data['itemName'] = $this->sqlModel->_get_item_name_by_id($item_id);
		$data['listOfMonthsByExpTypeExpenditure'] = $this->sqlModel->_get_list_of_months_by_exp_type_expenditure();
		$data['expForItemCategoryByItemsMonthly'] = $this->sqlModel->getExpForCategoryByItemsMonthly($cat_id,$item_id);
		$data['restOfCategories'] = $this->sqlModel->_get_categories_list($cat_id);
		if($item_id > 0)
		$data['restOfItems'] = $this->sqlModel->_get_items_list_with_skip($cat_id,$item_id,'category');
		$content = $this->load->view('view_exp_for_category_by_items_monthly', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);	
	}
	public function showExpForAccountByItemsMonthly($acct_id=0,$item_id=0,$exp_display_type=DEFAULT_EXP_DISPLAY)
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$_SESSION['EXP_DISPLAY_TYPE'] = $exp_display_type;
		if(isset($_REQUEST['showItemYear']) && $_REQUEST['showItemYear'] != '')
		{
			$_SESSION['exp_item_year'] = $_REQUEST['showItemYear'];
		}
		else
		{
			$_SESSION['exp_item_year'] = CURRENT_YEAR;			
		}		
		$data['acctName'] = $this->sqlModel->_get_acct_name_by_id($acct_id);
		$data['acctTotalExpAmt'] = $this->sqlModel->_get_total_exp_amount_by_account($acct_id,$item_id);
		$data['acctTotalExpAmtActual'] = $this->sqlModel->_get_total_exp_amount_by_account($acct_id,$item_id,'expire');
		$data['acctTotalDepoAmt'] = $this->sqlModel->_get_total_depo_amount_by_account($acct_id,$item_id);
		$data['acctTotalDepoAmtActual'] = $this->sqlModel->_get_total_depo_amount_by_account($acct_id,$item_id,'expire');
		$data['acctId'] = $acct_id;
		$data['itemId'] = $item_id;
		$data['itemName'] = $this->sqlModel->_get_item_name_by_id($item_id);
		$data['listOfMonthsByExpTypeExpenditure'] = $this->sqlModel->_get_list_of_months_by_exp_type_expenditure();
		$data['expForAccountByItemsMonthly'] = $this->sqlModel->getExpForAccountByItemsMonthly($acct_id,$item_id);
		$data['restOfAccounts'] = $this->sqlModel->_get_accounts_list($acct_id);
		if($item_id > 0)
		$data['restOfItems'] = $this->sqlModel->_get_items_list_with_skip($acct_id,$item_id,'account');
		$content = $this->load->view('view_exp_for_account_by_items_monthly', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);
	}
	public function showMonthlyStatusCurrent()
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		if(isset($_REQUEST['showMonth']) && $_REQUEST['showMonth'] != '')
		{
			$showMonYrArr = explode('-', $_REQUEST['showMonth']);
			$_SESSION['curr_mon'] = $showMonYrArr[0];
			$_SESSION['curr_yr'] = $showMonYrArr[1];
		}
		else
		{
			$_SESSION['curr_mon'] = CURRENT_MONTH;
			$_SESSION['curr_yr'] = CURRENT_YEAR;			
		}
		if(isset($_REQUEST['monthlySortBy']) && $_REQUEST['monthlySortBy'] != '')
		{
			if($_REQUEST['monthlySortBy'] == $_SESSION['monthlySortBy'])
			{
				if($_SESSION['monthlySortOrder'] == 'ASC'){
					$_SESSION['monthlySortOrder'] = 'DESC';
				}else{
					if($_SESSION['monthlySortOrder'] == 'DESC'){
						$_SESSION['monthlySortOrder'] = 'ASC';
					}
				}
			}
			else{
				$_SESSION['monthlySortBy'] = $_REQUEST['monthlySortBy'];
				$_SESSION['monthlySortOrder'] = 'ASC';
			}
		}
		else
		{
			$_SESSION['monthlySortBy'] = 'default';
			$_SESSION['monthlySortOrder'] = 'ASC';			
		}		
		if(isset($_POST['modfldmonyear']) && $_POST['modfldmonyear'] != '')
		{
			$this->sqlModel->addModifyMonthlyData($_POST);
		}
		$data['monthlselect'] = $this->sqlModel->_get_list_of_months();
		$data['monthlyData'] = $this->sqlModel->getMonthlyData();		
		$content = $this->load->view('monthly_status_current', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);
	}
	public function dailyDraw()
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$data['drawValArr'] = $this->sqlModel->_get_draw_value();			
		$content = $this->load->view('draw/drawWinnerPage', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);
	}
	public function showPrize()
	{
		$this->checkSignIn();
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		
		if(isset($_REQUEST['id']) && isset($_REQUEST['prize']))
		{
			$data['showPrizeArr'] = $this->sqlModel->_get_show_prize($_REQUEST['id'],$_REQUEST['prize'],$_REQUEST['round']);			
		}
		else{
			$data['showPrizeArr'] = $this->sqlModel->_get_show_prize(0,0,0);
		}
		$content = $this->load->view('draw/drawWinnerList', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */