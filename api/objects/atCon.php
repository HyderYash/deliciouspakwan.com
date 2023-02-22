<?php
include_once '../config/database.php';
class ActivityTracerConnection {
 
    // database connection and table name
    public $db;
	private $algorithm = 'AES-256-CBC';
	private $pass = '1234567890abcdef1234567890abcdef';
	private $iv = '1234567890abcdef';
	
    // constructor with $db as database connection
    public function __construct(){
		// instantiate database and settings object
		$this->db = new DB();
    }
	public function prepareConnection(){
		ini_set('max_execution_time', '300');
		$this->cors();
		// instantiate database and settings object
		$this->db = new DB();
		return $this->db;
	}
	public function returnConnectionElements(){
		$this->cors();
		$tempArray->HOST = $this->encryptText(DB_HOST);
		$tempArray->USER = $this->encryptText(DB_USER);
		$tempArray->PASSWORD = $this->encryptText(DB_PASS);
		$tempArray->DATABASE = $this->encryptText(DB_NAME);
		
		$retArray = array($tempArray);
		return $retArray;
	}
	function cors() {
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');
		}
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
			exit(0);
		}
		//echo "You have CORS!";
	}
	public function encryptText($text) {
		return bin2hex(openssl_encrypt($text, $this->algorithm, $this->pass, 1, $this->iv));
	}
}