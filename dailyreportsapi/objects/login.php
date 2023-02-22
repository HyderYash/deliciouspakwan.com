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
	public function signUp($postData)
	{
		$msg = '';
		$res = false;
		if((isset($postData->uname) && $postData->uname != '') && (isset($postData->psw) && $postData->psw != '')  && (isset($postData->uemail) && $postData->uemail != ''))
		{
			if($this->checkUserNameForSignUp($postData->uname,$postData->uemail))
			{
				$msg = $this->createNewUser($postData->uname,$postData->psw,$postData->uemail);
				$msg = $msg . ' SignUp process successful!! Please Login to continue..';
				$res = true;
			}
			else{
				$msg = 'Username or Email already exists!!';
			}
		}
		else{
			$msg = 'Enter Username and Password to begin SignUp process..';
		}
		$retArr = array($res,$msg);
		return $retArr;
	}
	public function encrypt_decrypt($string, $action = 'encrypt')
	{
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
		$secret_iv = '5fgf5HJ5g27'; // user define secret key
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
		if ($action == 'encrypt') {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} else if ($action == 'decrypt') {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}	
	public function checkUserNameForSignUp($uname,$uemail)
	{
		$currDate = date('Y-m-d h:i:s');
		$chkStr = "SELECT USER_NAME   
		FROM dr_users  
		WHERE USER_NAME = '" .  trim($uname) . "'"; 
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 0)
		{
			$chkStr1 = "SELECT USER_EMAIL    
			FROM dr_users  
			WHERE lower(USER_EMAIL) = '" .  strtolower(trim($uemail)) . "'"; 
			$chkPtr1 = $this->db->get_sql_exec($chkStr1);
			$chkRows1 = $this->db->get_db_num_rows($chkPtr1);
			if($chkRows1 == 0)
			{
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function createNewUser($user_name, $user_pass,$user_email)
	{
		$currDate = date('Y-m-d h:i:s');
		$chkStr = "SELECT ID,USER_NAME 
		FROM dr_users  
		WHERE USER_NAME = '" .  trim($user_name) . "'"; 
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 0)
		{
			/*****Create User *** Table: dr_users */
			$token = md5($user_name . date('Y-m-d h:i:s')); 
			$qry = "INSERT INTO dr_users (`ID`, `USER_NAME`, `USER_PWD`, `USER_EMAIL`,`USER_TOKEN`, `USER_LAST_LOGGED`, `USER_TYPE`, `USER_STATUS`)  VALUES 
			(NULL, '" . trim($user_name) . "', '" . trim($this->encrypt_decrypt($user_pass)) . "', '" . trim($user_email) . "', '" . $token . "', '" . $currDate . "', 'U', 'Y')";
			$ptr = 	$this->db->get_sql_exec($qry);
			$last_user_id = $this->db->get_last_insert_id();
			$msg = "User: [" . $user_name . "] is successfully created.";
		}
		else{
			$msg = "User: [" . $user_name . "] is already exists. Please try another username";
		}
		return $msg;
	}

	// read products with pagination
	public function checkLogin($postData){
		$res = false;
		$msg = '';
		$userRec = array();
		$currDate = date('Y-m-d h:i:s');
		$aa = 'ram@1234';
		//print $this->encryptIt($aa) . '======================' . $this->decryptIt($aa);
		$chkStr = "SELECT ID,USER_NAME, USER_TYPE,USER_LAST_LOGGED   
		FROM dr_users  
		WHERE USER_NAME = '" .  trim($postData->uname) . "' 
		AND USER_PWD = '" .  trim($this->encrypt_decrypt($postData->psw)) . "'
		AND USER_TYPE = '" . trim($postData->utype) . "'"; 
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 1){
			$chkUserResult = $this->db->get_one_record($chkStr);
			$token = md5($chkUserResult['USER_NAME'] . date('Y-m-d h:i:s')); 
			$updatePtr = $this->db->set_multiple_fields('dr_users', 'USER_TOKEN = "' . $token . '", USER_LAST_LOGGED = "' . $currDate . '"', 'ID = "' . $chkUserResult['ID'] . '"');
			$res = true;
			$userRec = $chkUserResult;
			$msg = $token;
		}
		else{
			$msg = 'Login Failed';
		}
		$retArr = array($res,$msg, $userRec);
		return $retArr;		
	}
	public function forgotPassword($postData) {
		$msg = '';
		$returnArr = array();
		$sql = "SELECT *  
		 FROM `dr_users` 
		 WHERE LOWER(USER_EMAIL) = '" . strtolower(trim($postData->EMAIL_ADDRESS)) . "'"; //die;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows == 0) {
			$msg = "Sorry, We didn't find this email address in our database.";
			$returnArr = array(false, $msg);
		}
		if($chkSqlRows == 1) {
			$chkResult = $this->db->get_one_record($sql);
			$userName = $chkResult['USER_NAME'];
			//$password = "aakash@1234";
			$password = $this->generateRandomString(6);
			$updatePtr = $this->db->set_multiple_fields('dr_users', 'USER_PWD = "' . trim($this->encrypt_decrypt($password)) . '"', 'ID = "' . $chkResult['ID'] . '"');
			$emailMsg = "Your UserName is = " . $userName . "<br>Your Password is = " . $password;
			//print($message);
			if(ENV_NAME != 'local'){
				$this->sendEmailFromAdmin($chkResult['USER_EMAIL'],'Password Recovery',$message);
			}
			$msg = "Your password has been sent to your email address." . '[' . $password . ']';
			$returnArr = array(true, $msg);
		}
		return $returnArr;
	}
	public function checkAndChangePassword($postData) {
		$sql = "SELECT *  
		 FROM `dr_users` 
		 WHERE LOWER(USER_PWD) = '" . trim($this->encrypt_decrypt($postData->CURRENT_PASSWORD)) . "' AND ID = " . $postData->USER_ID; //die;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows == 0) {
			$msg = "User with this Password does not exist in our Database.";
			$returnArr = array(false, $msg);
		}
		if($chkSqlRows == 1) {
			$updatePtr = $this->db->set_multiple_fields('dr_users', 'USER_PWD = "' . trim($this->encrypt_decrypt($postData->NEW_PASSWORD)) . '"', 'ID = "' . $postData->USER_ID . '"');
			$msg = "Changed the Password";
			$returnArr = array(true, $msg);
		}
		return $returnArr;
	}
	public function getUsers() {
		$sql = "SELECT *  
				 FROM `dr_users` 
				 WHERE USER_TYPE != 'A'";
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function updateUserStatus($postData) {
		$updatePtr = $this->db->set_multiple_fields('dr_users', 'USER_STATUS = "' . $postData->USER_STATUS . '"', 'ID = "' . $postData->ID . '"');
		// execute the query
		if($updatePtr == 1){
			return true;
		}
		return false;
	}
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}	
	public function sendEmailFromAdmin($_to, $subject = '', $extraMessage = null){
        $_message = "<br><br>" . $extraMessage;
		// Always set content-type when sending HTML email
		$_headers = "MIME-Version: 1.0" . "\r\n";
		$_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $_headers .= 'From: info@deliciouspakwan.com' . "\r\n";
		$_subject = 'Delicious Pakwan | ' . $subject;
		mail($_to, $_subject, $_message, $_headers);
	}
}