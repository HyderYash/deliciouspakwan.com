<div id="outerDiv" style="float:left;width:100%;">
	<div id="headdiv" style="float:left;width:100%;">
		<?php
		for($t = 1; $t <= 10;$t++){
			echo busi_get_ticketBox($t,$ticketBoxData,'showOuterDiv');
		}
		?>
	</div>
	<div id="bodyDiv" style="float:left;width:100%;">
		<div id="" style="float:left;margin:0px;padding:5px;width:9.25%;">
			<?php
			for($l = 36; $l >= 29;$l--){
				echo busi_get_ticketBox($l,$ticketBoxData);
			}
			?>
		</div>
		<div id="" style="float:left;margin:0px;padding:0px;width:79.5%;border:0px solid green;">
		<!-- POPUP Start -->
		<!-- Outer table starts -->
			<div id="ticketPopupDiv" style="display:none;margin-top:20px;border:0px solid blue;border-radius:10px;width:100%;justify-content:center;align-items:center;position:relative;">
				<div style="border:10px solid #45f7e9;border-radius:50px;width:80%;margin:0px auto;background-color:#45f7e9;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;min-height: 500px;">
					<!-- First row Table starts -->
					<table width="100%" cellpadding="0px" cellspacing="0px" style="font-family:arial;">
						<tr>
							<td align="center">
								<h1>Your Dice roll is 5</h1>
							</td>
						</tr>
					</table>
					<!-- First row Ends -->
					<!-- Second Row Table Starts -->
					<table width="95%" style="border: 4px solid #aa8dad;" cellpadding="20px" cellspacing="0px" align="center">
						<tr>
							<td width="25%" style="vertical-align:top;">
								<table border="0" width="100%" cellpadding="0px" cellspacing="0px">
									<tr>
										<td style="font-family:arial;font-weight:700;font-size:35px;" align="center">
											Arrived on Ticket
										</td>
									</tr>
									<tr>
										<td align="center" style="padding:20px;">
											<div style="margin:0px auto;padding:5px;width:200px;">
												<div id="4" style="float:left;width:98%;border:4px solid #000000;margin-top:0px;border-radius:10px;">
													<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:7px;padding-bottom:7px;font-size:16px;font-family:arial;font-weight:700;color:#fff;background-color:#000000;">Railways</div>
													<div align="center" style="text-align:center;width:100%;padding:0px;margin:0px;background-color:#eeeeee;">
														<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:#f536cb;">No House</div>
														<div id="plPos4" align="center" style="text-align:center;min-height:100px;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#000;background-color:#eeeeee;vertical-align:middle;"></div>
														<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:green">Available</div>
													</div>
													<div align="center" style="text-align:center;width:100%%;margin:0px;padding:0px;padding-top:5px;padding-bottom:5px;font-size:16px;font-family:arial;font-weight:700;color:#fff;background-color:#000000;">$ 9500</div>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</td>
							<td width="75%" align="center" style="border-left:4px solid #aa8dad;vertical-align:top;">
								<table border="0" width="100%" cellpadding="0px" cellspacing="0px">
									<tr>
										<td style="font-family:arial;font-weight:700;font-size:35px;padding-bottom:20px;" align="center">
											Your Current balance is $500
										</td>
									</tr>
									<tr>
										<td style="font-family:arial;font-weight:700;font-size:35px;">
											You have to pay $1000 You have to pay $1000 You have to pay $1000
										</td>
									</tr>
									<tr>
										<td>
											<table border="0" width="98%" cellpadding="5px" cellspacing="0px" align="center">
												<tr>
													<td style="float:left;vertical-align:middle;padding-top: 12px;">
														<div class="box">
															<select style="width:382px;">
																<option>Select your Ticket to sell</option>
																<option value="1">Operating System</option>
																<option value="2">Computer Networks</option>
																<option value="3">Data Structure</option>
																<option value="4">Algorithm</option>
																<option value="5">C programming</option>
																<option value="6">JAVA</option>
															</select>
														</div>
													</td>
													<td style="width:20%;">
														<div class="button" style="font-family:arial;width: 150px;background-color:#fc4828;padding-top:10px;padding-bottom:10px;background-color:purple">Sell</div>
													</td>
												</tr>
												<tr>
													<td style="width:20%;" align="center" colspan="2">
														<div class="button" style="font-family:arial;width: 150px;background-color:#fc4828;padding-top:10px;padding-bottom:10px;background-color:#fc4828;">Quit Game</div>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" style="padding-top:20px;">
											<a href="">
												<div class="button" style="font-family:arial;width: 150px;background-color:#fc4828;padding-top:10px;padding-bottom:10px;background-color:#4CAF50;">Continue</div>
											</a>
											<a href="">
												<div class="button" style="font-family:arial;width: 150px;background-color:#fc4828;padding-top:10px;padding-bottom:10px;">Cancel</div>
											</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>

					</table>
					<!-- Second Row Table Ends -->
					<!-- Third row Table starts -->
					<table width="100%" cellpadding="0px" cellspacing="0px" style="font-family:arial;">
						<tr>
							<td align="center">
								<h1>Your Dice roll is 5</h1>
							</td>
						</tr>
					</table>
					<!-- Third row Ends -->				
				</div>
			</div>
			<!-- Outer table ends -->		
			<div style="clear:both;"></div>
			<!-- POPUP ends -->
			<div id="playingAreaDiv" style="display:block;float:left;margin:10px;padding:0px;width:98.3%;border:2px solid blue;border-radius:12px;min-height:500px;background-color:#EBDEF0;">
				<!-- First row Table starts -->
				<table width="100%" cellpadding="5px" cellspacing="0px" style="font-family:arial;border-bottom:2px solid blue;">
					<tr>
						<td align="center"><h1>You are playing game [ <?=$gameName?> ]</h1></td>
					</tr>
				</table>
				<!-- First row Ends -->
				<form name="playerPos" method="post" action="/startBusiness">
				<!-- Second row Table -->
				<table width="100%" cellpadding="0px" cellspacing="0px" class="btn-group" style="border-bottom:2px solid blue;" >
					<tr>
						
						<td style="border-right:2px solid blue;width:30%;">
							<table width="100%" cellpadding="10px" cellspacing="0px" class="btn-group">
								<tr>
									<td><a href="/resetGame"><div class="button" style="width: 150px;background-color:#fc4828;padding-top:10px;padding-bottom:10px;">Reset this Game</div></a></td>
									<td><a href="/deleteGame"><div class="button" style="width: 150px;background-color:#fc4828;padding-top:10px;padding-bottom:10px;">Delete this Game</div></a></td>
								</tr>
								<tr>
									<td><a href="/logout"><div class="button" style="background-color:purple;width: 150px;padding-top:10px;padding-bottom:10px;">Create New Game</div></a></td>
									<td><a href="/logout"><div class="button" style="width: 150px;padding-top:10px;padding-bottom:10px;">Exit this Game</div></td>
								</tr>
							</table>
						</td>
						<td style="border-right:2px solid blue;width:5%;">&nbsp;</td>
						<td style="width:64%;">
							<table border="0" width="100%" cellpadding="5px" cellspacing="0px" align="right">
								<tr>
									<td style="width:69%;">
										<div class="box" style="float:left;">
										  <select style="width:250px;" name="playerId">
											<option value="">Select Player</option>
											<?php
											foreach ($playerListData as $plData){

												echo '<option value="' . $plData['ID'] . '">' . $plData['player_name']  . '</option>';
											}
											?>
										  </select>
										</div>
										<div class="box" style="float:left;margin-left:20px;">
										  <select style="width:150px;" name="posNumber">
											<option value="">Dice</option>
											<?php
											for($i=1; $i < 36; $i++)
											{
												echo '<option value="' . $i . '">' . $i . '</option>';
											}
											?>

										  </select>
										</div>
									</td>
									<td style="width:20%;"><button type="submit" name="MovePlayerToNewPosition" class="button" style="width: 150px;padding-top:10px;padding-bottom:10px;" ><span>GO </span></button></td>
								</tr>
								<tr>
									<td style="float:left;width:79%;">
										<div class="box"> 
											<select style="width:400px;" name="newPlayerId"> 
												<option>Add New Player to this Game</option> 
												<?php
												foreach ($additonalPlayerListData as $adplData){

													echo '<option value="' . $adplData['ID'] . '">' . $adplData['player_name']  . '</option>';
												}
												?>
											</select> 
										</div>
									</td>
									<td style="width:20%;">
									<button type="submit" name="AddNewPlayerToGame" class="button" style="width: 150px;padding-top:10px;padding-bottom:10px;background-color:purple;"><span>Add </span></button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<!-- Second Row Table -->
				</form>
				<!-- Third Row Table -->
				<table border="0" style="font-family:arial;" width="100%" cellpadding="0px" cellspacing="0px">
					<tr>
						<td align="center"><button class="button" style="padding-top:10px;padding-bottom:10px;width: 300px;background-color:green;">Total Tickets: 52</button></td>
						<td align="center"><button class="button" style="padding-top:10px;padding-bottom:10px;width: 300px;background-color:red;">Sold Tickets: 32</button></td>
						<td align="center"><button class="button" style="padding-top:10px;padding-bottom:10px;width: 300px;background-color:purple;">Avail. Tickets: 20</button></td>
					</tr>
				</table>
				<!-- Third Row Table -->
				<!-- Account Details Start -->
				<div style="width:100%;border:0px solid green;padding:0px;margin:0px;margin-top:10px;">
					<table style="width:100%;" cellpadding="0px" cellspacing="4px">
						<tr>
						<?php
						$counter = 1;
						foreach($accountListData as $acctData){
						if($counter > 3){
							$counter = 1;
							echo '</tr><tr>';
						}
						?>
							<td width="33%" style="vertical-align:top;">
								<!-- Player Table Starts -->
								<table border="0" width="100%" cellpadding="0px" cellspacing="0px" style="border:4px solid <?=$acctData['playerColor']?>;">
										<tr style="background-color:<?=$acctData['playerColor']?>">
											<td colspan="2" align="center" style="font-size:50px;font-family:calibri;"><b><?=$acctData['playerName']?></td>				
										</tr>
										<tr>
											<td colspan="2" align="center" style="background-color:#1ad61a;font-weight:700;font-family:arial;font-size:36px;"><?=$acctData['playerCurrentBalAmt']?></td>							
										</tr>
										<tr>
											<td colspan="2" align="center" style="font-family:arial;font-style:italic;font-weight:700;font-size:26px"><b><?=$acctData['gameInitialAmt']?></td>							
										</tr>
										<tr>
											<td colspan="2" align="center" style="font-family:arial;font-size:14px;font-weight:700;">
												<table border="1" width="100%" style="border:1px solid red;background-color:#b3e7ff">
													</tr>
														<td align="center">Amt</td>
														<td align="center">Type</td>
														<td align="center">Purpose</td>
														<td align="center">Against</td>
														<td align="center">Dice</td>
														<td align="center">Round</td>
													</tr>
													<?php
													foreach($acctData['transtionDetails'] as $tranData){
														if($tranData['purpose'] == 'Buy Ticket'){
															$rowColor = '#F3FF33';
														}else{
															$rowColor = '#b3e7ff';
														}
													?>													
													<tr style="background-color:<?=$rowColor?>">
														<td align="right" style="padding-right:2px;"><?=$tranData['transaction_amt']?></td>
														<td align="right" style="padding-right:2px;"><?=$tranData['transaction']?></td>
														<td align="right" style="padding-right:2px;"><?=$tranData['purpose']?></td>
														<td align="right" style="padding-right:2px;"><?=$tranData['tran_for_ticket_name']?></td>
														<td align="right" style="padding-right:2px;"><?=$tranData['dice_roll']?></td>
														<td align="right" style="padding-right:2px;"><?=$tranData['round_play']?></td>
													</tr>
													<?php
													}
													?>													
												</table>
											</td>							
										</tr>										
								</table>

								<!-- Player Table Ends-->
							</td>	
						<?php
						$counter++;
						}
						?>
						</tr>		
					</table>				
				</div>
				<!-- Account Details end -->
			</div>
		</div>		
		<div id="" style="float:left;margin:0px;padding:5px;width:9.25%;border:0px solid green;">
			<?php
			for($r = 11; $r <= 18;$r++){
				echo busi_get_ticketBox($r,$ticketBoxData);
			}
			?>		
		</div>		
	</div>
	<div id="footdiv" style="float:left;width:100%;">
		<?php
		for($f = 28; $f >= 19;$f--){
			echo busi_get_ticketBox($f,$ticketBoxData,'showOuterDiv');
		}
		?>
	</div>	
</div>