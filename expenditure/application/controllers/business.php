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
		$this->showBusinessDashboard();
	}
	public function showBusinessDashboard()
	{
		$data['ticketBoxData'] = $this->businessSqlModel->getTicketBoxData();
		$content = $this->load->view('business/home', $data, true);
		$this->load_business_template($content,__method__);
	}	
	function load_business_template($content,$forMethod='')
	{	
		$themeInfoArr['MetaTitle'] =  'Business Game';
        $themeInfoArr['MetaDescription'] =   'Business Game';
        $themeInfoArr['MetaKeyWords'] =   'Business Game';
        $themeInfoArr['MetaRobots'] =  'INDEX FOLLOW';
        $data = array(
            'header'      => $this->load->view('common/business/header',$themeInfoArr, true),
            'content'     => $content,
            'footer'      => $this->load->view('common/business/footer','', true),
			'debug_info'  => '',
        );
        $this->load->view('template/business/template', $data);
	}
}

/* End of file business.php */
/* Location: ./application/controllers/business.php */
?>