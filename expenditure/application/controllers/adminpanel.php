<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}
class Adminpanel extends MY_Controller {

	public function __construct() {
        parent::__construct();
		$this->sqlModel->_setFunctionHistory(__method__);
    }		 
	public function index()
	{
		$this->sqlModel->_setFunctionHistory(__method__);
		if((isset($_POST['uname']) && $_POST['uname'] != '') && (isset($_POST['psw']) && $_POST['psw'] != ''))
		{
			if($this->checkLogin($_POST['uname'],$_POST['psw']))
			{
				$this->showAdminDashboard();
			}
		}
		else
		{
			if($this->isLogged())
			{
				$this->showAdminDashboard();
			}
			else
			{
				$content = $this->load->view('login', '', true);
				$this->load_template($content);
			}
			
		}
	}

	public function doLogout()
	{
		$this->sqlModel->_setFunctionHistory(__method__);
		$this->sqlModel->userLogout();
	}	
	public function showAdminDashboard()
	{
		$this->checkSignIn();
		$this->sqlModel->_setFunctionHistory(__method__);
		$data['allTimeData'] = $this->sqlModel->_get_all_time_status();
		$data['accountsData'] = $this->sqlModel->_get_accounts_list();
		$data['categoryData'] = $this->sqlModel->_get_item_cat_list();
		$data['catExpPercentData'] = $this->sqlModel->getExpenditureByCatInPercent();
		$data['itemsData'] = $this->sqlModel->_get_items_list();
		$data['colorData'] = $this->sqlModel->_get_all_color_code();
		$content = $this->load->view('adminpanel/home', $data, true);
		$this->load_admin_template($content,__method__);
	}
	public function getListing($what='items',$action="add",$id=0,$msg="")
	{
		$this->checkSignIn();
		$this->sqlModel->_setFunctionHistory(__method__);
		$data['what'] = $what;
		$data['action'] = $action;
		$data['msg'] = $msg;
		$data['id'] = $id;
		$data['addEditFormData'] = $this->adminPanelSqlModel->getListingData($what,$id);
		/*************specific list as required *****/
		$data['colorCodeList'] = $this->adminPanelSqlModel->getColorCodeList();
		$data['categoryList'] = $this->adminPanelSqlModel->getCategoryList();
		$data['accountList'] = $this->adminPanelSqlModel->getAccountList();
		/*************specific list as required *****/
		$data['listingData'] = $this->adminPanelSqlModel->getListingData($what,0);
		$content = $this->load->view('adminpanel/listing', $data, true);
		$currMethod = __method__ . ' | ' . ucfirst($what);
		if($id > 0)
			$currMethod = ucfirst($what) . ucfirst($action);
		$this->load_admin_template($content,$currMethod);
	}	
	public function postData($id=0)
	{
		$this->checkSignIn();
		$this->sqlModel->_setFunctionHistory(__method__);
		if(isset($_POST['what']) && $_POST['what'] !=''){
			$msg = $this->adminPanelSqlModel->postFormData($_POST);
			$this->getListing($_POST['what'],'add',0, $msg);
		}else{
			$this->getListing();
		}			
	}
}
/* End of file adminpanel.php */
/* Location: ./application/controllers/adminpanel.php */
?>