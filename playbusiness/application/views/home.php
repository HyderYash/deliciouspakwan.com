    <!-- Outer Div Starts -->
    <div class="busi_home_outer-div-css">
	<form action="/startBusiness" name="start_business" method="post">
        <div class="busi_home_main-div-container">
            <!-- Header Div Starts -->
            <div class="busi_home_header-container">
                <div class="busi_home_header-text">
                    <h1>Welcome To Business!</h1>
                </div>
            </div>
            <!-- Header Div Ends -->
            <div class="busi_home_new-game-container">
				<?php
				if(isset($error_msg) && $error_msg != ''){
					?>
					<div class="busi_home_error_msg" align="center">
						<?=$error_msg?>
					</div>
					<?php
				}
				?>			
                <!-- New Game Div Starts -->
                <div class="busi_home_float-left-16">
                    <div class="busi_home_new-game2">
                        <div class="busi_home_login_container" id="busi_home_login-container">
                            <label for="newGame" (required)><b>Enter New Game</b></label>
                            <input type="text" placeholder="Enter New Game" id="newGame" name="newGame">
                        </div>
                    </div>
                    <div class="busi_home_new-game2">
                        <div class="busi_home_login_container" id="busi_home_login-container">
                            <label for="intialAmt" (required)><b>Select Initial Amount</b></label>
                            <input type="text" placeholder="Select Initial Amount" id="intialAmt" name="intialAmt">
                        </div>
                    </div>
                </div>
                <!-- New Game Div Starts -->
                <!-- Select Player Div Starts -->
                <div class="busi_home_select-player-container">
                    <label for="newPlayers">Select Player:</label>
                    <select name="newPlayers[]" id="newPlayers" multiple class="busi_home_select-player-box">
						<?php
						foreach ($playerListData as $pData){
							echo '<option value="' . $pData['ID'] . '">' . $pData['player_name'] . '</option>'; 
						}
						?>
                    </select>
                </div>
                <!-- Select Player Div Ends -->
                <!-- Start New Game Button Div Starts -->
                <div class="busi_home_start-new-game-button">
                    <div><input class="busi_home_button busi_home_create-start-button" type="submit" name="createGameAndStartBusiness" value="Create & Start New Game"></div>
                </div>

                <!-- Start New Game Button Div Ends -->
            </div>
			<?php
			if(!empty($gameListData)){
			?>				
				<!-- OR Div Start -->
				<div class="busi_home_or-container-css">
					<div class="busi_home_or-subdiv-css">
						<span class="busi_home_or-span">OR</span>
					</div>
				</div>
				<!-- OR Div Ends -->
				<div class="busi_home_float-left-16 busi_home_border-css">
				
					<!-- Select  Game Div Starts -->
					<div class="busi_home_box busi_home_select-game">
						Select Game:
						<select id="selectedGameId" name="selectedGameId" class="busi_home_select-game2-css">
						<?php
						foreach ($gameListData as $gData){
							echo '<option value="' . $gData['ID'] . '">' . $gData['game_name'] . '</option>'; 
						}
						?>
						</select>
					</div>
					<!-- Select  Game Div Ends -->
					<!-- Start Game Div Ends -->
					<div class="busi_home_start-game-head">
						<div><input class="busi_home_button busi_home_start-game-head2" type="submit" name="selectGameAndStartBusiness" value="Start Game"></div>
					</div>
					<!-- Start  Game Div Ends -->
				</div>
			<?php
			}
			?>
			<!-- Footer Div Starts -->
			<div class="busi_home_footer_container">
				<div align="center"	class="busi_home_footer">
					Created by and Copyright &copy; to Ram Jee Krishna and Yash Sharma
				</div>
			</div>
			<!-- Footer Div Ends -->			
        </div>
	</form>
    </div>
    <!-- Outer Div Ends -->
