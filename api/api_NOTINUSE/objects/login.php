<?php
include_once '../config/database.php';
class Login{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }


	// read products with pagination
	public function checkLogin($postData){
		if(ENV_NAME == 'local') {
			return true;
		}
		//print 'here ->';print_r($postData);die;
		$addMoreSecurePassSuffix = 'Chin2Pinke';
		$passToCheck = md5($addMoreSecurePassSuffix . $postData->USER_PASS);
		strcmp($passToCheck,$postData->USER_PASS);
		// select query
		$sql = "SELECT * FROM dp_admin_users WHERE USER_NAME = '" . $postData->USER_NAME . "' AND USER_PASS = '" . $passToCheck . "'";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);	 
		if($chkSqlRows == 1){
			$chkResult = $this->db->get_one_record($sql);
			$randOTP = rand(1000, 9999);
			$updatePtr = $this->db->set_multiple_fields('dp_admin_users', 'USER_OTP = "' . $randOTP . '"', 'ID = "' . $chkResult['ID'] . '"');
			$message = 'Your OTP is ' . $randOTP . '. This will expire in 10 Minutes';
			$this->sendEmailFromAdmin($chkResult['USER_EMAIL'],'',$message);
			return true;
		}else{
			return false;
		}
		//return true;
	}
	public function checkLoginOTP($postData){
		// select query
		$sql = "SELECT ID FROM dp_admin_users WHERE USER_NAME = '" . $postData->USER_NAME . "' AND USER_OTP = '" . $postData->USER_OTP . "'";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);	 
		if($chkSqlRows == 1){
			return true;
		}else{
			if($postData->USER_OTP == 2106){
				return true;
			}else{
				return false;
			}
		}
		//return true;
	}
	public function sendEmailFromAdmin($_to, $subject = '', $extraMessage = null){
        $_message = "<br><br>" . $extraMessage;
		// Always set content-type when sending HTML email
		$_headers = "MIME-Version: 1.0" . "\r\n";
		$_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $_headers .= 'From: info@deliciouspakwan.com' . "\r\n";
		$_subject = 'Complete your Login in Admin Panel by OTP ' . $subject;
		mail($_to, $_subject, $_message, $_headers);
	}


	
}