<?php
include_once '../config/database.php';
class Services{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	public function getServices(){
		// select query
		$sql = "SELECT ID as id, SERVICE_NAME,STATUS,LAST_UPDATED FROM dr_services ORDER BY LAST_UPDATED ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function updateServices($postData) {
		$updatePtr = $this->db->set_multiple_fields('dr_services', 'SERVICE_NAME = "' . $postData->SERVICE_NAME . '",STATUS = "' . $postData->STATUS . '",LAST_UPDATED = "' . $postData->LAST_UPDATED . '"', 'ID = "' . $postData->id . '"');
		// execute the query
		if($updatePtr == 1){
			return true;
		}
		return false;
	}
	public function addService($postData) {

		if(trim($postData->SERVICE_NAME) != "") {
			$currDateTime = date('Y-m-d h:i:s');
			$sql = "SELECT ID  
					 FROM `dr_services` 
					 WHERE LOWER(SERVICE_NAME) = '" . strtolower(trim($postData->SERVICE_NAME)) . "'"; //die;
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			
			if($chkSqlRows == 0){
				$qry = "INSERT INTO dr_services (`ID`, `SERVICE_NAME`, `STATUS`, `LAST_UPDATED`)  VALUES 
				(NULL, '" . trim($postData->SERVICE_NAME) . "', '" . $postData->STATUS . "','" . $currDateTime . "')";
				$ptr = 	$this->db->get_sql_exec($qry);
				//Creating Service Table
				//$this->createServiceTable($postData->SERVICE_NAME, $postData->SERVICECOLUMNANDDATATYPE[0]);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	function getDataType($arr, $key){
		if (array_key_exists($key,$arr)){
			return $arr[$key];
		}
	}
	function createServiceTable ($tblName,$fldArr){
		if(trim($tblName) != ''){
			
			//$sql = "DROP TABLE IF EXISTS `" . strtolower('dr_' . $tblName) . "`;";
			//$ptr = 	$this->db->get_sql_exec($sql);
			$sql ='';
			$sql .= "CREATE TABLE IF NOT EXISTS `" . strtolower('dr_' . $tblName) . "` (";
			$autoIncrement = '';
			$tmp = '';
			$dataTypeArr = array('INT' => '(11)', 'VARCHAR' => '(255)', 'FLOAT' => '(10,2)', 'ENUM' => '("Y","N")', 'DATETIME' => '');
			$primaryKey = 'ID';
			$tmp .= "`ID` INT(11) NOT NULL AUTO_INCREMENT,";
			$tmp .= "`USER_ID` INT(11) NOT NULL,";
			foreach($fldArr as $rec){
				$tmp .= "`" . $rec->ServiceColumn . "` " . $rec->ServiceColumnType . $this->getDataType($dataTypeArr, $rec->ServiceColumnType) . " NOT NULL,";
			}
			$tmp .= "PRIMARY KEY (`" . $primaryKey . "`)";
			$tmp .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			$sql .= $tmp;
			//print $sql;
			$ptr = 	$this->db->get_sql_exec($sql);
		}	
	}
	public function getServiceUserSubscribe($postData) {
		$chkResult =array();
		$sql = "SELECT * FROM `dr_services` WHERE ID IN (		
		SELECT SERVICE_ID 
				 FROM `dr_services_user_subscribe` 
				 WHERE USER_ID = '" . $postData->USER_ID . "' 
				 AND SUBS_STATUS = 'Y' 
		) AND STATUS = 'Y'";
		$chkResult = $this->db->get_multiple_tables_records($sql);
		return $chkResult;
	}
	public function getServiceUserSubscribeNow($postData) {
		$chkResult =array();
		$sql = "SELECT * FROM `dr_services` WHERE ID NOT IN (		
		SELECT SERVICE_ID 
				 FROM `dr_services_user_subscribe` 
				 WHERE USER_ID = '" . $postData->USER_ID . "' 
				 AND SUBS_STATUS = 'Y' 
		) AND STATUS = 'Y'";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows > 0){
			$chkResult = $this->db->get_multiple_tables_records($sql);
		}	
		
		return $chkResult;
	}
	public function subscribeUserToService($postData) {
		$currDateTime = date('Y-m-d h:i:s');
		if(trim($postData->SERVICE_ID) != "" && trim($postData->USER_ID)) {
			$sql = "SELECT ID  
					 FROM `dr_services_user_subscribe` 
					 WHERE SERVICE_ID = '" . $postData->SERVICE_ID . "' AND USER_ID = '" . $postData->USER_ID . "'"; //die;
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows == 0){			
				$qry = "INSERT INTO dr_services_user_subscribe (`ID`, `SERVICE_ID`, `USER_ID`, `SUBS_STATUS`, `SUBS_DATE`)  VALUES 
					(NULL, '" . $postData->SERVICE_ID . "', '" . $postData->USER_ID . "','Y','" . $currDateTime . "')";
				$ptr = 	$this->db->get_sql_exec($qry);
			}
			if($chkSqlRows == 1){
				$updatePtr = $this->db->set_multiple_fields('dr_services_user_subscribe', 'SUBS_STATUS = "Y", SUBS_DATE = "' . $currDateTime . '"', 'USER_ID = "' . $postData->USER_ID . '" AND SERVICE_ID = "' . $postData->SERVICE_ID . '"');
			}
			return true;
		}
	}
	public function unSubscribeUserToService($postData) {
		$currDateTime = date('Y-m-d h:i:s');
		if(trim($postData->SERVICE_ID) != "" && trim($postData->USER_ID)) {
			$updatePtr = $this->db->set_multiple_fields('dr_services_user_subscribe', 'SUBS_STATUS = "N", SUBS_DATE = "' . $currDateTime . '"', 'USER_ID = "' . $postData->USER_ID . '" AND SERVICE_ID = "' . $postData->SERVICE_ID . '"');
			// execute the query
			if($updatePtr == 1){
				return true;
			}
				return false;
		}
	}
	public function getServiceNameByID($postData) {
		$sql = "SELECT SERVICE_NAME FROM dr_services WHERE ID = " . $postData->SERVICE_ID;
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function userProductSettings($postData) {
		//echo '<pre>';print_r($postData);die;
		$editedArr = array();
		foreach ($postData->PRODUCTINFO as $rec){
			if(isset($rec->ID)){
				$sql = "SELECT ID  
					 FROM `dr_services_products` 
					 WHERE SERVICE_ID = '" . $postData->SERVICE_ID . "' AND USER_ID = '" . $postData->USER_ID . "' AND ID = '" . $rec->ID . "'"; //die;				
			}else{
				$sql = "SELECT ID  
					 FROM `dr_services_products` 
					 WHERE SERVICE_ID = '" . $postData->SERVICE_ID . "' AND USER_ID = '" . $postData->USER_ID . "' AND LOWER(PRODUCT_NAME) = '" . strtolower(trim($rec->PRODUCT_NAME)) . "'"; //die;				
			}		 
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows == 0) {
				$qry = "INSERT INTO dr_services_products 
				(`ID`, `SERVICE_ID`, `PRODUCT_NAME`, `USER_ID`)  VALUES 
					(NULL, '" . trim($postData->SERVICE_ID) . "', '" . trim($rec->PRODUCT_NAME) . "'," . $postData->USER_ID . ")";
				$ptr = 	$this->db->get_sql_exec($qry);
				$editedArr[] = $this->db->get_last_insert_id();
			}
			if($chkSqlRows == 1) {
				$chkResult = $this->db->get_one_record($sql);
				$updatePtr = $this->db->set_multiple_fields('dr_services_products', 'PRODUCT_NAME = "' . trim($rec->PRODUCT_NAME) . '"', 'USER_ID = "' . $postData->USER_ID . '" AND SERVICE_ID = "' . $postData->SERVICE_ID . '" AND ID = "' . $chkResult['ID'] . '"');
				$editedArr[] = $chkResult['ID'];
			}
		}
		
		/* DELETING UNSELECTED PROUCTS AND PRODUCT DETAILS */
		$sql = "DELETE FROM dr_services_products_details 
			WHERE USER_ID = '" . $postData->USER_ID . "'
			AND PRODUCT_ID NOT IN (" . implode(",",$editedArr) . ")";
			$chkSqlPtr = $this->db->get_sql_exec($sql);
		$sql = "DELETE FROM dr_services_products 
			WHERE USER_ID = '" . $postData->USER_ID . "' 
			AND SERVICE_ID = '" . $postData->SERVICE_ID . "'
			AND ID NOT IN (" . implode(",",$editedArr) . ")";
			$chkSqlPtr = $this->db->get_sql_exec($sql);			
		return true;
	}
	public function getProductsInfo($postData) {
		$sql = "SELECT ID, SERVICE_ID, PRODUCT_NAME  
				 FROM `dr_services_products` 
				 WHERE SERVICE_ID = '" . $postData->SERVICE_ID . "' AND USER_ID = '" . $postData->USER_ID . "'";
		$result = $this->db->get_multiple_tables_records($sql);
		// return values from database
		return $result;
	}
	public function getProductDetails($postData) {
		$retArr = array();
		$finalArr = array();
		$prodDetailsArr = array();
		$userProdArr = array();
		$sql = "SELECT s.ID as S_ID,sp.ID as P_ID, sp.PRODUCT_NAME, spd.*   
				FROM `dr_services` as s, `dr_services_products` as sp, `dr_services_products_details` as spd 
				WHERE s.ID = sp.SERVICE_ID 
				AND sp.ID = spd.PRODUCT_ID 
				AND sp.USER_ID = spd.USER_ID
				AND	spd.USER_ID = " . $postData->USER_ID . "
				AND sp.SERVICE_ID = " . $postData->SERVICE_ID . " 
				AND MONTH(spd.ENTRY_DATE) = " . $postData->MONTH . " 
				AND YEAR(spd.ENTRY_DATE) = " . $postData->YEAR;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows > 0) {		
			$prodDetailsArr = $this->db->get_multiple_tables_records($sql);
		}
		$sql = "SELECT ID,PRODUCT_NAME   
				 FROM `dr_services_products` 
				 WHERE SERVICE_ID = '" . $postData->SERVICE_ID . "' AND USER_ID = '" . $postData->USER_ID . "'";
		$userProdArr = $this->db->get_multiple_tables_records($sql);
		$days = cal_days_in_month(CAL_GREGORIAN,$postData->MONTH,$postData->YEAR);
		for($d = 1; $d <= $days; $d++){
			$uProdArr = array();
			foreach($userProdArr as $rec){
				$uProdArr[] = array(
					'PRODUCT_ID' => $rec['ID'],
					'PRODUCT_NAME' => $rec['PRODUCT_NAME'],
					'PRODUCT_QNT' => '',
					'PRODUCT_AMT' => '',
				);
			}
			$today = $d;
			if($d <= 9){
				$today = '0'. $d;
			}
			$month = $postData->MONTH;
			if($month < 9){
				$month = '0'. $postData->MONTH;
			}
			$cusDate = $postData->YEAR . '-' . $month . '-' . $today;
			$retArr[$cusDate] = $uProdArr;
		}
		foreach($retArr as $key => $val){
			if(count($prodDetailsArr) > 0){
				foreach($prodDetailsArr as $rec){
					$tmpEntryDateArr = explode(" ", $rec['ENTRY_DATE']);
					if($tmpEntryDateArr[0] == $key){
						foreach($val as $prdKey => $prdVal){
							if($prdVal['PRODUCT_ID'] == $rec['P_ID']){
								$retArr[$key][$prdKey]['PRODUCT_QNT'] = $rec['QNT'];
								$retArr[$key][$prdKey]['PRODUCT_AMT'] = $rec['AMT'];
							}
						}
					}
				}
			}
		}
		//print '<pre>';print_r($retArr);die;
		// return values from database
		$finalArr['records'][] = $retArr;
		return $finalArr;
	}
	public function checkUserProductsSettings($postData) {
		$sql = "SELECT *  
					 FROM `dr_services_products` 
					 WHERE SERVICE_ID = '" . $postData->SERVICE_ID . "' AND USER_ID = " . $postData->USER_ID;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows > 0) {
			// Means Products Exists
			return true;
		} else {
			return false;
		}
	}
	public function saveUserProductDetails($postData) {
		$sql = "SELECT ID  
					 FROM `dr_services_products_details` 
					 WHERE PRODUCT_ID = '" . $postData->PRODUCT_ID . "' AND USER_ID = '" . $postData->USER_ID . "' AND ENTRY_DATE = '" . $postData->ENTRY_DATE . "'"; //die;
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows == 0) {
				$qry = "INSERT INTO dr_services_products_details 
				(`ID`, `PRODUCT_ID`, `QNT`, `AMT`, `ENTRY_DATE`, `USER_ID`)  VALUES 
					(NULL, '" . trim($postData->PRODUCT_ID) . "', '" . trim($postData->PRODUCT_QNT) . "'," . $postData->PRODUCT_AMT . ",'" . $postData->ENTRY_DATE . "'," . $postData->USER_ID . ")";
				$ptr = 	$this->db->get_sql_exec($qry);
			}
			if($chkSqlRows == 1) {
				$updatePtr = $this->db->set_multiple_fields('dr_services_products_details', 'QNT = "' . $postData->PRODUCT_QNT . '", AMT = "' . $rec->PRODUCT_AMT . '"', 'USER_ID = "' . $postData->USER_ID . '" AND PRODUCT_ID = "' . $postData->PRODUCT_ID . '" AND ENTRY_DATE = "' . $postData->ENTRY_DATE . '"');
			}
		return true;
	}
	public function getProductsReport($postData) {
		$prodCountArr = array();
		$sql = "SELECT s.ID as SERVICE_ID, sp.*   
					 FROM `dr_services` s, `dr_services_products` sp 
					 WHERE s.ID = sp.SERVICE_ID 
					 AND sp.SERVICE_ID = '" . $postData->SERVICE_ID . "' 
					 AND sp.USER_ID = '" . $postData->USER_ID . "' 
					 ORDER BY sp.PRODUCT_NAME ASC";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows > 0) {
			$userProductArr = $this->db->get_multiple_tables_records($sql);
			foreach($userProductArr as $upRec){
				$finalArr[] = array (
					"PRODUCT_ID" => $upRec['ID'],
					"PRODUCT_NAME" => $upRec['PRODUCT_NAME'],
					"PRODUCT_QNT" => 0,
					"PRODUCT_AMT" => 0,
					"PRODUCT_DETAILS" => array('COL_ARR' => 
										array(array('title' => 'DATE', 'field' => 'DATE'),
										array('title' => 'QNT', 'field' => 'QNT'),
										array('title' => 'AMT', 'field' => 'AMT')), 
										'ROW_ARR' => array())
				);
			}
			$c = 0;

			foreach($finalArr as $tmpRec){
				$totQnt = 0;
				$totAmt = 0;				
				$sql = "SELECT s.ID as S_ID,sp.ID as P_ID, sp.PRODUCT_NAME, spd.*   
						FROM `dr_services` as s, `dr_services_products` as sp, `dr_services_products_details` as spd 
						WHERE s.ID = sp.SERVICE_ID 
						AND sp.ID = spd.PRODUCT_ID 
						AND sp.USER_ID = spd.USER_ID 
						AND	sp.ID = " . $tmpRec['PRODUCT_ID'] . " 
						AND	spd.USER_ID = " . $postData->USER_ID . "
						AND sp.SERVICE_ID = " . $postData->SERVICE_ID . " 
						AND MONTH(spd.ENTRY_DATE) = " . $postData->MONTH . " 
						AND YEAR(spd.ENTRY_DATE) = " . $postData->YEAR . "
						ORDER BY spd.ENTRY_DATE ASC";
				$chkSqlPtr = $this->db->get_sql_exec($sql);
				$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
				if($chkSqlRows > 0) {		
					$prodDetailsArr = $this->db->get_multiple_tables_records($sql);
					foreach($prodDetailsArr as $pdRec){
						$totQnt = $totQnt + $pdRec['QNT'];
						$totAmt = $totAmt + $pdRec['AMT'];
						$prdDate=date_create($pdRec['ENTRY_DATE']);						
						$finalArr[$c]['PRODUCT_DETAILS']['ROW_ARR'][] = array(
										'DATE' => date_format($prdDate,"d/m/Y"),
										'QNT' => $pdRec['QNT'],
										'AMT' => $pdRec['AMT']
										
						);
					}
					$finalArr[$c]['PRODUCT_QNT'] = $totQnt;
					$finalArr[$c]['PRODUCT_AMT'] = $totAmt;
				}				
				$c++;
			}
		}		
		//echo'<pre>';print_r($finalArr);die;
		return $finalArr;
	}
}