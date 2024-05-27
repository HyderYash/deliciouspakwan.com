<?php
// required headers
require_once '../config/cors_headers.php';
include_once '../config/database.php';
class Rasan {
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	public function getRasanList($postData){
		// select query
		$result = array();
		if($postData->LIST_NAME === ''){
			$item_result = array();
			$brand_result = array();
			$unit_result = array();
			$datecat_result = array();
			$sql = "SELECT * FROM dp_rasan_items WHERE status = 'Y' ORDER BY item_name ASC";
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows > 0){				
				$item_result = $this->db->get_multiple_tables_records($sql);
			}

			$sql = "SELECT * FROM dp_rasan_brands WHERE status = 'Y' ORDER BY brand_name ASC";
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows > 0){				
				$brand_result = $this->db->get_multiple_tables_records($sql);
			}
			
			$sql = "SELECT * FROM dp_rasan_units WHERE status = 'Y' ORDER BY unit_name ASC";
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows > 0){				
				$unit_result = $this->db->get_multiple_tables_records($sql);
			}
			$sql = "select date_cat from dp_rasan_list  where status = 'Y' group by date_cat";
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows > 0){				
				$datecat_result = $this->db->get_multiple_tables_records($sql);
			}
			$result['item_list'] = $item_result;
			$result['brand_list'] = $brand_result;
			$result['unit_list'] = $unit_result;
			$result['datecat_list'] = $datecat_result;

		}
		if($postData->LIST_NAME !== ''){
			$sql = "SELECT ri.ID as item_id, ri.item_name,rb.ID as brand_id, rb.brand_name,		rl.qnt,ru.ID unit_id, ru.unit_name FROM dp_rasan_items ri, dp_rasan_brands rb, dp_rasan_list rl, dp_rasan_units ru WHERE ri.ID = rl.item_id AND rb.ID = rl.brand_id AND ru.ID = rl.unit_id AND rl.status = 'Y' AND ri.status = 'Y' AND rb.status = 'Y' AND ru.status = 'Y' AND rl.date_cat = '".$postData->LIST_NAME."' ORDER BY ri.item_name ASC";
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
			if($chkSqlRows > 0){				
				$result = $this->db->get_multiple_tables_records($sql);
			}
		} 
		// return values from database
		return $result;
	}

	public function saveRasanList($postData){
		$date_cat = $this->date_format();
		foreach($postData as $post){
			$qry = "INSERT INTO dp_rasan_list (`ID`, `item_id`, `brand_id`, `qnt`,`unit_id`, `date_cat`, `status` )  VALUES (NULL, '" . $post->item . "', '" . $post->brand . "', '" . $post->quantity . "', '" . $post->unit . "', '" . trim($date_cat) . "', 'Y')";
			$ptr = 	$this->db->get_sql_exec($qry);
		}
		return true;
	}

	public function date_format(){
		// set the default timezone to use.
		date_default_timezone_set("Asia/Kolkata");
		// now
		return $date = date('dMy-h:i:sa');
		// Prints something like: Wednesday
		// return $date->format('dMy-h:i:sa');
	}

}