<?=_getSectionHeader('Showing.. Monthly Expenditure of ' . $_SESSION['curr_mon'] . '-' . $_SESSION['curr_yr'], 'Records Displaying Order By ' . strtoupper($_SESSION['monthlySortBy']) . ' And Sort By ' . strtoupper($_SESSION['monthlySortOrder']))?>
<form name="exp_mod_form" id="exp_mod_form" action="<?=$_SERVER['REQUEST_URI']?>?showMonth=<?=$_SESSION['curr_mon'] . '-' .  $_SESSION['curr_yr']?>" method="post">
	
	<select id="mo_year" name="mo_year" onchange="javascript:showThisMonthData(this.value);">
		<?php
		foreach ($monthlselect as $monyear => $monname){
			$year = $monyear;
			foreach ($monname as $monname){
				$month = $monname['month_number'];
				print '<option value="' . $month . '-' . $year . '"';
				if($month == $_SESSION['curr_mon'] && $year == $_SESSION['curr_yr']){
					print ' selected="true"';
				}
				print '>' . $monname['month_name'] . ' ' . $year . '</option>';
			}
		}
		?>
	</select>
	<div id="scrolly">
    	<div class="rTable">
    		<div class="rTableRow rTableHeading rowheadercss">
    			<div class="rTableHead" style="width:20px;">Sl</div>			
    			<div class="rTableHead"><a href="/showMonthlyStatusCurrent/?showMonth=<?=$_SESSION['curr_mon']?>-<?=$_SESSION['curr_yr']?>&monthlySortBy=item">Items</a></div>
    			<div class="rTableHead"><a href="/showMonthlyStatusCurrent/?showMonth=<?=$_SESSION['curr_mon']?>-<?=$_SESSION['curr_yr']?>&monthlySortBy=account">Account</a></div>
    			<div class="rTableHead"><a href="/showMonthlyStatusCurrent/?showMonth=<?=$_SESSION['curr_mon']?>-<?=$_SESSION['curr_yr']?>&monthlySortBy=category">Category</a></div>
    			<?php if(($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR)) { ?>
    				<div class="rTableHead"><a href="/showMonthlyStatusCurrent/?showMonth=<?=$_SESSION['curr_mon']?>-<?=$_SESSION['curr_yr']?>&monthlySortBy=payday">PayDay</a></div>
    				<div class="rTableHead" style="width:10px;">Action</div>
    			<?php } ?>
    			<div class="rTableHead"><a href="/showMonthlyStatusCurrent/?showMonth=<?=$_SESSION['curr_mon']?>-<?=$_SESSION['curr_yr']?>&monthlySortBy=allocate">Allocated</a></div>
    			<div class="rTableHead" style="width:10px;">Action</div>
    			<div class="rTableHead">Available</div>
    			<div class="rTableHead">Exp1</div>
    			<div class="rTableHead">Exp2</div>
    			<div class="rTableHead">Exp3</div>
    			<div class="rTableHead">Exp4</div>
    			<div class="rTableHead">Exp5</div>
    			<div class="rTableHead">Total</div>
    			<div class="rTableHead"><a href="/showMonthlyStatusCurrent/?showMonth=<?=$_SESSION['curr_mon']?>-<?=$_SESSION['curr_yr']?>&monthlySortBy=default">Action</a></div>
    			
    		</div>
    		<?php
    		$payDayItemStyle = "font-size:14px;font-weight:bold;color:red;";
    		$item_mon_year = '';
    		$m = 0;
    		$t_depo_amt = 0;
    		$t_available_amt = 0;
    		$t_exp_1 = 0;
    		$t_exp_2 = 0;
    		$t_exp_3 = 0;
    		$t_exp_4 = 0;
    		$t_exp_5 = 0;
    		$t_total_exp = 0;
    		foreach ($monthlyData as $mData){
    			$t_depo_amt += $mData['depo_amt'];
    			$t_available_amt += $mData['available_amt'];
    			$t_total_exp += $mData['total_exp'];
    			$item_mon_year = $mData['item_mon_year'];
    			$rowDisplay = 'y';
    			if(($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR)){
    				$rowDisplay = 'y';
    			}else{
    				if($mData['depo_amt'] == 0){
    					$rowDisplay = 'n';
    				}
    			}
    			if($rowDisplay == 'y'){
    				if($mData['pay_day'] == date('d') && ($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR)){
    					?>
    						<div class="rTableRow" style="background-color:<?=$mData['bg_color']?>;<?=$payDayItemStyle?>;">
    					<?php				
    				}else{
    					?>
    						<div class="rTableRow" style="background-color:<?=$mData['bg_color']?>;color:<?=$mData['font_color']?>;">
    					<?php
    				}
    				?>
    				<div class="rTableCell" align="right"><?=($m+1)?></div>
    				<div class="rTableCell">
    				<span style=" echo $payDayItemStyle;?>"><a href="/showExpForCategoryByItemsMonthly/<?=$mData['item_cat_id']?>/<?=$mData['item_id']?>"><?=$mData['item_name']?></a></span></div>
    				<div class="rTableCell"><a href="/showExpForAccountByItemsMonthly/<?=$mData['acct_id']?>"><?=$mData['acct_name']?></a></div>
    				<div class="rTableCell"><a href="/showExpForCategoryByAccountsMonthly/<?=$mData['item_cat_id']?>"><?=$mData['item_cat']?></a></div>
    				
    				<?php if(($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR)) { ?>
    				<div class="rTableCell" align="right" id="payday_<?=$mData['item_id']?>"><?=$mData['pay_day']?> </div>
    				<div class="rTableCell" align="right" id="edit_button_payday_<?=$mData['item_id']?>"><img alt="edit" src="/images/b_edit.png" onclick="javascript:edit_payday_row('payday_<?=$mData['item_id']?>');"></div>
    				<div class="rTableCell" align="right" id="save_button_payday_<?=$mData['item_id']?>" style="display:none;"><img alt="save" src="/images/b_save.png" onclick="javascript:save_payday_row('payday_<?=$mData['item_id']?>');"></div>				
    				<?php } ?>
    				
    				<div class="rTableCell" align="right" id="allocation_<?=$mData['allocation_id']?>"><?=number_format($mData['depo_amt'],2)?></div>
    				<div class="rTableCell" align="right" id="edit_button_allocation_<?=$mData['allocation_id']?>"><img alt="edit" src="/images/b_edit.png" onclick="javascript:edit_allocate_amt_row('allocation_<?=$mData['allocation_id']?>');"></div>
    				<div class="rTableCell" align="right" id="save_button_allocation_<?=$mData['allocation_id']?>" style="display:none;"><img alt="save" src="/images/b_save.png" onclick="javascript:save_allocate_amt_row('allocation_<?=$mData['allocation_id']?>');"></div>					
    				<div class="rTableCell" align="right" style="font-size:12px;font-weight:bold;"><?=number_format($mData['available_amt'],2)?></div>
    				<?php
    				$edit_ids = '';
    				for($i = 1; $i <= 5; $i++){
    					$findData = 'no';
    					if(is_array($mData['exp_arr'])){
    						foreach($mData['exp_arr'] as $exp_amt){
    							if($exp_amt['EXP_SLOT'] == $i){
    								$findData = 'yes';
    								$exp_arr_id = $exp_amt['ID'];
    								$exp_arr_amt = $exp_amt['EXP_AMT'];
    								break;
    							}
    						}
    						if($findData == 'yes'){
    							print '<div class="rTableCell" align="right" id="div_expamt_' . $exp_arr_id . '">';
    							print number_format($exp_arr_amt,2);
    							${'t_exp_'.($i)} += $exp_arr_amt;
    							$edit_ids .= 'div_expamt_' . $exp_arr_id . ',';
    						}else{
    							print '<div class="rTableCell" align="right" id="div_newexpamt_' . $mData['item_id'] . '-' . $i .'">';
    							$edit_ids .= 'div_newexpamt_' . $mData['item_id'] . '-' .  $i . ',';								
    						}
    					}else{
    						print '<div class="rTableCell" align="right" id="div_newexpamt_' . $mData['item_id'] . '-' . $i .'">';
    						$edit_ids .= 'div_newexpamt_' . $mData['item_id'] . '-' .  $i . ',';
    					}
    					print '</div>';
    				}
    				?>
    				<div class="rTableCell" align="right" style="font-size:12px;font-weight:bold;"><?=number_format($mData['total_exp'],2)?></div>
    				<div class="rTableCell" align="right" id="edit_button<?=$m?>"><img alt="edit" src="/images/b_edit.png" onclick="javascript:edit_exp_row('<?=$edit_ids?>','<?=$m?>','<?=number_format($mData['available_amt'],2)?>');"></div>
    				<div class="rTableCell" align="right" id="save_button<?=$m?>" style="display:none;"><img alt="save" src="/images/b_save.png" onclick="javascript:save_exp_row('<?=$edit_ids?>');"></div>
    			</div>
    			<?php
    			$m++;
    			}
    		}
    		?>
    		<input type="hidden" name="modfldmonyear" id="modfldmonyear" value="<?=$item_mon_year?>">
    		<div class="rTableRow rTableFoot">
    			<div class="rTableCell" align="right">Total</div>
    			<div class="rTableCell" align="right">&nbsp;</div>
    			<div class="rTableCell" align="right">&nbsp;</div>
    			<div class="rTableCell" align="right">&nbsp;</div>
    			<?php if(($_SESSION['curr_mon'] == CURRENT_MONTH) && ($_SESSION['curr_yr'] == CURRENT_YEAR)) { ?>
    				<div class="rTableCell" align="right">&nbsp;</div>
    				<div class="rTableCell" align="right">&nbsp;</div>
    			<?php } ?>
    			<div class="rTableCell" align="right"><?=number_format($t_depo_amt,2)?></div>
    			<div class="rTableCell" align="right">&nbsp;</div>
    			<div class="rTableCell" align="right"><?=number_format($t_available_amt,2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($t_exp_1,2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($t_exp_2,2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($t_exp_3,2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($t_exp_4,2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($t_exp_5,2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($t_total_exp,2)?></div>
    			<div class="rTableCell" align="right">Bal: <?=number_format(($t_depo_amt - $t_total_exp),2)?>&nbsp;</div>
    		</div>
    	</div>
	</div>
</form>
<?=_getSectionFooter()?>		
		