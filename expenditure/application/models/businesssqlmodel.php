<?php
if (!isset($_SESSION)) { 
  session_start();
}
class businesssqlmodel extends CI_Model
{
	public function __construct() {
		
    }
	function getTicketBoxData()
	{
		$chkStr = "SELECT *    
			FROM busi_tickets"; 
		$chkResult = $this->db->get_multiple_tables_records($chkStr);
		return $chkResult;		
	}

}
?>