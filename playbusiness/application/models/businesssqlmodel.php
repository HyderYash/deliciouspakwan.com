<?php
if (!isset($_SESSION)) { 
  session_start();
}
class businesssqlmodel extends CI_Model
{
	public function __construct() {

    }
	function checkGameSession()
	{
		if(!isset($_SESSION['CURRENT_GAME_ID'])){
			$this->customRedirect('/');
		}		
	}
	function deleteCurrentGame(){
		$str = "DELETE FROM busi_running_game WHERE game_id = " . $_SESSION['CURRENT_GAME_ID'];
		$ptr = $this->db->get_sql_exec($str);
		
		$str = "DELETE FROM busi_games WHERE ID = " . $_SESSION['CURRENT_GAME_ID'];
		$ptr = $this->db->get_sql_exec($str);
		unset($_SESSION['CURRENT_GAME_ID']);
		unset($_SESSION['ERROR_MSG']);		
		$this->customRedirect('/');
	}
	function createNewGame($postData){
		$currDate = date('Y-m-d h:i:s');
		$chkStr = "SELECT game_name     
			FROM busi_games 
			WHERE lower(game_name) = '" . strtolower($postData['newGame']) . "'";
		$chkPtr = $this->db->get_sql_exec($chkStr);
		$chkRows = $this->db->get_db_num_rows($chkPtr);
		if($chkRows == 0){
			$qry = "INSERT INTO busi_games (`ID`, `game_name`,`game_intial_amt`,`game_active`)  VALUES 
			(NULL, '" . $postData['newGame'] . "', " . $postData['intialAmt'] . ", 'Y')";
			$ptr = 	$this->db->get_sql_exec($qry);
			$last_game_id = $this->db->get_last_insert_id();
			$_SESSION['CURRENT_GAME_ID'] = $last_game_id;
			foreach ($postData['newPlayers'] as $key => $val){
				$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
			(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $val . ", 1,  'None',  0,  'None',  '0',  'No',  'Yes',  1,  '0','0', '" . $currDate . "')";
				$ptr = 	$this->db->get_sql_exec($qry);				
			}
			$this->businessSqlModel->customRedirect('/startBusiness');
		}else{
			
			$_SESSION['ERROR_MSG'] = "This Game is already exist. Please try to create new Game.";
			$this->customRedirect('/');
		}		
	}
	function resetCurrentGame(){
		$currDate = date('Y-m-d h:i:s');
		$chkStr = "SELECT player_id     
			FROM busi_running_game  
			WHERE game_id = " . $_SESSION['CURRENT_GAME_ID'] . " 
			GROUP BY player_id ORDER BY player_id ASC";
		$chkResult = $this->db->get_multiple_tables_records($chkStr);
		
		$str = "DELETE FROM busi_running_game WHERE game_id = " . $_SESSION['CURRENT_GAME_ID'];
		$ptr = $this->db->get_sql_exec($str);
		
		foreach ($chkResult as $playerData){
			$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
			(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $playerData['player_id'] . ", 1,  'None',  0,  'None',  '0',  'No',  'Yes',  1,  '0','0', '" . $currDate . "')";
			$ptr = 	$this->db->get_sql_exec($qry);			
		}
		$this->businessSqlModel->customRedirect('/startBusiness');
	}	
	function addNewPlayerToGame($newPlayerId){
		$currDate = date('Y-m-d h:i:s');
		$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
			(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $newPlayerId . ", 1,  'None',  0,  'None',  '0',  'No',  'Yes',  1,  '0','0', '" . $currDate . "')";
		$ptr = 	$this->db->get_sql_exec($qry);
	}
	function getTicketBoxData()
	{
		$chkStr = "SELECT *    
			FROM busi_tickets"; 
		$chkResult = $this->db->get_multiple_tables_records($chkStr);
		return $chkResult;		
	}
	function getGameName()
	{
		$chkGameStr = "SELECT *    
			FROM busi_games 
			WHERE ID = ". $_SESSION['CURRENT_GAME_ID'];
		$chkGameResult = $this->db->get_one_record($chkGameStr);
		return 	$chkGameResult['game_name'];		
	}	
	function getGameListData($gameId=0)
	{
		$chkGameStr = "SELECT *    
			FROM busi_games";
		if($gameId > 0)	
		{
			$chkGameStr .= " WHERE ID = ". $gameId;
		}			
		$chkGameResult = $this->db->get_multiple_tables_records($chkGameStr);
		return 	$chkGameResult;		
	}
	//Using in html_helper
	function checkPlayerPosition($playerPosition){
		$tmpData = array();
		
		$chkPlayerStr = "SELECT p.ID, p.player_name, p.player_color, rg.ticket_id, rg.ticket_house_num   
			FROM busi_players as p, busi_running_game as rg 
			WHERE p.ID = rg.player_id 
			AND rg.game_id = " . $_SESSION['CURRENT_GAME_ID'] . "
			AND rg.active_row = 'Yes'"; 
			//print $chkPlayerStr; die;
		$chkPlayerResult = $this->db->get_multiple_tables_records($chkPlayerStr);		
			foreach ($chkPlayerResult as $playerData){
				if($playerPosition == $playerData['ticket_id']){
					$tmpData[] = array('player_name' => $playerData['player_name'],'player_color' => $playerData['player_color'],	'player_position' => $playerData['ticket_id'], 'player_round' => $playerData['ticket_house_num']);
				}
			}
		return 	$tmpData;		
	}
	function getTicketInfo($ticketId)
	{
		$chkTicketStr = "SELECT *    
			FROM busi_tickets WHERE ID = " . $ticketId; 
		$chkTicketResult = $this->db->get_one_record($chkTicketStr);
		return $chkTicketResult;
	}
	function getPlayerCurrentPosition($playerId){
		$chkPosStr = "SELECT ticket_id FROM busi_running_game 
		WHERE player_id = '" . $playerId . "' 
		AND active_row = 'Yes' 
		AND game_id = " . $_SESSION['CURRENT_GAME_ID'];
		$chkPosResult = $this->db->get_one_record($chkPosStr);
		return $chkPosResult;
	}
	function getTicketOwner($ticketId)
	{
		$chkTicketStr = "SELECT distinct p.player_name, t.ticket_price, t.ticket_type, rg.ticket_owner, rg.player_id   
			FROM busi_tickets as t, busi_running_game as rg, busi_players as p 
			WHERE t.ID = rg.ticket_id 
			AND p.ID = rg.player_id 
			AND rg.ticket_id =  " . $ticketId . " 
			AND rg.ticket_owner = 'Yes'			
			AND rg.game_id = " . $_SESSION['CURRENT_GAME_ID'];//die;
		$chkTicketPtr = $this->db->get_sql_exec($chkTicketStr);
		$chkTicketRows = $this->db->get_db_num_rows($chkTicketPtr);
		
		if($chkTicketRows == 1){			
			$chkTicketResult = $this->db->get_one_record($chkTicketStr);
			return $chkTicketResult['player_name'];
		}else{
			$ticketType = $this->getTicketInfo($ticketId);
			if($ticketType['ticket_type'] == 'Ticket'){
				return 'Available';
			}else{
				return '';
			}
			
		}

	}
	function isOwnThisTicket($playerId,$ticketId){
		$chkPlayerStr = "SELECT distinct player_id    
			FROM busi_running_game  
			WHERE ticket_id =  " . $ticketId . " 
			AND player_id = " . $playerId . " 
			AND	ticket_owner = 'Yes' 		
			AND game_id = " . $_SESSION['CURRENT_GAME_ID'];
		$chkPlayerPtr = $this->db->get_sql_exec($chkPlayerStr);
		$chkPlayerRows = $this->db->get_db_num_rows($chkPlayerPtr);
		
		if($chkPlayerRows == 1){
			return 'Yes';
		}
		else{
			return 'No';
		}
	}	
	function getTicketPriceOrRentOrFine($ticketId,$playerId,$posNum)
	{
		$currDate = date('Y-m-d h:i:s');
		$returnArr = array();
		$updatePtr = $this->db->set_multiple_fields('busi_running_game', 'active_row = "No", last_updated = "' . $currDate . '"', 'player_id = "' . $playerId . '" AND game_id = "' . $_SESSION['CURRENT_GAME_ID'] . '"');
		$chkPlayerStr = "SELECT t.ID as ticket_id, t.ticket_price, t.ticket_type, rg.ticket_owner,rg.round_play, rg.player_id   
			FROM busi_tickets as t, busi_running_game as rg 
			WHERE t.ID = rg.ticket_id 
			AND rg.ticket_id =  " . $ticketId . " 
			AND rg.ticket_owner = 'Yes'	
			AND rg.transaction = 'Debit' 
			AND rg.purpose = 'Buy Ticket' 
			AND rg.tran_for_ticket =  " . $ticketId . " 
			AND rg.game_id = " . $_SESSION['CURRENT_GAME_ID'];//die;
		$chkPlayerPtr = $this->db->get_sql_exec($chkPlayerStr);
		$chkPlayerRows = $this->db->get_db_num_rows($chkPlayerPtr);
		
		if($chkPlayerRows == 1){
			$chkPlayerResult = $this->db->get_one_record($chkPlayerStr);
			
			if($chkPlayerResult['ticket_type'] == 'Ticket'){
				//If current player is owner
				if($playerId == $chkPlayerResult['player_id']){
					$returnArr['transaction'] = 'None';
					$returnArr['transaction_amt'] = 0;
					$returnArr['purpose'] = 'None';
					$returnArr['ticket_house_num'] = 0;// Need to check
					$returnArr['ticket_owner'] = 'Yes';
					$returnArr['active_row'] = 'Yes';
				}else{
					//Player has to pay rent
					$returnArr['transaction'] = 'Debit';
					$rentAmt = (($chkPlayerResult['ticket_price']) * (10/100));
					$returnArr['transaction_amt'] = $rentAmt;
					$returnArr['purpose'] = 'Rent Paid';
					$returnArr['ticket_house_num'] = 0;// Need to check
					$returnArr['ticket_owner'] = 'No';
					$returnArr['active_row'] = 'Yes';

					//Get Owner last position
					$owner_last_position = $this->getPlayerCurrentPosition($chkPlayerResult['player_id']);
					// If owner also owns the ticket in which he currently staying
					$chkOwnTicket = $this->isOwnThisTicket($chkPlayerResult['player_id'],$owner_last_position['ticket_id']);
					
					// Update all active rows eq to "No" for ticket Owner
					$updatePtr = $this->db->set_multiple_fields('busi_running_game', 'active_row = "No", last_updated = "' . $currDate . '"', 'player_id = "' . $chkPlayerResult['player_id'] . '" AND game_id = "' . $_SESSION['CURRENT_GAME_ID'] . '"');

					//Ticket Owner will get Credit the Rent amount in his account
					$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
					(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $chkPlayerResult['player_id'] . ", '" . $owner_last_position['ticket_id'] . "',  'Credit',  '" . $rentAmt . "',  'Got Rent',  '0',  '" . $chkOwnTicket . "',  'Yes',  '" . $chkPlayerResult['ticket_id'] . "',  '0',  '" . $chkPlayerResult['round_play'] . "','" . $currDate . "')";
					$ptr = 	$this->db->get_sql_exec($qry);					
				}
			}
		}			
		if($chkPlayerRows == 0)
		{
			//Player can purchase
			$ticket_info = $this->getTicketInfo($ticketId);
			if($ticket_info['ticket_type'] == 'Ticket'){
				$returnArr['transaction'] = 'Debit';
				$returnArr['transaction_amt'] = $ticket_info['ticket_price'];
				$returnArr['purpose'] = 'Buy Ticket';
				$returnArr['ticket_house_num'] = 0;// Need to check
				$returnArr['ticket_owner'] = 'Yes';
				$returnArr['active_row'] = 'Yes';				
			}else if($ticket_info['ticket_type'] == 'Tax'){
				$returnArr['transaction'] = 'Debit';
				$trnsctAmt = ($ticket_info['ticket_price'] * $posNum);
				$returnArr['transaction_amt'] = $trnsctAmt;
				$returnArr['purpose'] = 'Fine Paid';
				$returnArr['ticket_house_num'] = 0;// Need to check
				$returnArr['ticket_owner'] = 'No';
				$returnArr['active_row'] = 'Yes';				
			}else if($ticket_info['ticket_type'] == 'Fine'){
				$returnArr['transaction'] = 'Debit';
				$returnArr['transaction_amt'] = $ticket_info['ticket_price'];
				$returnArr['purpose'] = 'Fine Paid';
				$returnArr['ticket_house_num'] = 0;// Need to check
				$returnArr['ticket_owner'] = 'No';
				$returnArr['active_row'] = 'Yes';				
			}else if($ticket_info['ticket_type'] == 'Home'){
				$returnArr['transaction'] = 'None';
				$returnArr['transaction_amt'] = 0;
				$returnArr['purpose'] = 'None';
				$returnArr['ticket_house_num'] = 0;// Need to check
				$returnArr['ticket_owner'] = 'No';
				$returnArr['active_row'] = 'Yes';				
			}else if($ticket_info['ticket_type'] == 'Lottery'){
				$returnArr['transaction'] = 'Credit';
				$returnArr['transaction_amt'] = 1000;
				$returnArr['purpose'] = 'Prize';
				$returnArr['ticket_house_num'] = 0;// Need to check
				$returnArr['ticket_owner'] = 'No';
				$returnArr['active_row'] = 'Yes';				
			}			
		}		
		return $returnArr;
	}

	function updatePlayerPosition($playerId, $posNum)
	{
		$currDate = date('Y-m-d h:i:s');
		//Get player last position which store in ticket ID
		$chkPlayerStr = "SELECT ticket_id, round_play   
			FROM busi_running_game 
			WHERE player_id = " . $playerId . "  
			AND game_id = " . $_SESSION['CURRENT_GAME_ID'] . "
			AND active_row = 'Yes'"; 
		$chkPlayerResult = $this->db->get_one_record($chkPlayerStr);
		$player_last_positon = $chkPlayerResult['ticket_id'];
		//If player's last position was on 'Home' and he is now passing home to get away
		if($player_last_positon == 1 && $chkPlayerResult['round_play'] > 0){
			// Need to get Home Ticket amount dynamicaly later on . currently it is hard coded
			$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
			(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $playerId . ", 1,  'Credit',  2500,  'Prize',  0,  'No',  'No',  1,  '" . $posNum . "',  '" . $chkPlayerResult['round_play'] . "','" . $currDate . "')";
			$ptr = 	$this->db->get_sql_exec($qry);			
		}
			
		$player_reached_position = $player_last_positon + $posNum;
		$roundIncrease = 0;
		if($player_reached_position > 36){
			$roundIncrease = 1;
			//Getting Player's about to reach position
			$player_reached_position = ($player_reached_position - 36);			
			
			// Adding Home amount (2500) to Player account
			// Need to get Home Ticket amount dynamicaly later on . currently it is hard coded
			if($player_reached_position > 1){
				// Need to get Home Ticket amount dynamicaly later on . currently it is hard coded
				$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
				(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $playerId . ", 1,  'Credit',  2500,  'Prize',  0,  'No',  'No',  1,  '" . $posNum . "', '" . ($chkPlayerResult['round_play'] + $roundIncrease) . "','" . $currDate . "')";
				$ptr = 	$this->db->get_sql_exec($qry);
			}

		}		
		$ticketInfo = $this->getTicketPriceOrRentOrFine($player_reached_position,$playerId,$posNum);
		$qry = "INSERT INTO busi_running_game (`ID`, `game_id`,`player_id`,`ticket_id`,`transaction`,`transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`,`round_play`,`last_updated`)  VALUES 
			(NULL, " . $_SESSION['CURRENT_GAME_ID'] . ", " . $playerId . ", '" . $player_reached_position . "',  '" . $ticketInfo['transaction'] . "',  '" . $ticketInfo['transaction_amt'] . "',  '" . $ticketInfo['purpose'] . "',  '" . $ticketInfo['ticket_house_num'] . "',  '" . $ticketInfo['ticket_owner'] . "',  '" . $ticketInfo['active_row'] . "',  '" . $player_reached_position . "',  '" . $posNum . "', '" . ($chkPlayerResult['round_play'] + $roundIncrease) . "','" . $currDate . "')";
		$ptr = 	$this->db->get_sql_exec($qry);
	}	
	function getPlayerListData($game_id=0)
	{
		
		if($game_id > 0)
		{
			$chkPlayerStr = "SELECT distinct p.*, rg.player_id   
			FROM busi_running_game as rg, busi_players as p 
			WHERE p.ID = rg.player_id 
			AND rg.game_id = " . $game_id;
			
		}else{
			$chkPlayerStr = "SELECT *    
			FROM busi_players"; 
			
		}
		$chkPlayerResult = $this->db->get_multiple_tables_records($chkPlayerStr);
		return 	$chkPlayerResult;		
	}
	function getAdditionalPlayerListData($game_id=0)
	{
		if($game_id > 0)
		{
			$playerData = $this->getPlayerListData($game_id);
			$playerArray = '';
			foreach($playerData as $pData)
			{
				$playerArray .= $pData['ID'] . ',';
			}
			$playerArray = rtrim($playerArray, ',');
			$chkPlayerStr = "SELECT ID,player_name   
			FROM busi_players  
			WHERE ID NOT IN(" . $playerArray .")";
		}else{
			$chkPlayerStr = "SELECT *    
			FROM busi_players"; 
			
		}
		$chkPlayerResult = $this->db->get_multiple_tables_records($chkPlayerStr);
		return 	$chkPlayerResult;		
	}
	
	function getAccountListData($gameId){
		
		$chkPlayerStr = "SELECT distinct p.*, g.game_intial_amt,rg.player_id   
			FROM busi_running_game as rg, busi_players as p, busi_games as g 
			WHERE p.ID = rg.player_id 
			AND g.ID = rg.game_id  
			AND rg.game_id = " . $gameId;
		$chkPlayerResult = $this->db->get_multiple_tables_records($chkPlayerStr);
		$finalArray = array();
		foreach($chkPlayerResult as $pdata)
		{
			$finalArray[$pdata['player_id']]['playerName'] = $pdata['player_name'];
			$finalArray[$pdata['player_id']]['playerColor'] = $pdata['player_color'];
			$finalArray[$pdata['player_id']]['gameInitialAmt'] = $pdata['game_intial_amt'];
			$finalArray[$pdata['player_id']]['playerCurrentBalAmt'] = $this->getPlayerCurrentBalAmt($pdata['player_id'],$gameId);
			$finalArray[$pdata['player_id']]['transtionDetails'] = $this->getPlayerTransactions($pdata['player_id'],$gameId);
		}
		
		//print '<pre>';print_r($finalArray);die;
		return $finalArray;
	}
	function getPlayerCurrentBalAmt($playerId,$gameId){
		$initialAmt = $this->getGameInitialAmount($gameId);
		$credit = $this->getGameCreditAmount($playerId,$gameId);
		$debit = $this->getGameDebitAmount($playerId,$gameId);
		$playerCurrentBal = (($initialAmt + $credit) - $debit);
		return $playerCurrentBal;
	}
	function getGameCreditAmount($playerId,$gameId){
		$chkStr = "SELECT sum(transaction_amt) as transaction_amt  
			FROM busi_running_game  			
			WHERE game_id = '" . $gameId . "' 
			AND player_id = '" . $playerId . "' 
			AND transaction = 'Credit'";  
		$chkResult = $this->db->get_one_record($chkStr);
		return 	$chkResult['transaction_amt'];
	}
	function getGameDebitAmount($playerId,$gameId){
		$chkStr = "SELECT sum(transaction_amt) as transaction_amt  
			FROM busi_running_game  			
			WHERE game_id = '" . $gameId . "' 
			AND player_id = '" . $playerId . "' 
			AND transaction = 'Debit'";  
		$chkResult = $this->db->get_one_record($chkStr);
		return 	$chkResult['transaction_amt'];
	}
	function getGameInitialAmount($gameId){
		$chkStr = "SELECT game_intial_amt    
			FROM busi_games 			
			WHERE ID = " . $gameId;  
		$chkResult = $this->db->get_one_record($chkStr);
		return 	$chkResult['game_intial_amt'];	
	}
	function getPlayerTransactions($playerId,$gameId){
		$chkStr = "SELECT p.*, g.game_name,	g.game_intial_amt, rg.*   
			FROM busi_running_game as rg, busi_players as p, busi_games as g 
			WHERE p.ID = rg.player_id 
			AND g.ID = rg.game_id 
			AND p.ID = '" . $playerId . "' 
			AND rg.game_id = '" . $gameId . "' 
			ORDER BY rg.last_updated,rg.ID ASC";//die;
			$chkResult = $this->db->get_multiple_tables_records($chkStr);
			foreach($chkResult as $key => $val)
			{
				$ticektInfo = $this->getTicketInfo($chkResult[$key]['tran_for_ticket']);
				$chkResult[$key]['tran_for_ticket_name'] = $ticektInfo['ticket_name'];
			}
			return $chkResult;
	}
	
	function customRedirect($url, $statusCode = 303)
	{
		header('Location: ' . $url, true, $statusCode);
		die();
	}
}
?>