<?php
if (!isset($_SESSION)) { 
  session_start();
}
class adminPanelSqlModel extends CI_Model
{
	public function __construct() {
    }
	function getDashboardData()
	{
		return $this->sqlModel->getDashboardData();
	}
	function getListingData($what,$id){
		
		switch ($what) {
			case "items":
				$sqlPrefix = "SELECT i.ID, i.EXP_ITEM_NAME as Items, c.EXP_ITEM_CAT as Category, a.EXP_ACCOUNT as Account, i.DEFAULT_DEPO_AMT as DefDepo, i.ACTIVE, i.EXPIRED, i.FIXED_PAYEE, i.PAY_DAY";
				$sqlEdit = ",c.ID as cat_id, a.ID as acct_id";
				$sqlSuffix = " FROM ec_items as i,ec_items_cat as c, ec_accounts as a 
				WHERE i.EXP_ITEM_CAT_ID = c.ID 
				AND i.EXP_ACCOUNT_ID = a.ID AND i.USER_ID = " . $_SESSION['curr_user_id'];
				$sql = $sqlPrefix.$sqlSuffix;
				if($id > 0){$sql = $sqlPrefix.$sqlEdit.$sqlSuffix . " AND i.ID = " . $id;}
				break;
			case "category":
				$sql = "SELECT c.ID,c.EXP_ITEM_CAT 
				FROM ec_items_cat as c WHERE c.USER_ID = " . $_SESSION['curr_user_id'];
				if($id > 0){$sql .= " AND c.ID = " . $id;}
				break;
			case "accounts":
				$sql = "SELECT a.ID, a.EXP_ACCOUNT, a.PRIMARY_BAL, a.CURRENT_BAL, a.COLOR_CODE_ID,c.BG_COLOR,c.FONT_COLOR 
				FROM ec_accounts as a,ec_color_code as c WHERE a.COLOR_CODE_ID = c.ID AND c.USER_ID = " . $_SESSION['curr_user_id'] ." AND a.USER_ID = " . $_SESSION['curr_user_id'];
				if($id > 0){$sql .= " AND a.ID = " . $id;}
				break;
			case "users":
				if($_SESSION['curr_user_type'] == 'A'){
					$sql = "SELECT ID,USER_NAME,USER_EMAIL,USER_TOKEN,USER_LAST_LOGGED as LAST_LOGIN,USER_TYPE,ACTIVE FROM ec_users";
					if($id > 0){$sql .= " WHERE ID = " . $id;}
				}
				else{
					$result = "YOU ARE NOT AUTHORIZED TO ACCESS THIS LINK.";
				}
				break;
			case "colorcodes":
				$sql = "SELECT * FROM ec_color_code WHERE USER_ID = " . $_SESSION['curr_user_id'];
				if($id > 0){$sql .= " AND ID = " . $id;}
				break;
			default:
				$result = "Invalid Link Selected.";
		}
		if(isset($sql))
		{
			if($id > 0){
				$result = $this->db->get_multiple_tables_records($sql);
				$result = $result[0];
			}else{
				$result = $this->db->get_multiple_tables_records_with_fields_list($sql);	//print '<pre>';print_r($result);die;
			}
		}
		return $result;		
	}
	function postFormData($postData)
	{
		switch ($postData['what']) {
			case "items":
				$msg = $this->postItemsData($postData);
				break;
			case "category":
				$msg = $this->postCategoryData($postData);
				break;
			case "accounts":
				$msg = $this->postAccountsData($postData);
				break;
			case "users":
				$msg = $this->postUsersData($postData);
				break;
			case "colorcodes":
				$msg = $this->postColorCodeData($postData);
				break;
			case "userthemes":
				
				break;				
			default:
				$msg = "NO POST DATA FOUND";
		}
		return $msg;		
	}
	function postUsersData($postData)
	{
		if(isset($postData['user_submit'])){
			if(isset($postData['user_name']) && isset($postData['user_pass']) && trim($postData['user_name']) != '' && trim($postData['user_pass']) != '' &&  $postData['user_submit'] == 'Create User')
			{
				$msg = $this->sqlModel->createNewUser($_POST['user_name'],$_POST['user_pass'],$_POST['user_email']);
				
			}
			else{
				$msg = "Invalid data for User creation";
			}
		}else{
			$msg = "Invalid Form data for User creation";
		}
		return $msg;		
	}
	function postColorCodeData($postData)
	{
		$currDate = date('Y-m-d h:i:s');
		if(isset($postData['colorcode_submit']))
		{
			if($postData['colorcode_submit'] == 'Add Color Code')// Adding Color Code
			{
				if(isset($postData['bg_color']) && isset($postData['font_color']) && trim($postData['bg_color']) != '' && trim($postData['font_color']) != '')
				{
					$chkStr = "SELECT ID,BG_COLOR 
					FROM ec_color_code  
					WHERE BG_COLOR = '" . $postData['bg_color'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{
						$qry = "INSERT INTO ec_color_code (`ID`, `BG_COLOR`, `FONT_COLOR`, `LAST_UPDATED`,`USER_ID` )  VALUES 
						(NULL, '" . $postData['bg_color'] . "', '" . $postData['font_color'] . "', '" . $currDate . "', '" . $_SESSION['curr_user_id'] . "')";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "ColorCode is successfully added.";
					}
					else{
						$msg = "ColorCode is already Exists.";
					}
					
				}
				else{
					$msg = "Invalid data for ColorCode Creation";
				}
			}
			elseif($postData['colorcode_submit'] == 'Edit Color Code')// Modifying Color Code
			{
				if(isset($postData['bg_color']) && isset($postData['font_color']) && isset($postData['id']) && trim($postData['bg_color']) != '' && trim($postData['font_color']) != '' && $postData['id'] > 0)
				{
					$chkStr = "SELECT ID FROM ec_color_code  
					WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 1)
					{					
						$updatePtr = $this->db->set_multiple_fields('ec_color_code', 'BG_COLOR = "' . $postData['bg_color'] . '",FONT_COLOR = "' . $postData['font_color'] . '", LAST_UPDATED = "' . $currDate . '"', 'ID = "' . $postData['id'] . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
						$msg = "ColorCode modification is successfully done.";
					}
					else{
						$msg = "This ColorCode doesn't belongs to you. Action Failed!!";
					}
				}
				else{
					$msg = "Invalid data for ColorCode Modification";
				}				
			}
			elseif($postData['colorcode_submit'] == 'Delete Color Code')// Deleting Color Code
			{
				$chkStr = "SELECT ID FROM ec_color_code  
				WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
				$chkPtr = $this->db->get_sql_exec($chkStr);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows == 1)
				{				
					$chkStr = "SELECT ID FROM ec_accounts  
					WHERE COLOR_CODE_ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{				
						$qry = "DELETE FROM ec_color_code WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" . $_SESSION['curr_user_id'] . "'";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "ColorCode is successfully deleted.";
					}
					else{
						$msg = "This ColorCode can't be deleted as it is being used by Accounts. Action Failed.";
					}
				}
				else{
					$msg = "This ColorCode doesn't belongs to you. Action Failed!!";
				}					
			}
			else
			{
				$msg = "Invalid Submit button value for ColorCode Operation";
			}
		}else{
			$msg = "Invalid Form data for ColorCode Operation";
		}
		return $msg;		
	}
	function postAccountsData($postData)
	{
		$currDate = date('Y-m-d h:i:s');
		if(isset($postData['account_submit']))
		{
			if($postData['account_submit'] == 'Add Account')// Adding Account
			{
				if(isset($postData['acct_name']) && isset($postData['curr_bal']) && isset($postData['pri_bal']) && trim($postData['acct_name']) != '' && trim($postData['curr_bal']) != '' && trim($postData['pri_bal']) != '')
				{
					$chkStr = "SELECT ID,EXP_ACCOUNT 
					FROM ec_accounts  
					WHERE lower(EXP_ACCOUNT) = '" . strtolower(trim($postData['acct_name'])) . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{
						$qry = "INSERT INTO ec_accounts (`ID`, `EXP_ACCOUNT`, `PRIMARY_BAL`, `CURRENT_BAL`,`COLOR_CODE_ID`,`LAST_UPDATED`,`USER_ID` )  VALUES 
						(NULL, '" . $postData['acct_name'] . "', '" . $postData['pri_bal'] . "', '" . $postData['curr_bal'] . "','" . $postData['colorCode'] . "', '" . $currDate . "', '" . $_SESSION['curr_user_id'] . "')";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "Account is successfully added.";
					}
					else{
						$msg = "Account is already Exists.";
					}
					
				}
				else{
					$msg = "Invalid data for Account Creation";
				}
			}
			elseif($postData['account_submit'] == 'Edit Account')// Modifying Account
			{
				if(isset($postData['acct_name']) && isset($postData['curr_bal']) && isset($postData['pri_bal']) && trim($postData['acct_name']) != '' && trim($postData['curr_bal']) != '' && trim($postData['pri_bal']) != '' && $postData['id'] > 0)
				{
					$chkStr = "SELECT ID FROM ec_accounts  
					WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 1)
					{					
						$updatePtr = $this->db->set_multiple_fields('ec_accounts', 'EXP_ACCOUNT = "' . $postData['acct_name'] . '",PRIMARY_BAL = "' . $postData['pri_bal'] . '",CURRENT_BAL = "' . $postData['curr_bal'] . '",COLOR_CODE_ID = "' . $postData['colorCode'] . '", LAST_UPDATED = "' . $currDate . '"', 'ID = "' . $postData['id'] . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
						$msg = "Account modification is successfully done.";

					}
					else{
						$msg = "This Account doesn't belongs to you. Action Failed!!";
					}
				}
				else{
					$msg = "Invalid data for Account Modification";
				}				
			}
			elseif($postData['account_submit'] == 'Delete Account')// Deleting Account
			{
				$chkStr = "SELECT ID FROM ec_accounts  
				WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
				$chkPtr = $this->db->get_sql_exec($chkStr);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows == 1)
				{				
					$chkStr = "SELECT ID FROM ec_items   
					WHERE EXP_ACCOUNT_ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{				
						$qry = "DELETE FROM ec_accounts WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" . $_SESSION['curr_user_id'] . "'";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "Account is successfully deleted.";
					}
					else{
						$msg = "This Account can't be deleted as it is being used by other Items. Action Failed.";
					}
				}
				else{
					$msg = "This Account doesn't belongs to you. Action Failed!!";
				}					
			}
			else
			{
				$msg = "Invalid Submit button value for Account Operation";
			}
		}else{
			$msg = "Invalid Form data for Account Operation";
		}
		return $msg;		
	}
	function postCategoryData($postData)
	{
		$currDate = date('Y-m-d h:i:s');
		if(isset($postData['category_submit']))
		{
			if($postData['category_submit'] == 'Add Category')// Adding Category
			{
				if(isset($postData['cat_name'])&& trim($postData['cat_name']) != '')
				{
					$chkStr = "SELECT ID,EXP_ITEM_CAT 
					FROM ec_items_cat  
					WHERE lower(EXP_ITEM_CAT) = '" . strtolower(trim($postData['cat_name'])) . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{
						$qry = "INSERT INTO ec_items_cat (`ID`, `EXP_ITEM_CAT`, `USER_ID` )  VALUES 
						(NULL, '" . $postData['cat_name'] . "', '" . $_SESSION['curr_user_id'] . "')";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "Category is successfully added.";
					}
					else{
						$msg = "Category is already Exists.";
					}
					
				}
				else{
					$msg = "Invalid data for Category Creation";
				}
			}
			elseif($postData['category_submit'] == 'Edit Category')// Modifying Category
			{
				if(isset($postData['cat_name'])&& trim($postData['cat_name']) != '' && $postData['id'] > 0)
				{
					$chkStr = "SELECT ID FROM ec_items_cat  
					WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 1)
					{					
						$updatePtr = $this->db->set_multiple_fields('ec_items_cat', 'EXP_ITEM_CAT = "' . $postData['cat_name'] . '"', 'ID = "' . $postData['id'] . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
						$msg = "Category modification is successfully done.";

					}
					else{
						$msg = "This Category doesn't belongs to you. Action Failed!!";
					}
				}
				else{
					$msg = "Invalid data for Category Modification";
				}				
			}
			elseif($postData['category_submit'] == 'Delete Category')// Deleting Category
			{
				$chkStr = "SELECT ID FROM ec_items_cat  
				WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
				$chkPtr = $this->db->get_sql_exec($chkStr);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows == 1)
				{				
					$chkStr = "SELECT ID FROM ec_items   
					WHERE EXP_ITEM_CAT_ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{				
						$qry = "DELETE FROM ec_items_cat WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" . $_SESSION['curr_user_id'] . "'";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "Category is successfully deleted.";
					}
					else{
						$msg = "This Category can't be deleted as it is being used by other Items. Action Failed.";
					}
				}
				else{
					$msg = "This Category doesn't belongs to you. Action Failed!!";
				}					
			}
			else
			{
				$msg = "Invalid Submit button value for Category Operation";
			}
		}else{
			$msg = "Invalid Form data for Category Operation";
		}
		return $msg;		
	}
	function postItemsData($postData)
	{
		$currDate = date('Y-m-d h:i:s');
		if(isset($postData['item_submit']))
		{
			if($postData['item_submit'] == 'Add Item')// Adding Item
			{
				if(isset($postData['item_name']) && isset($postData['def_depo']) && trim($postData['item_name']) != '' && trim($postData['def_depo']) != '')
				{
					$chkStr = "SELECT ID,EXP_ITEM_NAME 
					FROM ec_items  
					WHERE lower(EXP_ITEM_NAME) = '" . strtolower(trim($postData['item_name'])) . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 0)
					{
						$qry = "INSERT INTO ec_items (`ID`, `EXP_ITEM_NAME`, `EXP_ITEM_CAT_ID`, `EXP_ACCOUNT_ID`,`DEFAULT_DEPO_AMT`,`ACTIVE`,`EXPIRED`,`FIXED_PAYEE`,`PAY_DAY`,`USER_ID` ) VALUES 
						(NULL, '" . $postData['item_name'] . "', '" . $postData['cat_id'] . "','" . $postData['acct_id'] . "','" . $postData['def_depo'] . "','" . $postData['active'] . "', '" . $postData['expired'] . "','" . $postData['fixed_payee'] . "', '" . $postData['payday'] . "', '" . $_SESSION['curr_user_id'] . "')";
						$ptr = 	$this->db->get_sql_exec($qry);
						$msg = "Item is successfully added.";
					}
					else{
						$msg = "Item is already Exists.";
					}
					
				}
				else{
					$msg = "Invalid data for Item Creation";
				}
			}
			elseif($postData['item_submit'] == 'Edit Item')// Modifying Item
			{
				if(isset($postData['item_name']) && isset($postData['def_depo']) && trim($postData['item_name']) != '' && trim($postData['def_depo']) != '')
				{
					$chkStr = "SELECT ID FROM ec_items  
					WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
					$chkPtr = $this->db->get_sql_exec($chkStr);
					$chkRows = $this->db->get_db_num_rows($chkPtr);
					if($chkRows == 1)
					{					
						$updatePtr = $this->db->set_multiple_fields('ec_items', 'EXP_ITEM_NAME = "' . $postData['item_name'] . '",EXP_ITEM_CAT_ID = "' . $postData['cat_id'] . '",EXP_ACCOUNT_ID = "' . $postData['acct_id'] . '",DEFAULT_DEPO_AMT = "' . $postData['def_depo'] . '",ACTIVE = "' . $postData['active'] . '",EXPIRED = "' . $postData['expired'] . '",FIXED_PAYEE = "' . $postData['fixed_payee'] . '", PAY_DAY = "' . $postData['payday'] . '"', 'ID = "' . $postData['id'] . '" AND USER_ID = "' . $_SESSION['curr_user_id'] . '"');
						$msg = "Item modification is successfully done.";

					}
					else{
						$msg = "This Item doesn't belongs to you. Action Failed!!";
					}
				}
				else{
					$msg = "Invalid data for Item Modification";
				}				
			}
			elseif($postData['item_submit'] == 'Delete Item')// Deleting Item
			{
				$chkStr = "SELECT ID FROM ec_items  
				WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" .  $_SESSION['curr_user_id'] . "'"; 
				$chkPtr = $this->db->get_sql_exec($chkStr);
				$chkRows = $this->db->get_db_num_rows($chkPtr);
				if($chkRows == 1)
				{				
					$qry = "DELETE FROM ec_deposit WHERE DEPO_ITEM_ID = '" . $postData['id'] . "' AND USER_ID = '" . $_SESSION['curr_user_id'] . "'";
					$ptr = 	$this->db->get_sql_exec($qry);
					$qry = "DELETE FROM ec_expenditure WHERE EXP_ITEM_ID = '" . $postData['id'] . "' AND USER_ID = '" . $_SESSION['curr_user_id'] . "'";
					$ptr = 	$this->db->get_sql_exec($qry);
					$qry = "DELETE FROM ec_items WHERE ID = '" . $postData['id'] . "' AND USER_ID = '" . $_SESSION['curr_user_id'] . "'";
					$ptr = 	$this->db->get_sql_exec($qry);
					$msg = "Item is successfully deleted.";
				}
				else{
					$msg = "This Item doesn't belongs to you. Action Failed!!";
				}					
			}
			else
			{
				$msg = "Invalid Submit button value for Item Operation";
			}
		}else{
			$msg = "Invalid Form data for Item Operation";
		}
		return $msg;		
	}

	function getColorCodeList()
	{
		$sql = "select ID,CONCAT('style=\"background-color:',BG_COLOR,';padding:5px;width:280px;line-height:30px;color:', FONT_COLOR, ';\"') as selectItem,CONCAT('Backgound: ',BG_COLOR,' || Font Color: ', FONT_COLOR) as selectItemText from  ec_color_code WHERE USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_multiple_tables_records($sql);
		//print '<pre>';print_R($result);
		return $result;		
	}
	function getCategoryList()
	{
		$sql = "select ID,EXP_ITEM_CAT as selectItem from  ec_items_cat WHERE USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_multiple_tables_records($sql);	
		return $result;		
	}
	function getAccountList()
	{
		$sql = "select ID,EXP_ACCOUNT as selectItem from ec_accounts WHERE USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_multiple_tables_records($sql);	
		return $result;		
	}
	function getAccountData(){
		$sql = "select * from  ec_accounts WHERE USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_multiple_tables_records($sql);	
		return $result;		
	}
	function getItemData()
	{
		$sql = "select i.*, c.EXP_ITEM_CAT,a.EXP_ACCOUNT  
		FROM ec_items as i,ec_items_cat as c,  ec_accounts as a 
		WHERE i.EXP_ITEM_CAT_ID = c.ID 
		AND i.EXP_ACCOUNT_ID = a.ID 		
		AND i.USER_ID = " . $_SESSION['curr_user_id'];
		$result = $this->db->get_multiple_tables_records($sql);	
		return $result;	
	}	
}
?>	