<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SESSION)) { 
  session_start();
}

class Sitemap extends MY_Controller {

	public function __construct() {
        parent::__construct();
		$this->load->library('sitemap_lib', '', 'sitemap');
	}
    public function index()
    {
		 // is_cli_request() is provided by default input library of codeigniter
        if($this->input->is_cli_request())
        {		
			$sitemapContentArr = $this->sqlModel->getSitemapContent();
			$this->sitemap->displaySitemap($sitemapContentArr);
		}
		else
        {
            echo "You dont have access";
        }
    }
	function createSitemapForSite(){
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$data['sitemapContentArr'] = $this->sqlModel->getSitemapContentForDisplayInSite();
		$this->overrideMetaDataInfo($data['sitemapContentArr'], 'Sitemap');
		$_SESSION['showVisitorCount'] = 1;		
		$content = $this->load->view('sitemap', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);	
	}
	
	function pageNotFound(){
		
		$funcId = $this->sqlModel->_setFunctionHistory(__method__);
		$data['sitemapContentArr'] = $this->sqlModel->getSitemapContentForDisplayInSite();
		$this->overrideMetaDataInfo(array(), 'pageNotFound');
		$_SESSION['showVisitorCount'] = 1;
		$content = $this->load->view('page_not_found', $data, true);
		$this->sqlModel->_getExecutionTime($funcId);
		$this->load_template($content,__method__);			
	}

}
/* End of file sitemap.php */
/* Location: ./application/controllers/sitemap.php */
?>	