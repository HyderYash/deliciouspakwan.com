<?php
require_once '../config/cors_headers.php';
include_once '../config/database.php';
class Nutrient{
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }

	// read products with pagination
	public function checkNutrientInDB($postData){
		//print $postData->FOOD_NAME;die;
		$returnArr = array();
		$sql = "SELECT ID FROM dp_nutrition_foods WHERE LOWER(FOOD_NAME) = '" . strtolower(trim($postData->FOOD_NAME)) . "'";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
		if($chkSqlRows == 1){
			//print $chkSqlRows . 'UnderCheck';
			$chkResult = $this->db->get_one_record($sql);
			$food_sql = "SELECT ID FROM dp_nutrition_facts WHERE FOOD_ID = " . $chkResult['ID'];
			$chkSqlFoodPtr = $this->db->get_sql_exec($food_sql);
			$chkSqlFoodRows = $this->db->get_db_num_rows($chkSqlFoodPtr);
			if($chkSqlFoodRows > 0) {
				$returnArr = array($chkResult['ID'], true);
			} else {
				$returnArr = array($chkResult['ID'], false);
			}
		}
		if($chkSqlRows == 0){
			if(trim($postData->FOOD_NAME) != ""){
				$qry = "INSERT INTO dp_nutrition_foods (`ID`, `FOOD_NAME`, `FDC_ID`)  VALUES (NULL, '" . trim($postData->FOOD_NAME) . "', '0')";
				$ptr = 	$this->db->get_sql_exec($qry);
				$last_insert_food_id = $this->db->get_last_insert_id();
				$returnArr = array($last_insert_food_id, false);
			}else{
				$returnArr = array(0, false);
			}
		}
		return $returnArr;
	}
	public function updateNutrientInDB($postData){
		//print_r($postData);die;
		if($postData->FOOD_ID > 0) {
			$updatePtr = $this->db->set_multiple_fields('dp_nutrition_foods', 'FDC_ID = "' . $postData->FDC_ID . '"', 'ID = "' . $postData->FOOD_ID . '"');
			foreach($postData->FOOD_NUTRIENTS_ARR as $nutriData) {
				$sql = "SELECT ID FROM dp_nutrition_facts WHERE LOWER(NUTRIENT_NAME) = '" . strtolower(trim($nutriData->nutrientName)) . "' AND FOOD_ID = " . $postData->FOOD_ID;
				$chkSqlPtr = $this->db->get_sql_exec($sql);
				$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);
				if($chkSqlRows == 0){
					$qry = "INSERT INTO dp_nutrition_facts (`ID`, `FOOD_ID`, `NUTRIENT_NAME`, `NUTRIENT_VALUE`,`NUTRIENT_UNIT`, `NUTRIENT_DESC`)  VALUES 
							(NULL, '" . $postData->FOOD_ID . "', '" . $nutriData->nutrientName . "', '" . $nutriData->value . "', '" . $nutriData->unitName . "', '" . $nutriData->derivationDescription . "')";
					$ptr = 	$this->db->get_sql_exec($qry);
				}
			}
			return true;
		}else{
			return false;
		}
		
		
	}
}