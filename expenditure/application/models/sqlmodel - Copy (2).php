<?php
if (!isset($_SESSION)) { 
  session_start();
}
class sqlmodel extends CI_Model
{
	private $startExecutionTime = '';
	private $endExecutionTime = '';
	public function __construct() {
		
		$this->load->model('Timer', 'timer');

    }

	function addModifyMonthlyData($postfld)
	{	
		$funcId = $this->_setFunctionHistory(__method__);
		$month = CURRENT_MONTH;
		$year = CURRENT_YEAR;
		foreach($postfld as $fldkey => $fldval)
		{
			if($fldkey == 'modfldmonyear')
			{
				$mon_year_arr = explode('-', $fldval);
				if(isset($mon_year_arr[0]) && $mon_year_arr[0] != '')
				{
					$month = $mon_year_arr[0];
				}
				if(isset($mon_year_arr[1]) && $mon_year_arr[1] != '')
				{
					$year = $mon_year_arr[1];
				}				
			}
		}
		foreach($postfld as $fldkey => $fldval)
		{
			$fldkeyArr = explode('_', $fldkey);
			if(isset($fldkeyArr[0]) && $fldkeyArr[0] == 'newexpamt')
			{
				if(isset($fldkeyArr[1]) && $fldkeyArr[1] != '')
				{
					$itemIdArr = explode('-',$fldkeyArr[1]);
					if(isset($itemIdArr[0]) && $itemIdArr[0] != '')
					{
						$this->insertNewRecInExpenditure($itemIdArr[0],$fldval,$month,$year,$itemIdArr[1]);		
					}
				}
			}
			if(isset($fldkeyArr[0]) && $fldkeyArr[0] == 'expamt')
			{
				if(isset($fldkeyArr[1]) && $fldkeyArr[1] != '')
				{
					$expnditurePrimaryIdArr = explode('-',$fldkeyArr[1]);
					if(isset($expnditurePrimaryIdArr[0]) && $expnditurePrimaryIdArr[0] != '')
					{
						$this->modifyExistingRecInExpenditure($expnditurePrimaryIdArr[0],$fldval,$month,$year);		
					}
				}
			}
		}
		$this->_getExecutionTime($funcId); 
		$this->customRedirect('/showMonthlyStatusCurrent/?showMonth=' . $month . '-' . $year);		
	}
	function insertNewRecInExpenditure($item_id,$amt,$month,$year,$slot)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$currDate = $year . '-' . $month . '-01 ' . date('h:i:s');
		$amt = str_replace(',', '',$amt);
		if($amt > 0)
		{
			$qry = "INSERT INTO ec_expenditure (`ID`, `EXP_ITEM_ID`, `EXP_AMT`, `EXP_DATE`, `EXP_SLOT`, `USER_ID` )  VALUES 
			(NULL, " . $item_id . ", " . $amt . ", '" . $currDate . "',  '" . $slot . "', '" . $_SESSION['curr_user_id'] . "')";
			$ptr = 	$this->db->get_sql_exec($qry);
		}
		$this->_getExecutionTime($funcId,$qry); 
	}
	function modifyExistingRecInExpenditure($exp_primary_id,$amt,$month,$year)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$currDate = $year . '-' . $month . '-01 ' . date('h:i:s');
		$amt = str_replace(',', '',$amt);
		$updatePtr = $this->db->set_multiple_fields('ec_expenditure', 'EXP_AMT = "' . $amt . '", EXP_DATE = "' . $currDate . '"', 'ID = "' . $exp_primary_id . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
		 $this->_getExecutionTime($funcId,$updatePtr); 
	}
	
	function getDashboardData()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$acctListArr = $this->_get_accounts_list();
		$dashboardArray = array();
		foreach ($acctListArr as $acctList)
		{
			$acct_id = $acctList['ID'];
			$acct_name = $acctList['EXP_ACCOUNT'];
			$cf = $this->_get_system_or_cf_balance($acct_id,'cf');
			$system_bal = $this->_get_system_or_cf_balance($acct_id);
			$savings = $this->_get_recurring_or_savings($acct_id,3);
			$recurring = $this->_get_recurring_or_savings($acct_id,4);
			$current_bal = $this->_get_primary_current_balance($acct_id,'current');
			$actual_primary_bal = $this->_get_primary_current_balance($acct_id,'primary');
			$primary_bal = $this->_get_affected_primary_balance($actual_primary_bal, $system_bal, $savings, $current_bal);
			$all_savings = ($primary_bal + $savings + $recurring);
			
			$expected_sys_bal = $this->_get_affected_expected_sys_bal($system_bal,$all_savings,$recurring, $primary_bal,$actual_primary_bal);
			
			$actual_avl = $current_bal - $expected_sys_bal;

			
			//$actual_avl = $current_bal - ($primary_bal + $savings);
			
			if($system_bal < 0)
			{
				$emergency_avl = $current_bal - ($system_bal * (-1));
			}			
			if($system_bal >= 0)
			{
				$emergency_avl = $current_bal - $system_bal;
			}				
			
			$dashboardArray[] = array(
			'bg_color' => $acctList['BG_COLOR'], 
			'font_color' => $acctList['FONT_COLOR'],
			'Account ID' => $acct_id,
			'Accounts' => $acct_name, 
			'System Bal' => $system_bal, 
			'Primary Bal' => $primary_bal, 
			'Savings' => $savings, 
			'Recurring' => $recurring, 
			'CF' => $cf, 
			'All Savings' => $all_savings, 
			'Expected Sys Bal' => $expected_sys_bal, 
			'Current Bal' => $current_bal,  
			'Actual Avl' => $actual_avl, 
			'Emergency Avl' => $emergency_avl
			);	
		}
		$this->_getExecutionTime($funcId,'',$dashboardArray); 
		return $dashboardArray;
	}
	/*Automatic function --calling from My_Controller */
	function auto_do_add_exp_for_fixed_paid_items($month='', $year='')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		
		$sql = "SELECT ID, DEFAULT_DEPO_AMT, PAY_DAY, EXP_ACCOUNT_ID, '" . $year . "-" . $month . "-01 " . date('h:i:s') . "' AS depo_date 
				FROM `ec_items` 
				WHERE FIXED_PAYEE = 'Y' 
				AND ACTIVE = 'Y' 
				AND EXPIRED = 'N' 
				AND DAY(CURDATE()) >= PAY_DAY 
				AND USER_ID = " . $_SESSION['curr_user_id']; //die;
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
		if($chkSqlRows > 0)
		{
			$result = $this->db->get_multiple_tables_records($sql);
			//print '<pre>';print_r($result);die;
			foreach($result as $rec)
			{
				/*Fixed Payee Entry on fixed Day*/
				$chkExpStr = "SELECT ID, EXP_AMT, TIME(EXP_DATE) as expTime   
				FROM ec_expenditure 
				WHERE EXP_ITEM_ID = '" . $rec['ID'] . "' 
				AND month(EXP_DATE) = '" . $month . "' 
				AND year(EXP_DATE) = '" . $year . "' 
				AND DAY(CURDATE()) >= '" . $rec['PAY_DAY'] . "' 
				AND USER_ID = " . $_SESSION['curr_user_id'];
				$chkExpPtr = $this->db->get_sql_exec($chkExpStr);
				$chkExpRows = $this->db->get_db_num_rows($chkExpPtr);
				if($chkExpRows == 1)
				{
					$chkExpResult = $this->db->get_one_record($chkExpStr);
					if($chkExpResult['EXP_AMT'] == 0.00 && $chkExpResult['expTime'] == '00:00:00')
					{
						/* Updating Expenditure Amount and date according to auto payday for fixed items */
						$updatePtr = $this->db->set_multiple_fields('ec_expenditure', 'EXP_AMT = "' . $rec['DEFAULT_DEPO_AMT'] . '", EXP_DATE = "' . $rec['depo_date'] . '"', 'ID = "' . $chkExpResult['ID'] . '" AND EXP_ITEM_ID = "' . $rec['ID'] . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');

						/* Updating Current Balance Amount and date according to auto payday for fixed items */
						$currBalOnAcct = $this->_get_primary_current_balance($rec['EXP_ACCOUNT_ID'],$what='current');
						$this->modifyCurrBalAmt($rec['EXP_ACCOUNT_ID'],($currBalOnAcct - $rec['DEFAULT_DEPO_AMT']));
					}
				}
			}
		}		
		$this->_getExecutionTime($funcId,$sql);
	}
	/*Automatic function --calling from My_Controller */
	function auto_do_default_surplus_on_current_balance(){
		$funcId = $this->_setFunctionHistory(__method__);
		$acctListArr = $this->_get_accounts_list();
		$currDate = date('Y-m-d h:i:s');
		foreach ($acctListArr as $acctList)
		{
			$acct_id = $acctList['ID'];
			$sql = "SELECT sum(d.DEPO_AMT) as amt, a.EXP_ACCOUNT, a.ID, a.CURRENT_BAL    
			FROM `ec_deposit` AS d, ec_items AS i, ec_accounts AS a 
			WHERE d.DEPO_ITEM_ID = i.ID 
			AND i.ACTIVE = 'Y'
			AND i.EXPIRED = 'N' 
			AND i.EXP_ACCOUNT_ID = a.ID 
			AND month(d.DEPO_DATE) = '" . CURRENT_MONTH . "' 
			AND year(d.DEPO_DATE) = '" . CURRENT_YEAR . "' 
			AND i.EXP_ACCOUNT_ID = " . $acct_id	. " 
			AND month(a.LAST_UPDATED) != '" . CURRENT_MONTH . "' 
			AND year(a.LAST_UPDATED) = '" . CURRENT_YEAR . "' 
			AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND a.USER_ID = " . $_SESSION['curr_user_id']; 		
			$chkSqlRows = $this->db->get_one_record($sql);
			//print $sql . "<br><br><br>";print '<pre>';print_r($chkSqlRows);		
			if($chkSqlRows['ID'] != null)
			{
				$updatePtr = $this->db->set_multiple_fields('ec_accounts', 'CURRENT_BAL = "' . ($chkSqlRows['CURRENT_BAL'] + $chkSqlRows['amt']) . '", LAST_UPDATED = "' . $currDate . '"', 'ID = "' . $acct_id . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
				//print $sql . "<br><br><br>";
			}
		}
		$this->_getExecutionTime($funcId,'',$acctListArr);
	}
	/*Automatic function --calling from My_Controller */
	function auto_do_default_deposit_for_month($month='', $year='')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT ID, DEFAULT_DEPO_AMT, '" . $year . "-" . $month . "-01 " . "00:00:00' AS depo_date 
				FROM `ec_items` 
				WHERE ACTIVE = 'Y' 
				AND EXPIRED = 'N' 
				AND USER_ID = " . $_SESSION['curr_user_id']; 
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
		if($chkSqlRows == 0)
		{
			print "Sorry!! Exp_Items are not set for this User.";die;
		}
		else
		{
			$result = $this->db->get_multiple_tables_records($sql);
			foreach($result as $rec)
			{
				/*Default Depo Entry*/
				$chkStr = "SELECT ID 
				FROM ec_deposit 
				WHERE DEPO_ITEM_ID = '" . $rec['ID'] . "' 
				AND DEPO_DATE = '" . $rec['depo_date'] . "'  
				AND USER_ID = " . $_SESSION['curr_user_id'];
				$chkPtr = $this->db->get_sql_exec($chkStr);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows == 0)
				{
					$qry = "INSERT INTO ec_deposit (`ID`, `DEPO_ITEM_ID`, `DEPO_AMT`, `DEPO_DATE`, `USER_ID`)  VALUES 
					(NULL, " . $rec['ID'] . ", " . $rec['DEFAULT_DEPO_AMT'] . ", '" . $rec['depo_date'] . "', '" . $_SESSION['curr_user_id'] . "')";
					$ptr = 	$this->db->get_sql_exec($qry);
				}
				/*Default Exp Entry*/
				$chkExpStr = "SELECT ID 
				FROM ec_expenditure 
				WHERE EXP_ITEM_ID = '" . $rec['ID'] . "' 
				AND month(EXP_DATE) = '" . $month . "' 
				AND year(EXP_DATE) = '" . $year . "' 	
				AND USER_ID = " . $_SESSION['curr_user_id'];
				$chkExpPtr = $this->db->get_sql_exec($chkExpStr);
				$chkExpRows = $this->db->get_db_num_rows($chkExpPtr);
				if($chkExpRows == 0)
				{
					$expQry = "INSERT INTO ec_expenditure (`ID`, `EXP_ITEM_ID`, `EXP_AMT`, `EXP_DATE`, `USER_ID`)  VALUES 
					(NULL, " . $rec['ID'] . ", 0, '" . $rec['depo_date'] . "', '" . $_SESSION['curr_user_id'] . "')";
					$expPtr = $this->db->get_sql_exec($expQry);
				}
				
			}
			
		}		
		/*************On New Month starts... Default surplus on CURRENT_BAL  *******************Start**********/
		$this->auto_do_default_surplus_on_current_balance();
		/*************On New Month starts... Default surplus on CURRENT_BAL  *******************End**********/
		
		/*************Default Entries for new Users *******************Start**********/
		$this->doDefaultEntryForWordSent();
		$this->doDefaultEntryForQuesListWithAns();
		/*************Default Entries for new Users *******************End**********/
		$this->_getExecutionTime($funcId,$sql);
		
	}

	function getMonthlyData($month='',$year='')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		if($month == '')
		{
			$month = $_SESSION['curr_mon'];
			$year = $_SESSION['curr_yr'];
		}
		$orderby = 'a.ID,i.EXP_ITEM_NAME ASC';
		if(isset($_SESSION['monthlySortBy']))
		{
			switch ($_SESSION['monthlySortBy']) {
			case 'account':
				$orderby = 'a.EXP_ACCOUNT ' . $_SESSION['monthlySortOrder'];
				break;
			case 'item':
				$orderby = 'i.EXP_ITEM_NAME ' . $_SESSION['monthlySortOrder'];
				break;
			case 'category':
				$orderby = 'ic.EXP_ITEM_CAT ' . $_SESSION['monthlySortOrder'];
				break;
			case 'payday':
				$orderby = 'i.PAY_DAY ' . $_SESSION['monthlySortOrder'];
				break;
			case 'allocate':
				$orderby = 'i.DEFAULT_DEPO_AMT ' . $_SESSION['monthlySortOrder'];
				break;
			case 'default':
				$orderby = 'a.ID,i.EXP_ITEM_NAME ASC';
				break;
			}
		}

		$sql = "SELECT i.ID,i.EXP_ITEM_NAME, i.PAY_DAY, ic.ID as item_cat_id, ic.EXP_ITEM_CAT,a.ID as acct_id, a.EXP_ACCOUNT,c.BG_COLOR,c.FONT_COLOR  
		FROM ec_items AS i, ec_items_cat AS ic,ec_accounts AS a, ec_color_code AS c  
		WHERE i.EXP_ITEM_CAT_ID = ic.ID 
		AND i.EXP_ACCOUNT_ID = a.ID 
		AND c.ID = a.COLOR_CODE_ID 
		AND i.ACTIVE = 'Y' 
		AND c.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND ic.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND a.USER_ID = " . $_SESSION['curr_user_id'] . " 		
		ORDER BY " . $orderby;
		$result = $this->db->get_multiple_tables_records($sql);
		$monthlyDataArr = array();
		foreach($result as $rec)
		{
			$chkExpStr = "SELECT ID,EXP_ITEM_ID, EXP_AMT, EXP_SLOT, EXP_DATE    
			FROM ec_expenditure 
			WHERE EXP_ITEM_ID = " . $rec['ID'] . " 
			AND month(EXP_DATE) = " . $month . " 
			AND year(EXP_DATE) = " . $year . " 
			AND USER_ID = " . $_SESSION['curr_user_id'] . " 
			ORDER BY EXP_SLOT ASC"; 
			$chkExpResult = $this->db->get_multiple_tables_records($chkExpStr);
			$t_exp = 0;
			if(is_array($chkExpResult))
			{
				foreach($chkExpResult as $exp_amt)
				{
					$t_exp += $exp_amt['EXP_AMT'];
				}
			}
			$chkDepoStr = "SELECT ID, DEPO_AMT AS t_depo  
			FROM ec_deposit 
			WHERE DEPO_ITEM_ID = " . $rec['ID'] . " 
			AND month(DEPO_DATE) = " . $month . " 
			AND year(DEPO_DATE) = " . $year . " 
			AND USER_ID = " . $_SESSION['curr_user_id']; 
			$chkDepoResult = $this->db->get_one_record($chkDepoStr);

			$monthlyDataArr[] = array('item_mon_year' => $month . '-' . $year, 'item_id' => $rec['ID'],'item_name' => $rec['EXP_ITEM_NAME'], 'item_cat_id' => $rec['item_cat_id'],'item_cat' => $rec['EXP_ITEM_CAT'],'pay_day' => $rec['PAY_DAY'],'last_updated' => $chkExpResult[0]['EXP_DATE'],'acct_id' => $rec['acct_id'],'acct_name' => $rec['EXP_ACCOUNT'],'exp_arr' => $chkExpResult,'depo_amt' => $chkDepoResult['t_depo'],'allocation_id' => $chkDepoResult['ID'], 'available_amt' => ($chkDepoResult['t_depo'] - $t_exp), 'total_exp' => $t_exp, 'bg_color' => $rec['BG_COLOR'], 'font_color' => $rec['FONT_COLOR'] );
			
		}//print '<pre>';print_r($monthlyDataArr);die;
		$this->_getExecutionTime($funcId,'',$monthlyDataArr); 
		return $monthlyDataArr;
	}
	function getTotalAllocateForCurrentMonth()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT sum(d.`DEPO_AMT`) as depo_amt   
			FROM `ec_deposit` AS d, ec_items AS i, ec_items_cat AS ic 
			WHERE d.DEPO_ITEM_ID = i.ID 
			AND i.EXP_ITEM_CAT_ID = ic.ID 
			AND month(d.`DEPO_DATE`) = " . CURRENT_MONTH . " 
			AND year(d.`DEPO_DATE`) = " . CURRENT_YEAR . " 
			AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND ic.USER_ID = " . $_SESSION['curr_user_id'] . " 
			GROUP BY year(d.`DEPO_DATE`), month(d.`DEPO_DATE`)";		
		$depo_result = $this->db->get_one_record($sql);
		$this->_getExecutionTime($funcId,'',$depo_result); 
		return $depo_result['depo_amt'];
	}
	function getExpenditureByCatInPercent()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$itemCatListArr = $this->_get_item_cat_list();
		$expByCatArray = array();
		$i = 1;
		$t_allocate_amt = 0;//print '<pre>';//print_r($itemCatListArr);die;
		foreach ($itemCatListArr as $catList)
		{
			if($i == 1)
			{
				$t_allocate_amt = $this->getTotalAllocateForCurrentMonth();
			}
			$item_cat_id = $catList['ID'];
			$sql = "SELECT sum(d.`DEPO_AMT`) as depo_amt, ic.EXP_ITEM_CAT  
				FROM `ec_deposit` AS d, ec_items AS i, ec_items_cat AS ic 
				WHERE d.DEPO_ITEM_ID = i.ID 
				AND i.EXP_ITEM_CAT_ID = ic.ID 
				AND ic.ID = " . $item_cat_id . " 
				AND month(d.`DEPO_DATE`) = " . CURRENT_MONTH . " 
				AND year(d.`DEPO_DATE`) = " . CURRENT_YEAR . " 
				AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
				AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
				AND ic.USER_ID = " . $_SESSION['curr_user_id'] . " 
				GROUP BY year(d.`DEPO_DATE`), month(d.`DEPO_DATE`)";		
		
			$depo_result = $this->db->get_one_record($sql);
			$yearly_exp_amount = ($depo_result['depo_amt'] * 12);
			/***checked condition to rescue depo_amt to division by zero **/
			if($t_allocate_amt == 0)
			{
				$percent_exp_amount = 0;
			}
			else
			{
				$percent_exp_amount = ((($depo_result['depo_amt']) * 100) / $t_allocate_amt);
			}
			$colorCodeArr = $this->_get_color_code($item_cat_id,'category',__method__);
			$expByCatArray[] = array('item_cat_id' => $item_cat_id,'item_cat_name' => $depo_result['EXP_ITEM_CAT'], 'monthly_exp_amt' => $depo_result['depo_amt'], 'yearly_exp_amt' => $yearly_exp_amount,'percent_exp_amount' => $percent_exp_amount, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);
			$i++;
		}
		$this->_getExecutionTime($funcId,'',$expByCatArray); 
		return $expByCatArray;
	}
	function _get_affected_primary_balance($primary_bal, $system_bal, $savings, $current_bal)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$fixedPrimaryBal = $primary_bal;
		if($system_bal < 0)
		{
			if($current_bal < (($system_bal * (-1)) + $savings) + $fixedPrimaryBal)
			{
				$primary_bal = 0;
			}
		}
		if($system_bal >= 0)
		{
			if($current_bal < ($system_bal + $savings) + $fixedPrimaryBal)
			{
				$primary_bal = 0;
			}
		}
		$this->_getExecutionTime($funcId,$primary_bal);
		return $primary_bal;
	}
	function _get_color_code($for_id=0,$what='account',$methodcall='none')
	{
		
		$funcId = $this->_setFunctionHistory(__method__);
		$chkStr = "SELECT a.COLOR_CODE_ID, c.BG_COLOR,c.FONT_COLOR 
		FROM ec_accounts AS a, ec_color_code AS c  
		WHERE a.USER_ID = " . $_SESSION['curr_user_id'] . "
		AND c.USER_ID = " . $_SESSION['curr_user_id'] . " 		
		AND c.ID = a.COLOR_CODE_ID ";		
		if($for_id == 0)
		{
			$colCont = $this->_getColorCountForCurrentUser();
			$chkStr .= " AND a.COLOR_CODE_ID = " . $colCont['rand_color_id'];
		}
		else
		{
			$acct_id = 1;
			if(rand(1,9) <= 5){
				$order = " DESC";
			}
			else{
				$order = " ASC";
			}
			if($what == 'account')
				$acct_id = $for_id;
			if($what == 'category'){
				$acctSql = "SELECT EXP_ACCOUNT_ID 
				FROM ec_items as i, ec_items_cat as c, ec_accounts AS a 
				WHERE i.EXP_ACCOUNT_ID = a.ID 
				AND i.EXP_ITEM_CAT_ID = c.ID 
				AND c.ID = " . $for_id . " 
				AND c.USER_ID = " . $_SESSION['curr_user_id'] . " 
				GROUP BY c.ID
				ORDER BY c.ID " . $order . " 
				LIMIT 1";
				$acct_result = $this->db->get_one_record($acctSql);
				$acct_id = $acct_result['EXP_ACCOUNT_ID'];
			}
				
			if($what == 'item'){
				$acctSql = "SELECT EXP_ACCOUNT_ID,count(EXP_ACCOUNT_ID) 
				FROM ec_items as i, ec_items_cat as c, ec_accounts AS a 
				WHERE i.EXP_ACCOUNT_ID = a.ID 
				AND i.EXP_ITEM_CAT_ID = c.ID 
				AND i.ID = " . $for_id . " 
				AND c.USER_ID = " . $_SESSION['curr_user_id'] . " 
				GROUP BY i.ID
				ORDER BY i.ID " . $order . " 
				LIMIT 1";//print $acctSql . "<br><br>";
				$acct_result = $this->db->get_one_record($acctSql);
				$acct_id = $acct_result['EXP_ACCOUNT_ID'];
			}
			if($acct_id == '') $acct_id = 1;	
				$chkStr .= " AND a.ID = " . $acct_id;		
		}//print $methodcall . "====>" . $chkStr . "<br><br>";
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 0)
		{
			$this->_getExecutionTime($funcId,$chkStr); 
			print "Sorry!! Account are not set for this User.";die;
		}
		else
		{
			$result = $this->db->get_multiple_tables_records($chkStr);
			$this->_getExecutionTime($funcId,$chkStr,$result); 
			return $result;		
		}		
	}
	function _getColorCountForCurrentUser()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$chkStr = "SELECT c.ID as rand_color_id   
		FROM ec_color_code AS c   
		WHERE c.USER_ID = " . $_SESSION['curr_user_id'] . "	order by rand()";
		$col_result = $this->db->get_one_record($chkStr);
		$this->_getExecutionTime($funcId,$chkStr,$col_result);
		return $col_result;
	}
	function getTransferData()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$acctListArr = $this->_get_accounts_list();
		$transferArray = array();
		
		foreach ($acctListArr as $acctList)
		{
			$acct_id = $acctList['ID'];
			$sql = "SELECT sum(d.DEPO_AMT) as amt, a.EXP_ACCOUNT 
			FROM `ec_deposit` AS d, ec_items AS i, ec_accounts AS a 
			WHERE d.DEPO_ITEM_ID = i.ID 
			AND i.EXP_ACCOUNT_ID = a.ID 
			AND month(d.DEPO_DATE) = '" . CURRENT_MONTH . "' 
			AND year(d.DEPO_DATE) = '" . CURRENT_YEAR . "' 
			AND i.EXP_ACCOUNT_ID = " . $acct_id	. " 
			AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND a.USER_ID = " . $_SESSION['curr_user_id']; 		

			$depo_result = $this->db->get_one_record($sql);
			
			
			$cf = $this->_get_system_or_cf_balance($acct_id,'cf');
			$system_bal = $this->_get_system_or_cf_balance($acct_id);
			$savings = $this->_get_recurring_or_savings($acct_id,3);
			$recurring = $this->_get_recurring_or_savings($acct_id,4);
			$current_bal = $this->_get_primary_current_balance($acct_id,'current');
			$actual_primary_bal = $this->_get_primary_current_balance($acct_id,'primary');
			$primary_bal = $this->_get_affected_primary_balance($actual_primary_bal, $system_bal, $savings, $current_bal);
			$all_savings = ($primary_bal + $savings + $recurring);

			$expected_sys_bal = $this->_get_affected_expected_sys_bal($system_bal,$all_savings,$recurring, $primary_bal,$actual_primary_bal);
			
			
			
			$actual_avl = $current_bal - $expected_sys_bal;			
			$yearlyAmt = ($depo_result['amt'] * 12);
			$transfer = $this->_round_up($depo_result['amt'], -2);

			if($actual_avl < 0)
			{
				$currAdjustment = $transfer + ($actual_avl * (-1));
			}			
			if($actual_avl >= 0)
			{
				$currAdjustment = $transfer - $actual_avl;
			}			
			$extraAmtReq = $currAdjustment - $transfer;
			$transferArray[] = array(
			'bg_color' => $acctList['BG_COLOR'], 
			'font_color' => $acctList['FONT_COLOR'],			
			'AccountID' => $acct_id,
			'Accounts' => $depo_result['EXP_ACCOUNT'],
			'MonthlyAmt' => $depo_result['amt'], 
			'YearlyAmt' => $yearlyAmt, 
			'Transfer' => $transfer, 
			'Curr_Adjustment' => $currAdjustment, 
			'Extra_Amt_Req' => $extraAmtReq 
			);	
		}
		$this->_getExecutionTime($funcId,'',$transferArray); 
		return $transferArray; 		
	}
	function _get_affected_expected_sys_bal($system_bal,$all_savings,$recurring, $primary_bal,$actual_primary_bal)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		if($primary_bal == 0)
		{
			$primary_bal = $actual_primary_bal;
		}
		else{
			$primary_bal = 0;
		}
		if($system_bal < 0)
		{
			$expected_sys_bal = ($all_savings - $recurring) + ($system_bal * (-1)) + $primary_bal;
		}
		else
		{
			$expected_sys_bal = ($all_savings - $recurring) + $system_bal + $primary_bal;
		}
		$this->_getExecutionTime($funcId,'',$expected_sys_bal);
		return $expected_sys_bal;
	}
	function _round_up($value, $places) 
	{
		
		$mult = pow(10, abs($places)); 
		return $places < 0 ?
		ceil($value / $mult) * $mult :
			ceil($value * $mult) / $mult;
			
	}	
	function allMonthsData ()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		if(isset($_REQUEST['showItemYear']) && $_REQUEST['showItemYear'] != '')
		{
			$_SESSION['exp_item_year'] = $_REQUEST['showItemYear'];
		}
		else
		{
			$_SESSION['exp_item_year'] = CURRENT_YEAR;			
		}	
		$allMonthDataArr = array();
		$monthStr = "SELECT m.MONTH_NUMBER, m.MONTH_SHORT_NAME, year(d.DEPO_DATE) AS MONTH_YEAR  
			FROM  ec_months AS m, ec_deposit AS d  
			WHERE month(d.DEPO_DATE) = m.MONTH_NUMBER 
			AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND year(d.DEPO_DATE) = " . $_SESSION['exp_item_year'] . " 
			GROUP BY month(d.DEPO_DATE),year(d.DEPO_DATE)";
		$monthResult = $this->db->get_multiple_tables_records($monthStr);

		foreach($monthResult as $month)
		{
			$acctArr = $this->_get_accounts_list();
			$acctExpDepoArr = array();
			$t_depo_amt = 0;
			$t_exp_amt = 0;
			$t_bal_amt = 0;
			$t_cf_amt = 0;			
			foreach($acctArr as $acct_name)
			{
				$chkDepoStr = "SELECT sum(d.`DEPO_AMT`) as depo_amt, year(d.`DEPO_DATE`) ,month(d.`DEPO_DATE`) 
				FROM `ec_deposit` AS d, ec_items AS i 
				WHERE d.DEPO_ITEM_ID = i.ID 
				AND i.EXP_ACCOUNT_ID = " . $acct_name['ID'] . "
				AND month(d.`DEPO_DATE`) = " . $month['MONTH_NUMBER'] . " 
				AND year(d.`DEPO_DATE`) = " . $month['MONTH_YEAR'] . " 
				AND year(d.DEPO_DATE) = " . $_SESSION['exp_item_year'] . "				
				AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
				AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 				
				GROUP BY year(d.`DEPO_DATE`), month(d.`DEPO_DATE`)";
				$chkDepoResult = $this->db->get_one_record($chkDepoStr);
			
				$chkExpStr = "SELECT sum(e.`EXP_AMT`) as exp_amt, year(e.`EXP_DATE`) ,month(e.`EXP_DATE`) 
				FROM `ec_expenditure` AS e, ec_items AS i 
				WHERE e.EXP_ITEM_ID = i.ID 
				AND i.EXP_ACCOUNT_ID = " . $acct_name['ID'] . " 
				AND month(e.`EXP_DATE`) = " . $month['MONTH_NUMBER'] . " 
				AND year(e.`EXP_DATE`) = " . $month['MONTH_YEAR'] . " 
				AND year(e.EXP_DATE) = " . $_SESSION['exp_item_year'] . "				
				AND e.USER_ID = " . $_SESSION['curr_user_id'] . " 
				AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
				GROUP BY year(e.`EXP_DATE`), month(e.`EXP_DATE`)";
				$chkExpResult = $this->db->get_one_record($chkExpStr);
				
				if($month['MONTH_NUMBER'] == CURRENT_MONTH && $month['MONTH_YEAR'] == CURRENT_YEAR)
				{
					$cf_amt = 0.00;
				}
				else
				{
					$cf_amt = $chkDepoResult['depo_amt'] - $chkExpResult['exp_amt'];
				}
				$bal_amt = ($chkDepoResult['depo_amt'] - $chkExpResult['exp_amt']);
				$t_depo_amt += $chkDepoResult['depo_amt'];
				$t_exp_amt += $chkExpResult['exp_amt'];
				$t_bal_amt += $bal_amt;
				$t_cf_amt += $cf_amt;
				$acctExpDepoArr[] = array('acct_id' => $acct_name['ID'],'acct_name' => $acct_name['EXP_ACCOUNT'], 'depo_amt' => $chkDepoResult['depo_amt'], 'exp_amt' => $chkExpResult['exp_amt'], 'bal_amt' => $bal_amt, 'cf_amt' => $cf_amt, 'bg_color' => $acct_name['BG_COLOR'], 'font_color' => $acct_name['FONT_COLOR'], 'month_number' => $month['MONTH_NUMBER'],'month_year' => $month['MONTH_YEAR']);
			}
			/* Get Total Amount */
			$acctExpDepoArr[] = array('acct_name' => 'TOTAL', 'depo_amt' => $t_depo_amt, 'exp_amt' => $t_exp_amt, 'bal_amt' => $t_bal_amt, 'cf_amt' => $t_cf_amt, 'bg_color' => '#B6DDE8', 'font_color' => '#000');
			$allMonthDataArr[$month['MONTH_SHORT_NAME'] . " " . $month['MONTH_YEAR']] = $acctExpDepoArr;
			/* Get Total Amount */
			
		}
		/* Get Grand Total Amount */
		reset($acctArr);
		$gt_acctExpDepoArr = array();
		$tArr = array('ID' => '', 'EXP_ACCOUNT' => 'TOTAL','PRIMARY_BAL' => 0.00,'CURRENT_BAL' => 0.00,'COLOR_CODE_ID' => 1, 'LAST_UPDATED' => '2017-03-14 00:00:00', 'sl_no' => 7);
		array_push($acctArr,$tArr);
		reset($acctArr);
		foreach($acctArr as $acct_name)
		{
			$gt_depo_amt = 0;
			$gt_exp_amt = 0;
			$gt_bal_amt = 0;
			$gt_cf_amt = 0;			
			foreach($allMonthDataArr as $months => $amtArr)
			{
				foreach($amtArr as $amtdata)
				{
					if($amtdata['acct_name'] == $acct_name['EXP_ACCOUNT'])
					{
						$gt_depo_amt += $amtdata['depo_amt'];
						$gt_exp_amt += $amtdata['exp_amt'];
						$gt_bal_amt += $amtdata['bal_amt'];
						$gt_cf_amt += $amtdata['cf_amt'];
					}
				}
				
			}
			$gt_acctExpDepoArr[] = array('acct_name' => $acct_name['EXP_ACCOUNT'], 'depo_amt' => $gt_depo_amt, 'exp_amt' => $gt_exp_amt, 'bal_amt' => $gt_bal_amt, 'cf_amt' => $gt_cf_amt, 'bg_color' => isset($acct_name['BG_COLOR']) ? $acct_name['BG_COLOR'] : '#B6DDE8', 'font_color' => isset($acct_name['FONT_COLOR']) ? $acct_name['FONT_COLOR'] : '#000');
		}
		
		$allMonthDataArr['Total'] = $gt_acctExpDepoArr;
		reset($allMonthDataArr);
		//print '<pre>';print_r($allMonthDataArr);die;
		
		$this->_getExecutionTime($funcId,'',$allMonthDataArr); 
		return $allMonthDataArr;
	}
	function modifyAllocationAmt($allocation_id,$allocation_amt)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$updatePtr = $this->db->set_multiple_fields('ec_deposit', 'DEPO_AMT = "' . $allocation_amt . '"', 'ID = "' . $allocation_id . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
		/* Below code is being used for updating ec_items when modifying current months allocation */
		$chkStr = "BLANK";
		if(isset($_SESSION['curr_mon']))
		{
			if(($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR))
			{
				$chkStr = "SELECT ID,DEPO_ITEM_ID 
				FROM ec_deposit  
				WHERE ID = '" .  $allocation_id . "' 
				AND USER_ID = " . $_SESSION['curr_user_id'];  
				$chkPtr = $this->db->get_sql_exec($chkStr);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows == 1)
				{
					$chkResult = $this->db->get_one_record($chkStr);
					if($allocation_amt == 0.00)
					{
						$active = 'Y';
					}
					else{
						$active = 'Y';
					}
					$updatePtr = $this->db->set_multiple_fields('ec_items', 'ACTIVE = "' . $active . '", DEFAULT_DEPO_AMT = "' . $allocation_amt . '"', 'ID = "' . $chkResult['DEPO_ITEM_ID'] . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
				}
			}
		}
		/*--------------------*/
		$this->_getExecutionTime($funcId,$chkStr); 
	}
	function modifyPayDayVal($item_id,$payday_val)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		if(isset($_SESSION['curr_mon']))
		{
			if(($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR))
			{
				if($payday_val == 0)
				{
					$fixedPayee = 'N';
				}
				else{
					$fixedPayee = 'Y';
				}
				$updatePtr = $this->db->set_multiple_fields('ec_items', 'FIXED_PAYEE = "' . $fixedPayee . '", PAY_DAY = "' . $payday_val . '"', 'ID = "' . $item_id . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
			}
		}
		$this->_getExecutionTime($funcId);
	}
	function modifyCurrBalAmt($acct_id,$curr_bal_amt)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$currDate = date('Y-m-d h:i:s');
		$updatePtr = $this->db->set_multiple_fields('ec_accounts', 'CURRENT_BAL = "' . $curr_bal_amt . '", LAST_UPDATED = "' . $currDate . '"', 'ID = "' . $acct_id . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
		
		$this->_getExecutionTime($funcId); 
	}
	
	public function getExpForAccountByItemsMonthly($acct_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$expItemListArr = $this->_get_item_list_by_accounts($acct_id);
		$getmonArray = $this->_get_list_of_months_by_exp_type_expenditure();
		$expByItemsArray = array();
		foreach ($expItemListArr as $itemList)
		{		
			$expDetails = array();
			$sql = "SELECT a.EXP_ACCOUNT,i.EXP_ITEM_NAME,i.EXPIRED, sum(e.EXP_AMT) as exp_amt, null as depo_amt, m.MONTH_NUMBER, m.MONTH_SHORT_NAME, year(e.EXP_DATE) AS MONTH_YEAR  
			FROM ec_months AS m, ec_expenditure AS e, ec_items as i, ec_accounts as a, ec_items_cat as ic     
			WHERE month(e.EXP_DATE) = m.MONTH_NUMBER 
			AND e.exp_item_id = i.ID 
			AND i.EXP_ACCOUNT_ID = a.ID 
			AND i.EXP_ITEM_CAT_ID = ic.ID 
			AND i.ACTIVE = 'Y' 
			AND i.ID = " . $itemList['ID'];
			if($acct_id != 0){
				$sql .= " AND a.ID = " . $acct_id;
			}
			$sql .= " AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND year(e.EXP_DATE) = " . $_SESSION['exp_item_year'] . " 
			GROUP BY i.ID, month(e.EXP_DATE),year(e.EXP_DATE)  
			ORDER BY i.ID, month(e.EXP_DATE),year(e.EXP_DATE)";//print $sql;
			$chkPtr = $this->db->get_sql_exec($sql);
			$chkRows = $this->db->get_db_num_rows($chkPtr);
			if($chkRows > 0)
			{			
				$sqlResultExp = $this->db->get_multiple_tables_records($sql);
				$sqlDepo = str_replace(array('null as depo_amt', 'e.EXP_DATE', 'e.exp_item_id','ec_expenditure AS e','sum(e.EXP_AMT) as exp_amt,'), array('sum(d.DEPO_AMT) as depo_amt','d.DEPO_DATE','d.DEPO_ITEM_ID','ec_deposit as d',''), $sql);
				$sqlResultDepo = $this->db->get_multiple_tables_records($sqlDepo);
				/* Injecting depo_amt in $sqlResultExp arary */
				foreach($sqlResultExp as $ekey => $evalue){
					foreach($sqlResultDepo as $dkey => $dvalue){
						if($sqlResultExp[$ekey]['MONTH_NUMBER'] == $sqlResultDepo[$dkey]['MONTH_NUMBER']){
							$sqlResultExp[$ekey]['depo_amt'] = $sqlResultDepo[$dkey]['depo_amt'];
						}
					}
				}
				if(!empty($sqlResultExp)){
					foreach($sqlResultExp as $itemExpDetails){			
						$expDetails[] = array('month_no' => $itemExpDetails['MONTH_NUMBER'],'month_name' => $itemExpDetails['MONTH_SHORT_NAME'],'year' => $itemExpDetails['MONTH_YEAR'],'exp_amt' => $itemExpDetails['exp_amt'],'depo_amt' => $itemExpDetails['depo_amt'],'exp_acct' => $itemExpDetails['EXP_ACCOUNT']);
					}
					/* Adding Expenditure of months as 0.00 if not exist in the list -- start */
					foreach($getmonArray as $kmon => $vmon){
						foreach($vmon as $monKey => $monVal){
							$monFound = 'n';
							foreach($expDetails as $expDetailsKey){
								if($expDetailsKey['month_no'] == $monVal['month_number']){
									$monFound = 'y';break;
								}
							}
							if($monFound == 'n'){
								$expDetails[] = array('month_no' => $monVal['month_number'],'month_name' => $monVal['month_name'],'year' => $monVal['year'],'exp_amt' => 0.00,'depo_amt' => 0.00, 'exp_acct' => $sqlResultExp[0]['EXP_ACCOUNT']);
							}
						}	
					}
					$monSort = array();
					foreach ($expDetails as $key => $row){
						$monSort[$key] = $row['month_no'];
					}
					array_multisort($monSort, SORT_ASC, $expDetails);
					/*--------------------End -----------------------*/
					$colorCodeArr = $this->_get_color_code($itemList['acct_id'],'',__method__);
					$catInfoArr = $this->_get_catagory_info_by_item_id ($itemList['ID']);
					$expByItemsArray[$itemList['EXP_ITEM_NAME']] = array('item_id' => $itemList['ID'],'item_status' => $sqlResultExp[0]['EXPIRED'], 'cat_id' => $catInfoArr[0]['cat_id'],'cat_name' => $catInfoArr[0]['cat_name'],'acct_id' => $itemList['acct_id'],'acct_name' => $itemList['acct_name'],'exp_details' => $expDetails, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);		
				}
			}
			
		}//die;
		$this->_getExecutionTime($funcId,'',$expByItemsArray); 
		return $expByItemsArray;
	}
	public function _get_catagory_info_by_item_id($item_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT ec.id as cat_id,ec.exp_item_cat as cat_name,i.EXP_ITEM_NAME  
		FROM ec_items as i, ec_items_cat as ec  
		WHERE i.EXP_ITEM_CAT_ID = ec.ID 
		AND i.id = " . $item_id;
		$sqlResult = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$sqlResult); 
		return $sqlResult;		
	}
	public function _get_account_info_by_item_id($item_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT a.ID as acct_id,a.EXP_ACCOUNT as acct_name,i.EXP_ITEM_NAME  
		FROM ec_items as i, ec_accounts as a  
		WHERE i.EXP_ACCOUNT_ID = a.ID 
		AND i.id = " . $item_id;
		$sqlResult = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$sqlResult); 
		return $sqlResult;		
	}
	public function getExpForCategoryByAccountsMonthly($cat_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$expAccountListArr = $this->_get_account_list_by_cat($cat_id);
		$expByAcctArray = array();
		foreach ($expAccountListArr as $acctList)
		{		
			$expDetails = array();
			$sql = "SELECT a.ID,a.EXP_ACCOUNT, ic.exp_item_cat, m.MONTH_NUMBER, m.MONTH_SHORT_NAME, sum(e.EXP_AMT) as exp_amt, null as depo_amt, year(e.EXP_DATE) AS MONTH_YEAR  
			FROM ec_months AS m, ec_items_cat as ic, ec_items as i, ec_accounts as a, ec_expenditure AS e     
			WHERE i.EXP_ITEM_CAT_ID = ic.ID 
			AND i.EXP_ACCOUNT_ID = a.ID ";
			if($cat_id != 0){
				$sql .= " AND ic.ID = " . $cat_id; 			
			}				
			$sql .= " AND a.ID = " . $acctList['ID'] . " 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . "
			AND e.exp_item_id = i.ID 
			AND i.ACTIVE = 'Y' 
			AND month(e.EXP_DATE) = m.MONTH_NUMBER 
			AND year(e.EXP_DATE) = " . $_SESSION['exp_item_year'] . " 
			GROUP BY a.ID, month(e.EXP_DATE),year(e.EXP_DATE) 
			ORDER BY month(e.EXP_DATE),year(e.EXP_DATE)";
			$chkPtr = $this->db->get_sql_exec($sql);
			$chkRows = $this->db->get_db_num_rows($chkPtr);
			if($chkRows > 0)
			{				
				$sqlResultExp = $this->db->get_multiple_tables_records($sql);
				
				$sqlDepo = str_replace(array('null as depo_amt', 'e.EXP_DATE', 'e.exp_item_id','ec_expenditure AS e','sum(e.EXP_AMT) as exp_amt,'), array('sum(d.DEPO_AMT) as depo_amt','d.DEPO_DATE','d.DEPO_ITEM_ID','ec_deposit as d',''), $sql);
				$sqlResultDepo = $this->db->get_multiple_tables_records($sqlDepo);
				
				/* Injecting depo_amt in $sqlResultExp arary */
				foreach($sqlResultExp as $ekey => $evalue){
					foreach($sqlResultDepo as $dkey => $dvalue){
						if($sqlResultExp[$ekey]['MONTH_NUMBER'] == $sqlResultDepo[$dkey]['MONTH_NUMBER']){
							$sqlResultExp[$ekey]['depo_amt'] = $sqlResultDepo[$dkey]['depo_amt'];
						}
					}
				}
				if(!empty($sqlResultExp)){			
					foreach($sqlResultExp as $iAcctExpDetails){			
						$expDetails[] = array('month_no' => $iAcctExpDetails['MONTH_NUMBER'],'month_name' => $iAcctExpDetails['MONTH_SHORT_NAME'],'year' => $iAcctExpDetails['MONTH_YEAR'],'exp_amt' => $iAcctExpDetails['exp_amt'],'depo_amt' => $iAcctExpDetails['depo_amt'],'exp_acct' => $iAcctExpDetails['EXP_ACCOUNT']);
					}
					$colorCodeArr = $this->_get_color_code($acctList['ID'],'', __method__);
					$expByAcctArray[$acctList['EXP_ACCOUNT']] = array('acct_id' => $acctList['ID'], 'exp_details' => $expDetails, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);		
				}
			}
		}
		$this->_getExecutionTime($funcId,'',$expByAcctArray); 
		return $expByAcctArray;
	}
	public function getExpForCategoryByItemsMonthly($cat_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$expItemListArr = $this->_get_item_list_by_category($cat_id);
		$getmonArray = $this->_get_list_of_months_by_exp_type_expenditure();
		$expByItemsArray = array();
		if(!empty($expItemListArr))
		{
			foreach ($expItemListArr as $itemList)
			{		
				$expDetails = array();
				$sql = "SELECT a.EXP_ACCOUNT,i.EXP_ITEM_NAME, i.EXPIRED, c.ID,c.EXP_ITEM_CAT, sum(e.EXP_AMT) as exp_amt, null as depo_amt, m.MONTH_NUMBER, m.MONTH_SHORT_NAME, year(e.EXP_DATE) AS MONTH_YEAR  
				FROM ec_months AS m, ec_expenditure AS e, ec_items as i, ec_accounts as a, ec_items_cat as c     
				WHERE month(e.EXP_DATE) = m.MONTH_NUMBER 
				AND e.exp_item_id = i.ID 
				AND i.EXP_ACCOUNT_ID = a.ID
				AND i.EXP_ITEM_CAT_ID = c.ID 
				AND i.ACTIVE = 'Y' 				
				AND i.ID = " . $itemList['ID'];
				if($cat_id != 0)
				{
					$sql .= " AND c.ID = " . $cat_id;
				
				}
				$sql .= " AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
				AND year(e.EXP_DATE) = " . $_SESSION['exp_item_year'] . " 
				GROUP BY i.ID, month(e.EXP_DATE),year(e.EXP_DATE) 
				ORDER BY i.ID, month(e.EXP_DATE),year(e.EXP_DATE)";//	print $sql . "<br>";
				$chkPtr = $this->db->get_sql_exec($sql);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows > 0)
				{			
					$sqlResultExp = $this->db->get_multiple_tables_records($sql);
					$sqlDepo = str_replace(array('null as depo_amt', 'e.EXP_DATE', 'e.exp_item_id','ec_expenditure AS e','sum(e.EXP_AMT) as exp_amt,'), array('sum(d.DEPO_AMT) as depo_amt','d.DEPO_DATE','d.DEPO_ITEM_ID','ec_deposit as d',''), $sql);
					$sqlResultDepo = $this->db->get_multiple_tables_records($sqlDepo);
					
					/* Injecting depo_amt in $sqlResultExp arary */
					foreach($sqlResultExp as $ekey => $evalue){
						foreach($sqlResultDepo as $dkey => $dvalue){
							if($sqlResultExp[$ekey]['MONTH_NUMBER'] == $sqlResultDepo[$dkey]['MONTH_NUMBER']){
								$sqlResultExp[$ekey]['depo_amt'] = $sqlResultDepo[$dkey]['depo_amt'];
							}
						}
					}
					if(!empty($sqlResultExp)){						
						foreach($sqlResultExp as $itemExpDetails)
						{			
							$expDetails[] = array('month_no' => $itemExpDetails['MONTH_NUMBER'],'month_name' => $itemExpDetails['MONTH_SHORT_NAME'],'year' => $itemExpDetails['MONTH_YEAR'],'exp_amt' => $itemExpDetails['exp_amt'],'depo_amt' => $itemExpDetails['depo_amt'],'exp_acct' => $itemExpDetails['EXP_ACCOUNT']);
						}
						/* Adding Expenditure of months as 0.00 if not exist in the list -- start */
						foreach($getmonArray as $kmon => $vmon){
							foreach($vmon as $monKey => $monVal){
								$monFound = 'n';
								foreach($expDetails as $expDetailsKey){
									if($expDetailsKey['month_no'] == $monVal['month_number']){
										$monFound = 'y';break;
									}
								}
								if($monFound == 'n'){
									$expDetails[] = array('month_no' => $monVal['month_number'],'month_name' => $monVal['month_name'],'year' => $monVal['year'],'exp_amt' => 0.00,'depo_amt' => 0.00,'exp_acct' => $sqlResultExp[0]['EXP_ACCOUNT']);
								}
							}	
						}
						$monSort = array();
						foreach ($expDetails as $key => $row){
							$monSort[$key] = $row['month_no'];
						}
						array_multisort($monSort, SORT_ASC, $expDetails);
						/*--------------------End -----------------------*/
						
						$acctInfoArr = $this->_get_account_info_by_item_id ($itemList['ID']);
						$colorCodeArr = $this->_get_color_code($itemList['ID'],'item',__method__);
						$expByItemsArray[$itemList['EXP_ITEM_NAME']] = array('item_id' => $itemList['ID'],'item_status' => $sqlResultExp[0]['EXPIRED'], 'cat_id' => $cat_id,'cat_name' => $itemList['cat_name'],'acct_id' => $acctInfoArr[0]['acct_id'],'acct_name' => $acctInfoArr[0]['acct_name'],'exp_details' => $expDetails, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);
					}
				}
				
			}//die;
		}
		$this->_getExecutionTime($funcId,'',$expByItemsArray); 
		return $expByItemsArray;
	}	
	function getExpByItemCategoryMonthly()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$itemCatListArr = $this->_get_item_cat_list();//print '<pre>';print_r($itemCatListArr);
		$expByCatArray = array();
		
		foreach ($itemCatListArr as $catList)
		{		
			$expDetails = array();
			$sql = "SELECT ic.exp_item_cat, sum(e.EXP_AMT) as exp_amt, null as depo_amt, m.MONTH_NUMBER, m.MONTH_SHORT_NAME, year(e.EXP_DATE) AS MONTH_YEAR  
			FROM ec_months AS m, ec_expenditure AS e, ec_items_cat as ic, ec_items as i    
			WHERE month(e.EXP_DATE) = m.MONTH_NUMBER 
			AND e.exp_item_id = i.ID 
			AND i.EXP_ITEM_CAT_ID = ic.ID 
			AND i.ACTIVE = 'Y' 
			AND ic.ID = " . $catList['ID'] . " 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND year(e.EXP_DATE) = " . $_SESSION['exp_item_year'] . " 
			GROUP BY ic.ID, month(e.EXP_DATE),year(e.EXP_DATE) 
			ORDER BY month(e.EXP_DATE),year(e.EXP_DATE)";
			$chkPtr = $this->db->get_sql_exec($sql);
			$chkRows = $this->db->get_db_num_rows($chkPtr);//print $sql . '<br>';
			if($chkRows > 0)
			{			
				$sqlResultExp = $this->db->get_multiple_tables_records($sql);
				$sqlDepo = str_replace(array('null as depo_amt', 'e.EXP_DATE', 'e.exp_item_id','ec_expenditure AS e','sum(e.EXP_AMT) as exp_amt,'), array('sum(d.DEPO_AMT) as depo_amt','d.DEPO_DATE','d.DEPO_ITEM_ID','ec_deposit as d',''), $sql);
				$sqlResultDepo = $this->db->get_multiple_tables_records($sqlDepo);
				
				/* Injecting depo_amt in $sqlResultExp arary */
				foreach($sqlResultExp as $ekey => $evalue){
					foreach($sqlResultDepo as $dkey => $dvalue){
						if($sqlResultExp[$ekey]['MONTH_NUMBER'] == $sqlResultDepo[$dkey]['MONTH_NUMBER']){
							$sqlResultExp[$ekey]['depo_amt'] = $sqlResultDepo[$dkey]['depo_amt'];
						}
					}
				}
				if(!empty($sqlResultExp)){
					foreach($sqlResultExp as $iCatExpDetails)
					{			
						$expDetails[] = array('month_no' => $iCatExpDetails['MONTH_NUMBER'],'month_name' => $iCatExpDetails['MONTH_SHORT_NAME'],'year' => $iCatExpDetails['MONTH_YEAR'],'exp_amt' => $iCatExpDetails['exp_amt'],'depo_amt' => $iCatExpDetails['depo_amt'],'icat' => $iCatExpDetails['exp_item_cat']);
					}
					$colorCodeArr = $this->_get_color_code($catList['ID'],'category',__method__);
					$expByCatArray[$catList['EXP_ITEM_CAT']] = array('cat_id' => $catList['ID'], 'exp_details' => $expDetails, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);
				}
			}
		}
		$this->_getExecutionTime($funcId,'',$expByCatArray); 
		return $expByCatArray;
	}
	function getCurrentMonthDomData($what='Accounts', $timeDuration)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		if($timeDuration != '' && $timeDuration != 'All_Time')
		{
			$monYearArr = explode("-",$timeDuration);
			$curMonth = $monYearArr[0];
			$currYear = $monYearArr[1];
		}
		else
		{
			$curMonth = CURRENT_MONTH;
			$currYear = CURRENT_YEAR;			
		}
		if($what == 'Category')
		{
			$tempListArr = $this->_get_item_cat_list();
		}
		if($what == 'Accounts')
		{
			$tempListArr = $this->_get_accounts_list();
		}
		if($what == 'Items')
		{
			$tempListArr = $this->_get_items_list();
		}
					
		$expDetailsArray = array();
		//print $what;die;
		foreach ($tempListArr as $tempList)
		{		
			$sql = "SELECT ic.exp_item_cat, ic.ID as cat_id, i.EXP_ITEM_NAME, i.ID as item_id, a.EXP_ACCOUNT, a.ID as account_id, sum(e.EXP_AMT) as expamt, m.MONTH_NUMBER, m.MONTH_SHORT_NAME, year(e.EXP_DATE) AS MONTH_YEAR  
			FROM ec_months AS m, ec_expenditure AS e, ec_items_cat as ic, ec_items as i, ec_accounts as a    
			WHERE month(e.EXP_DATE) = m.MONTH_NUMBER 
			AND e.EXP_ITEM_ID = i.ID 
			AND i.ACTIVE = 'Y' 
			AND i.EXP_ITEM_CAT_ID = ic.ID 
			AND i.EXP_ACCOUNT_ID = a.ID ";
			if($what == 'Category')
				$sql .= " AND ic.ID = " . $tempList['ID']; 
			if($what == 'Accounts')
				$sql .= " AND a.ID = " . $tempList['ID'];
			if($what == 'Items')
				$sql .= " AND i.ID = " . $tempList['ID'];
  			
			$sql .= " AND i.USER_ID = " . $_SESSION['curr_user_id'];
			if($timeDuration != 'All_Time'){
				$sql .= " AND month(e.EXP_DATE) = " . $curMonth . " AND year(e.EXP_DATE) = " . $currYear;//print $sql . "<br><br>";  
			}
			$chkPtr = $this->db->get_sql_exec($sql);
			$chkRows = $this->db->get_db_num_rows($chkPtr);
			if($chkRows > 0)
			{			
				$sqlResult = $this->db->get_one_record($sql);
				//Overiding depo amt as required group by depo item
				$sqlDepo = "SELECT sum(d.DEPO_AMT) as depoamt, m.MONTH_NUMBER, m.MONTH_SHORT_NAME   
				FROM ec_months AS m, ec_deposit AS d, ec_items_cat as ic, ec_items as i, ec_accounts as a    
				WHERE month(d.DEPO_DATE) = m.MONTH_NUMBER
				AND d.DEPO_ITEM_ID = i.ID 
				AND i.ACTIVE = 'Y' 
				AND i.EXP_ACCOUNT_ID = a.ID 
				AND i.EXP_ITEM_CAT_ID = ic.ID ";
				if($what == 'Category')
					$sqlDepo .= " AND ic.ID = " . $tempList['ID']; 
				if($what == 'Accounts')
					$sqlDepo .= " AND a.ID = " . $tempList['ID'];
				if($what == 'Items')
					$sqlDepo .= " AND i.ID = " . $tempList['ID'];
				$sqlDepo .= " AND i.USER_ID = " . $_SESSION['curr_user_id']; 
				if($timeDuration != 'All_Time'){
					$sqlDepo .= " AND month(d.DEPO_DATE) = " . $curMonth . " AND year(d.DEPO_DATE) = " . $currYear;//print $sql . "<br><br>";  
				}
				//print $sqlDepo;die;
				$depoAmtCalculate = $this->db->get_one_record($sqlDepo);
				
				if($depoAmtCalculate['depoamt'] == 0)
				{
					$expPercent = 0.00;
				}else{
				$expPercent = ($sqlResult['expamt'] * 100)/$depoAmtCalculate['depoamt'];
				}
				$balAmt = $depoAmtCalculate['depoamt'] - $sqlResult['expamt'];
				$balPercent = (100 - $expPercent);
				
				
				/****************************/
				if($what == 'Category'){
					$temp_id = $sqlResult['cat_id'];
					$temp_name = $sqlResult['exp_item_cat'];
					$temp_link = '/showExpForCategoryByAccountsMonthly/' . $temp_id;
					$colorCodeArr = $this->_get_color_code($sqlResult['cat_id'], 'category',__method__);
				}	
				if($what == 'Accounts'){
					$temp_id = $sqlResult['account_id'];
					$temp_name = $sqlResult['EXP_ACCOUNT'];
					$temp_link = '/showExpForAccountByItemsMonthly/' . $temp_id;
					$colorCodeArr = $this->_get_color_code($sqlResult['account_id'], 'account',__method__);
				}
				if($what == 'Items'){
					$temp_id = $sqlResult['item_id'];
					$temp_name = $sqlResult['EXP_ITEM_NAME'];
					$temp_link = '/showExpForCategoryByItemsMonthly/' . $sqlResult['cat_id'];
					$colorCodeArr = $this->_get_color_code($sqlResult['item_id'], 'item',__method__);
				}				
				/****************************/
				$expDetailsArray[] = array('month_no' => $sqlResult['MONTH_NUMBER'],'month_name' => $sqlResult['MONTH_SHORT_NAME'],'year' => $sqlResult['MONTH_YEAR'],'exp_amt' => $sqlResult['expamt'],'depo_amt' => $depoAmtCalculate['depoamt'],'exp_percent' => $expPercent,'bal_amt' => $balAmt,'bal_percent' => $balPercent,'temp_name' => $temp_name,'temp_id' => $temp_id,'temp_link' => $temp_link, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);
			}
		}
		$expAmtSort = array();
		foreach ($expDetailsArray as $key => $row){
			$expAmtSort[$key] = $row['exp_percent'];
		}
		array_multisort($expAmtSort, SORT_DESC, $expDetailsArray);		
		$this->_getExecutionTime($funcId,'',$expDetailsArray); 
		return $expDetailsArray;		
	}
	function customRedirect($url, $statusCode = 303)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$this->_getExecutionTime($funcId); 
		header('Location: ' . $url, true, $statusCode);
		die();
	}
	function checkAndSetUserLogin($uname,$upwd)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$currDate = date('Y-m-d h:i:s');
		$chkStr = "SELECT ID,USER_NAME 
		FROM ec_users  
		WHERE USER_NAME = '" .  trim($uname) . "' 
		AND USER_PWD = '" .  trim($upwd) . "'";  
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 1)
		{
			$chkUserResult = $this->db->get_one_record($chkStr);
			$_SESSION['curr_user_id'] = $chkUserResult['ID'];
			$_SESSION['curr_user_name'] = $chkUserResult['USER_NAME'];
			$token = md5($chkUserResult['USER_NAME'] . date('Y-m-d h:i:s')); 
			$_SESSION['curr_user_token'] = $token;
			$updatePtr = $this->db->set_multiple_fields('ec_users', 'USER_TOKEN = "' . $token . '", USER_LAST_LOGGED = "' . $currDate . '"', 'ID = "' . $chkUserResult['ID'] . '"');
			$this->_getExecutionTime($funcId,$chkStr,$chkUserResult); 
			return true;
		}
		else
		{
			session_destroy();
			$this->_getExecutionTime($funcId,$chkStr); 
			return false;
		}
		
	}
	function checkIfUserLogged()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		if(isset($_SESSION['curr_user_id']) && isset($_SESSION['curr_user_token']))
		{
			$chkStr = "SELECT ID 
			FROM ec_users  
			WHERE ID = '" .  $_SESSION['curr_user_id'] . "' 
			AND USER_TOKEN = '" .  $_SESSION['curr_user_token'] . "'";
			$chkPtr = $this->db->get_sql_exec($chkStr);
			$chkRows = $this->db->get_db_num_rows($chkPtr);
			if($chkRows == 1)
			{
				$this->_getExecutionTime($funcId,$chkStr); 
				return true;
			}
			else
			{
				session_destroy();
				$this->_getExecutionTime($funcId,$chkStr); 
				return false;		
			}
		}
		else
		{

			session_destroy();
			$this->_getExecutionTime($funcId); 
			return false;			
		}
	}
	function userLogout()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		session_destroy();
		$this->_getExecutionTime($funcId); 
		$this->customRedirect('/index.php');
	}

	function _get_list_of_months_by_exp_type_expenditure()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT m.MONTH_NUMBER, m.MONTH_SHORT_NAME, year(e.EXP_DATE) AS MONTH_YEAR  
			FROM  ec_months AS m, ec_expenditure AS e, ec_items_cat as ic, ec_items as i    
			WHERE month(e.EXP_DATE) = m.MONTH_NUMBER 
			AND e.exp_item_id = i.ID 
			AND i.EXP_ITEM_CAT_ID = ic.ID 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
			AND year(e.EXP_DATE) = " . $_SESSION['exp_item_year'] . " 
			GROUP BY month(e.EXP_DATE),year(e.EXP_DATE) 
			ORDER BY year(e.EXP_DATE),month(e.EXP_DATE)";
		$result = $this->db->get_multiple_tables_records($sql);
		$monYearArr = array();
		for($i = $_SESSION['exp_item_year']; $i <= $_SESSION['exp_item_year']; $i++)
		{
			$monArr = array();
			foreach ($result as $monList)
			{
				if($i <= $_SESSION['exp_item_year'])
				{
					$monArr[] = array('year' => $monList['MONTH_YEAR'],'month_number' => $monList['MONTH_NUMBER'], 'month_name' => $monList['MONTH_SHORT_NAME']);
				}
			}
			$monYearArr[] = $monArr;
		}
		$this->_getExecutionTime($funcId,$sql,$monYearArr); 
		return $monYearArr;
	}	
	function _get_list_of_months()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM ec_months 
		ORDER BY MONTH_NUMBER ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$monYearArr = array();
		for($i = 2017; $i <= CURRENT_YEAR; $i++)
		{
			$monArr = array();
			foreach ($result as $monList)
			{
				if($i <= CURRENT_YEAR)
				{
					$monArr[] = array('month_number' => $monList['MONTH_NUMBER'], 'month_name' => $monList['MONTH_FULL_NAME']);
					if($monList['MONTH_NUMBER'] == CURRENT_MONTH){
						$_SESSION['curr_mon_name'] = $monList['MONTH_FULL_NAME'];
					}
				}
			}
			$monYearArr[$i] = $monArr;
		}
		if(!isset($_SESSION['curr_mon']))
		{
			$_SESSION['curr_mon'] = CURRENT_MONTH;
			$_SESSION['curr_yr'] = CURRENT_YEAR;
			
		}
		$this->_getExecutionTime($funcId,$sql,$monYearArr); 
		return $monYearArr;
	}
	public function _get_item_cat_name_by_id($cat_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT exp_item_cat 
		FROM ec_items_cat ";
		if($cat_id != 0)
		{
			$sql .= " WHERE id = " . $cat_id;
			$cat_result = $this->db->get_one_record($sql);
			$this->_getExecutionTime($funcId,$sql,$cat_result); 
			return $cat_result['exp_item_cat'];	
		}
		else
		{
			$this->_getExecutionTime($funcId,$sql); 
			return "ALL ACCOUNTS";			
		}			

	}
	public function _get_acct_name_by_id($acct_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT EXP_ACCOUNT 
		FROM ec_accounts ";
		if($acct_id !=0)
		{
			$sql .= " WHERE id = " . $acct_id;
			$cat_result = $this->db->get_one_record($sql);
			$this->_getExecutionTime($funcId,$sql,$cat_result); 
			return $cat_result['EXP_ACCOUNT'];
		}
		else
		{
			$this->_getExecutionTime($funcId,$sql); 
			return "ALL ITEMS";
		}
		
		
		
	}	
	function _get_account_list_by_cat($cat_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT a.ID,a.EXP_ACCOUNT 
		FROM ec_expenditure AS e, ec_items_cat as ic, ec_items as i, ec_accounts as a     
		WHERE e.exp_item_id = i.ID 
		AND i.EXP_ITEM_CAT_ID = ic.ID 
		AND i.EXP_ACCOUNT_ID = a.ID ";
		if($cat_id != 0)
		{
			$sql .= " AND ic.ID = " . $cat_id; 			
		}
		$sql .= " AND i.ACTIVE = 'Y' AND i.USER_ID = " . $_SESSION['curr_user_id'] . "
		GROUP BY a.EXP_ACCOUNT";
		$sqlResult = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$sqlResult); 
		return $sqlResult;
	}
	function _get_item_list_by_accounts($acct_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT i.ID,i.EXP_ITEM_NAME,a.EXP_ACCOUNT as acct_name, a.ID as acct_id 
		FROM ec_expenditure AS e, ec_items as i, ec_accounts as a     
		WHERE e.exp_item_id = i.ID 
		AND i.EXP_ACCOUNT_ID = a.ID";
		if($acct_id != 0)
		{
			$sql .= " AND a.ID = " . $acct_id;
			$sql .= " AND i.ACTIVE = 'Y' 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . "
			GROUP BY i.EXP_ITEM_NAME 
			ORDER BY i.EXP_ITEM_NAME ASC";			
		}
		else
		{
			$sql .= " AND i.ACTIVE = 'Y' 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . "
			GROUP BY i.EXP_ITEM_NAME 
			ORDER BY a.ID ASC";				
		}//print $sql . "<br>";
		$sqlResult = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$sqlResult); 
		return $sqlResult;
	}
	function _get_item_list_by_category($cat_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT i.ID,i.EXP_ITEM_NAME,c.EXP_ITEM_CAT as cat_name, c.ID as cat_id 
		FROM ec_expenditure AS e, ec_items as i, ec_items_cat as c     
		WHERE e.exp_item_id = i.ID 
		AND i.EXP_ITEM_CAT_ID = c.ID";
		if($cat_id != 0)
		{
			$sql .= " AND c.ID = " . $cat_id;
			$sql .= " AND i.ACTIVE = 'Y' 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . "
			GROUP BY i.EXP_ITEM_NAME 
			ORDER BY i.EXP_ITEM_NAME ASC";			
		}
		else
		{
			$sql .= " AND i.ACTIVE = 'Y' 
			AND i.USER_ID = " . $_SESSION['curr_user_id'] . "
			GROUP BY i.EXP_ITEM_NAME 
			ORDER BY c.ID ASC";				
		}//print $sql . "<br>";
		$sqlResult = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$sqlResult); 
		return $sqlResult;
	}
	function _get_items_list($item_id=0)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT *  
		FROM ec_items      
		WHERE ACTIVE = 'Y'";
		
		if($item_id != 0)
		{
			$sql .= " AND ID = " . $item_id ;
		}
		$sql .= " AND USER_ID = " . $_SESSION['curr_user_id'] . "
			ORDER BY EXP_ITEM_NAME ASC";				
		$sqlResult = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$sqlResult); 
		return $sqlResult;
	}
	function _get_accounts_list()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$chkStr = "SELECT a.*, c.BG_COLOR,c.FONT_COLOR 
		FROM ec_accounts AS a, ec_color_code AS c  
		WHERE a.USER_ID = " . $_SESSION['curr_user_id'] . "
		AND c.USER_ID = " . $_SESSION['curr_user_id'] . " 		
		AND c.ID = a.COLOR_CODE_ID 
		ORDER BY exp_account ASC";
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 0)
		{
			$this->_getExecutionTime($funcId,$chkStr); 
			print "Sorry!! Account are not set for this User.";die;
		}
		else
		{
			$result = $this->db->get_multiple_tables_records($chkStr);
			$this->_getExecutionTime($funcId,$chkStr,$result); //print 'heree';die;
			return $result;		
		}

	}
	function _get_system_or_cf_balance($acct_id, $what='')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sqlExp = "SELECT sum(e.EXP_AMT) as amt 
		FROM `ec_expenditure` AS e, ec_items AS i, ec_accounts AS a
		WHERE e.EXP_ITEM_ID = i.ID 
		AND i.EXP_ACCOUNT_ID = a.ID 
		AND i.EXP_ACCOUNT_ID = " . $acct_id . " 
		AND i.ACTIVE = 'Y' 
		AND e.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND a.USER_ID = " . $_SESSION['curr_user_id'];
		if($what == 'cf')
		{
			$sqlExp .= " AND year(e.EXP_DATE) <= " . CURRENT_YEAR . " AND month(e.EXP_DATE) <  " . CURRENT_MONTH;
		}
		$exp_result = $this->db->get_one_record($sqlExp);
		$sqlDepo = "SELECT sum(d.DEPO_AMT) as amt 
		FROM `ec_deposit` AS d, ec_items AS i, ec_accounts AS a 
		WHERE d.DEPO_ITEM_ID = i.ID 
		AND i.EXP_ACCOUNT_ID = a.ID 
		AND i.EXP_ACCOUNT_ID = " . $acct_id . " 
		AND i.ACTIVE = 'Y' 
		AND d.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND a.USER_ID = " . $_SESSION['curr_user_id'];
		if($what == 'cf')
		{
			$sqlDepo .= " AND year(d.DEPO_DATE) <= " . CURRENT_YEAR . " AND month(d.DEPO_DATE) <  " . CURRENT_MONTH;
		}
		$depo_result = $this->db->get_one_record($sqlDepo);		
		$this->_getExecutionTime($funcId,$sqlExp,$exp_result);//print $sqlExp . "<Br><br>";
		return ($depo_result['amt'] - $exp_result['amt']);
	}
	function _get_primary_current_balance($acct_id,$what='current')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM ec_accounts 
		WHERE ID = " . $acct_id . " 
		AND USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_one_record($sql);
		if($what == "primary")
		{
			$this->_getExecutionTime($funcId,$sql,$result); 
			return $result['PRIMARY_BAL'];
		}
		if($what == "current")
		{
			$this->_getExecutionTime($funcId,$sql,$result); 
			return $result['CURRENT_BAL'];
		}
		
	}
	function _get_recurring_or_savings($acct_id, $what_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT sum(e.EXP_AMT) AS amt
		FROM ec_expenditure AS e, ec_items AS i, ec_accounts AS a 
		WHERE e.EXP_ITEM_ID = i.ID 
		AND i.EXP_ACCOUNT_ID = a.ID 
		AND i.ACTIVE = 'Y' 
		AND i.EXPIRED = 'N' 
		AND i.EXP_ITEM_CAT_ID = " . $what_id . "  
		AND a.ID = " . $acct_id . " 
		AND e.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND i.USER_ID = " . $_SESSION['curr_user_id'] . " 
		AND a.USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_one_record($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result['amt'];
	}
	/************** total exp and depo by CATEGORY *******Start****/ 
	function _get_total_exp_amount_by_category($cat_id=0, $considerExpire='noexpire')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT sum(e.EXP_AMT) AS amt
		FROM ec_expenditure AS e, ec_items AS i  
		WHERE e.EXP_ITEM_ID = i.ID";
		if($cat_id > 0)
		{
			$sql .= " AND i.EXP_ITEM_CAT_ID = " . $cat_id; 
		}
		$sql .= " AND e.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.ACTIVE = 'Y'";
		if($considerExpire == 'expire')
		{
			$sql .= " AND i.EXPIRED = 'N'";
		}
		$result = $this->db->get_one_record($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result['amt'];		
	}
	function _get_total_depo_amount_by_category($cat_id=0, $considerExpire='noexpire')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT sum(d.DEPO_AMT) AS amt
		FROM ec_deposit AS d, ec_items AS i  
		WHERE d.DEPO_ITEM_ID = i.ID";
		if($cat_id > 0)
		{
			$sql .= " AND i.EXP_ITEM_CAT_ID = " . $cat_id; 
		}
		$sql .= " AND d.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.ACTIVE = 'Y'";		
		if($considerExpire == 'expire')
		{
			$sql .= " AND i.EXPIRED = 'N'";
		}		
		$result = $this->db->get_one_record($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result['amt'];		
	}
	/************** total exp and depo by CATEGORY *******End****/ 
	
	/************** total exp and depo by ACCOUNT *******Start****/ 
	function _get_total_exp_amount_by_account($acct_id=0, $considerExpire='noexpire')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT sum(e.EXP_AMT) AS amt
		FROM ec_expenditure AS e, ec_items AS i  
		WHERE e.EXP_ITEM_ID = i.ID";
		if($acct_id > 0)
		{
			$sql .= " AND i.EXP_ACCOUNT_ID = " . $acct_id;
		}
		$sql .= " AND e.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.ACTIVE = 'Y'"; 
		if($considerExpire == 'expire')
		{
			$sql .= " AND i.EXPIRED = 'N'";
		}		
		$result = $this->db->get_one_record($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result['amt'];		
	}
	function _get_total_depo_amount_by_account($acct_id=0, $considerExpire='noexpire')
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT sum(d.DEPO_AMT) AS amt
		FROM ec_deposit AS d, ec_items AS i  
		WHERE d.DEPO_ITEM_ID = i.ID";
		if($acct_id > 0)
		{
			$sql .= " AND i.EXP_ACCOUNT_ID = " . $acct_id;
		}
		$sql .= " AND d.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.USER_ID = " . $_SESSION['curr_user_id'] . " AND i.ACTIVE = 'Y'";
		if($considerExpire == 'expire')
		{
			$sql .= " AND i.EXPIRED = 'N'";
		}		
		$result = $this->db->get_one_record($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result['amt'];		
	}	
	/************** total exp and depo by ACCOUNT *******End****/ 
	function _get_expected_sys_bal($acct_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM ec_accounts 
		WHERE USER_ID = " . $_SESSION['curr_user_id'] . " 
		ORDER BY exp_account ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result;
	}
	function _get_actual_available($acct_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM ec_accounts 
		WHERE USER_ID = " . $_SESSION['curr_user_id'] . " 
		ORDER BY exp_account ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result;
	}
	function _get_emergency_available($acct_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM ec_accounts 
		WHERE USER_ID = " . $_SESSION['curr_user_id'] . " 		
		ORDER BY exp_account ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result;
	}
	function _get_item_cat_list()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM ec_items_cat  
		WHERE USER_ID = " . $_SESSION['curr_user_id'] . " 
		ORDER BY EXP_ITEM_CAT ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql,$result); 
		return $result;
	}
	
	
	/**********************************COM WORD SECTION START ************************************/
	function doDefaultEntryForWordSent()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT ID 
		FROM com_words  
		WHERE USER_ID = " . $_SESSION['curr_user_id'];
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
		if($chkSqlRows == 0)
		{
			$qry = "INSERT INTO com_words (`ID`, `COM_WORD`, `COM_WORD_MEAN`, `USER_ID` )  VALUES 
			(NULL, 'Test', 'Test', " . $_SESSION['curr_user_id'] . ")";
			$ptr = 	$this->db->get_sql_exec($qry);
			$word_id = $this->db->get_last_insert_id();
			$qry = "INSERT INTO com_words_sent (`ID`, `COM_WORD_ID`, `COM_WORD_SENT`, `USER_ID` )  VALUES 
			(NULL, " . $word_id . ", 'TestSent', " . $_SESSION['curr_user_id'] . ")";
			$ptr = 	$this->db->get_sql_exec($qry);			
		}
		$this->_getExecutionTime($funcId);
	}
	function doDefaultEntryForQuesListWithAns()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$currDate = date('Y-m-d h:i:s');
		$sql = "SELECT ID 
		FROM com_jour  
		WHERE USER_ID = " . $_SESSION['curr_user_id'];
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
		if($chkSqlRows == 0)
		{
			$qry = "INSERT INTO com_jour (`ID`, `COM_QUES`, `COM_ANS`, `CREATED_ON`, `USER_ID` )  VALUES 
			(NULL, 'Testques', 'TestAns', '" . $currDate . "', " . $_SESSION['curr_user_id'] . ")";
			$ptr = 	$this->db->get_sql_exec($qry);
		}
		$this->_getExecutionTime($funcId);	
	}
	function _get_word_list()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM com_words  
		WHERE USER_ID = " . $_SESSION['curr_user_id'] . " 
		ORDER BY COM_WORD ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId); 
		return $result;
	}	
	function getWordListWithSent()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$wordListArr = $this->_get_word_list();
		$finalWordArray = array();
		$i = 1;
		$t_allocate_amt = 0;
		foreach ($wordListArr as $wordList)
		{
			$sentArray = array();
			$word_id = $wordList['ID'];
			$sql = "SELECT w.ID, s.ID AS SENT_ID,s.COM_WORD_SENT  
			FROM com_words AS w,com_words_sent AS s   
			WHERE w.ID = s.COM_WORD_ID 
			AND w.ID = " . $word_id . "
			AND w.USER_ID = " . $_SESSION['curr_user_id'] . " 
			ORDER BY s.COM_WORD_SENT ASC";		
			$sqlResult = $this->db->get_multiple_tables_records($sql);
			foreach($sqlResult as $sentDetails)
			{			
				$sentArray[] = array('word_id' => $word_id,'sent_id' => $sentDetails['SENT_ID'],'sent_text' => $sentDetails['COM_WORD_SENT']);
			}
			$colorCodeArr = $this->_get_color_code(0,'',__method__);			
			$finalWordArray[] = array('word_id' => $word_id,'word_name' => $wordList['COM_WORD'],'word_mean' => $wordList['COM_WORD_MEAN'],'sent_info' => $sentArray, 'bg_color' => $colorCodeArr[0]['BG_COLOR'], 'font_color' => $colorCodeArr[0]['FONT_COLOR']);
			$i++;
		}
		$this->_getExecutionTime($funcId); 
		return $finalWordArray;		
	}
	function getQuesListWithAns()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
		FROM com_jour  
		WHERE USER_ID = " . $_SESSION['curr_user_id'] . " 
		ORDER BY ID ASC";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId); 
		return $result;
	}
	
	/**********************************COM WORD SECTION END ************************************/
	
	/* THEME CSS FROM DATABASE -- START */
	function getActiveThemeInfo()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT t.THEME_HEADER_HTML,t.THEME_FOOTER_HTML, t.THEME_CSS, ut.ID, ut.THEME_ID, ut.ACTIVE 
			FROM ec_themes AS t, ec_users_themes AS ut 
			WHERE t.ID = ut.THEME_ID 
			AND ut.ACTIVE = 'Y' 
			AND t.ACTIVE = 'Y'";		
		if(isset($_SESSION['curr_user_id']))
		{
			$tmp = " AND ut.USER_ID = " . $_SESSION['curr_user_id']; 
			$finalsql = $sql . $tmp;
			$chkSqlPtr = $this->db->get_sql_exec($finalsql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
			if($chkSqlRows == 0)
			{
				$tmp = " AND ut.USER_ID = 0"; 
				$finalsql = $sql . $tmp;
				$themeInfoResult = $this->db->get_one_record($finalsql);
			}
			else
			{
				$themeInfoResult = $this->db->get_one_record($finalsql);
			}
		}
		else
		{
			$tmp = " AND ut.USER_ID = 0"; 
			$finalsql = $sql . $tmp;
			$themeInfoResult = $this->db->get_one_record($finalsql);		
		}
		$this->_getExecutionTime($funcId,$sql); 
		return $themeInfoResult;		
	}
	function getAvailableThemes()
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$sql = "SELECT * 
			FROM ec_themes   
			WHERE ACTIVE = 'Y'";
		$result = $this->db->get_multiple_tables_records($sql);
		$this->_getExecutionTime($funcId,$sql); 
		return $result;			
	}
	function setCurrentTheme($theme_id)
	{
		$funcId = $this->_setFunctionHistory(__method__);
		$updatePtr = $this->db->set_multiple_fields('ec_users_themes', 'ACTIVE = "N"', 'USER_ID = "' . $_SESSION['curr_user_id'] . '"');
		$sql = "SELECT * 
			FROM ec_users_themes   
			WHERE THEME_ID = '" . $theme_id . "' 
			AND USER_ID = " . $_SESSION['curr_user_id'];
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
		if($chkSqlRows == 0)
		{
			$qry = "INSERT INTO ec_users_themes (`ID`, `USER_ID`, `THEME_ID`, `ACTIVE` )  VALUES 
			(NULL, " . $_SESSION['curr_user_id'] . ", " . $theme_id . ", 'Y')";
			$ptr = 	$this->db->get_sql_exec($qry);
		}
		else
		{
			$updatePtr = $this->db->set_multiple_fields('ec_users_themes', 'ACTIVE = "Y"', 'USER_ID = "' . $_SESSION['curr_user_id'] . '" AND THEME_ID = "' . $theme_id . '"');
		}
		$this->_getExecutionTime($funcId); 
	}	
	/* THEME CSS FROM DATABASE -- END */
	
	
	function _get_draw_value ()
	{
		$sql = "SELECT * 
		FROM draw_members  
		ORDER BY id ASC";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);		
		$drawMember = $this->db->get_multiple_tables_records($sql);		
		$currMemId = rand(1,3);
		$round = rand(79999,9999999);	
		$currMemPrize = rand(1,100);
		for($i = 1; $i <= count($chkSqlRows); $i++)
		{
			foreach($drawMember as $rec)
			{
				if($rec['id'] == $currMemId)
				{
					$winnerArr =  array($rec['id'],$rec['name'],$rec['img'],$currMemPrize,$round);break;
				}				
			}

		}
		return $winnerArr;
	}
	function _get_show_prize($id,$prize,$round)
	{
		if($id > 0 && $prize > 0 && $round > 0)
		{
			$currDate = date('Y-m-d h:i:s');
			$sql = "SELECT *   
				FROM draw_prize  
				WHERE mem_id = " . $id . " AND prize = " . $prize . " AND round = " . $round;
			$chkSqlPtr = $this->db->get_sql_exec($sql);
			$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);	
			if($chkSqlRows == 0)
			{				
				$qry = "INSERT INTO draw_prize (`id`, `mem_id`, `prize`, `prize_date`, `round` )  VALUES 
							(NULL, " . $id . ", " . $prize . ", '" . $currDate . "', '" . $round . "')";
				$ptr = 	$this->db->get_sql_exec($qry);	
			}			
		}
		$prizeList = array();
		$sql = "SELECT sum(p.prize) as prize,m.name as name  
			FROM draw_members AS m, draw_prize AS p 
			WHERE m.id = p.mem_id group by m.id order by prize desc";
		$chkSqlPtr = $this->db->get_sql_exec($sql);
		$chkSqlRows = $this->db->get_db_num_rows($chkSqlPtr);	
		if($chkSqlRows > 0)
		$prizeList = $this->db->get_multiple_tables_records($sql);	
		return $prizeList;
	}
	/****************************DEBUG FUNCTION Start Here**************************/
    function _setFunctionHistory($method = "") 
	{
 		/*if(isset($_SESSION['curr_user_token']))
		{*/
			$this->timer->start();
			$this->startExecutionTime = $this->timer->get();
			$funcId = $this->_addFunctionHistoryToDebugTable ($method,$this->startExecutionTime);
			$this->timer->start();
			return $funcId;
		/*}*/
    }
    function _getExecutionTime($funcId,$query='',$result='') 
	{
		$this->endExecutionTime = $this->timer->get();
		$this->_updateFunctionHistoryToDebugTable($funcId,$this->endExecutionTime,$query,$result);
    }
	function _emptyDebugTable($maxId=1000)
	{
		$sql = "SELECT max(ID) as maxID FROM `ec_debug`";
		$maxResult = $this->db->get_one_record($sql);
		if($maxResult['maxID'] > $maxId)
		{
			$qry = "TRUNCATE TABLE ec_debug";
			$ptr = 	$this->db->get_sql_exec($qry);			
		}else{
			$qry = "DELETE FROM ec_debug";
			if(isset($_SESSION['curr_user_id']))
				$qry .= " WHERE USER_ID = " . $_SESSION['curr_user_id'];
			$ptr = 	$this->db->get_sql_exec($qry);
		}
	}
	function _addFunctionHistoryToDebugTable ($method,$startTime)
	{
		if(isset($_SESSION['curr_user_id']))
		{
			$qry = "INSERT INTO ec_debug (`ID`, `FUNC_NAME`, `START_TIME`,`USER_ID` )  VALUES 
				(NULL, '" . $method . "', " . $startTime . ", '" . $_SESSION['curr_user_id'] . "')";//print $qry;die;
			$ptr = 	$this->db->get_sql_exec($qry);
			return $this->db->get_last_insert_id();
		}else{
			return '0';
		}
	}
	function _updateFunctionHistoryToDebugTable($funcId,$endTime,$query='',$result='')
	{
		//if(isset($_SESSION['curr_user_id']))
		//{
			$result = "'" . serialize($result) . "'";
			$updatePtr = $this->db->set_multiple_fields('ec_debug', 'END_TIME = "' . $endTime . '", QUERY_STR = "' . $query . '", QUERY_RESULT = ' . $result, 'ID = "' . $funcId . '"');
			//print $funcId . '=====>' . $updatePtr . '<br>';
		//}
	}
	function _getFunctionHistoryLog()
	{
		if(isset($_SESSION['curr_user_id']))
		{
			$sql = "SELECT * FROM `ec_debug` WHERE USER_ID = " . $_SESSION['curr_user_id'] . " ORDER BY ID ASC";
			$result = $this->db->get_multiple_tables_records($sql);
			return $result;
		}else{
			return '';
		}
	}
    function _debugScriptForErrors($die = 0, $showQuery = "Y", $showResult = "Y") 
	{//print '<pre>';print_r($_SERVER['REQUEST_URI']);die;
		$optionLinksArray = array(
		'Show Functions' => $_SERVER['REQUEST_URI'],
		'Show Functions & Query' => $_SERVER['REQUEST_URI'],
		'Show Functions, Query & Result' => $_SERVER['REQUEST_URI'],
		);       
		$t_exe_time = 0;
		$temp = '';
		$temp .=_getSectionHeader('Function Trace:', 'HTML TEST' . _getOptionLinkHtml($optionLinksArray));
		$temp .= "<div class='rTable'>";
			$temp .= "<div class='rTableRow rTableHeading'>";
				$temp .= "<div class='rTableCell'>SL</div>";
				$temp .= "<div class='rTableCell'>Function</div>";
				$temp .= "<div class='rTableCell'>Start</div>";
				$temp .= "<div class='rTableCell'>End</div>";
				$temp .= "<div class='rTableCell'>Execution Time</div>";
				//$temp .= "<div class='rTableCell'>Query</div>";
				//$temp .= "<div class='rTableCell'>Result</div>";
			$temp .= "</div>";
			$debugResult = $this->_getFunctionHistoryLog();
			if(!empty($debugResult)){//print '<pre>';print_r($debugResult);die;
				foreach ($debugResult as $key => $val){
					$exeutionTime = ($debugResult[$key]['END_TIME'] - $debugResult[$key]['START_TIME']);
					$t_exe_time += $exeutionTime;
				$temp .= "<div class='rTableRow rTableHeading'>";
				$temp .= "<div class='rTableCell'>" . $debugResult[$key]['sl_no'] . "</div>";
					$temp .= "<div class='rTableCell'>" . $debugResult[$key]['FUNC_NAME'] . "</div>";
					$temp .= "<div class='rTableCell'>" . $debugResult[$key]['START_TIME'] . "</div>";
					$temp .= "<div class='rTableCell'>" . $debugResult[$key]['END_TIME'] . "</div>";
					$temp .= "<div class='rTableCell'>" . number_format($exeutionTime,5) . " s</div>";
					//$temp .= "<div class='rTableCell'>" . $debugResult[$key]['QUERY_STR'] . "</div>";
					//$temp .= "<div class='rTableCell'>" . print_r(unserialize($debugResult[$key]['QUERY_RESULT']), true) . "</div>";
				$temp .= "</div>";
				}
			}
		$temp .= "</div>";
		$temp .= _getSectionFooter("TOTAL EXECUTION TIME IS " . number_format($t_exe_time,5) . " SECONDS.");
        if ($die == 1) {
            die;
        }
		return $temp;
    }
	/****************************DEBUG FUNCTION End Here**************************/	
}
?>