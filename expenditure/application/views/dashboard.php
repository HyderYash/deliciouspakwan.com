<?=_getSectionHeader('Dashboard','Current Accounts Status')?>
<div id="scrolly">
    <div class="rTable">
    	<div class="rTableRow rTableHeading">
    		<div class="rTableHead">Accounts</div>
    		<div class="rTableHead">System Bal</div>
    		<div class="rTableHead">Primary Bal</div>
    		<div class="rTableHead">Savings</div>
    		<div class="rTableHead">Recurring</div>
    		<div class="rTableHead">CF (info)</div>
    		<div class="rTableHead">All Savings</div>
    		<div class="rTableHead">Expected Bal</div>
    		<div class="rTableHead">Current Bal</div>
    		<div class="rTableHead">Action</div>
    		<div class="rTableHead">Actual Avl</div>
    		<div class="rTableHead">Emergency Avl</div>
    	</div>
    	<?php
    	$t_sysbal = 0;
    	$t_pribal = 0;
    	$t_savings = 0;
    	$t_recurring = 0;
    	$t_cf = 0;
    	$t_all_savings = 0;
    	$t_expectedbal = 0;
    	$t_actualcurrbal = 0;
    	$t_currbal = 0;
    	$t_actualavl = 0;
    	$t_emergencyavl = 0;
    	$i = 0;
    	foreach ($dashboardData as $dData)
    	{
    		$t_sysbal += $dData['System Bal'];
    		$t_pribal += $dData['Primary Bal'];
    		$t_savings += $dData['Savings'];
    		$t_recurring += $dData['Recurring'];
    		$t_cf += $dData['CF'];
    		$t_all_savings += $dData['All Savings'];
    		$t_expectedbal += $dData['Expected Sys Bal'];
    		$t_currbal += $dData['Current Bal'];
    		$t_actualavl += $dData['Actual Avl'];
    		$t_emergencyavl += $dData['Emergency Avl'];
    
    		?>
    		<div class="rTableRow" style="background-color:<?=$dData['bg_color']?>;color:<?=$dData['font_color']?>;">
    			<div class="rTableCell"><a href="/showExpForAccountByItemsMonthly/<?=$dData['Account ID']?>"><?=$dData['Accounts']?></a></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['System Bal'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['Primary Bal'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['Savings'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['Recurring'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['CF'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['All Savings'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['Expected Sys Bal'],2)?></div>
    
    			<div class="rTableCell" align="right" id="curr_bal_<?=$dData['Account ID']?>"><?=number_format($dData['Current Bal'],2)?></div>
    			<div class="rTableCell" align="right" id="edit_button_curr_bal_<?=$dData['Account ID']?>"><img src="/images/b_edit.png" onclick="javascript:edit_curr_bal_amt_row('curr_bal_<?=$dData['Account ID']?>');"></div>
    			<div class="rTableCell" align="right" id="save_button_curr_bal_<?=$dData['Account ID']?>" style="display:none;"><img src="/images/b_save.png" onclick="javascript:save_curr_bal_amt_row('curr_bal_<?=$dData['Account ID']?>');"></div>							
    			<div class="rTableCell" align="right"><?=number_format($dData['Actual Avl'],2)?></div>
    			<div class="rTableCell" align="right"><?=number_format($dData['Emergency Avl'],2)?></div>
    		</div>
    		<?php
    		$i++;
    	}
    	?>
    	<div class="rTableRow rTableFoot">
    		<div class="rTableCell" align="right">Total</div>
    		<div class="rTableCell" align="right"><?=number_format($t_sysbal,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_pribal,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_savings,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_recurring,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_cf,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_all_savings,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_expectedbal,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_currbal,2)?></div>
    		<div class="rTableCell" align="right">&nbsp;</div>
    		<div class="rTableCell" align="right"><?=number_format($t_actualavl,2)?></div>
    		<div class="rTableCell" align="right"><?=number_format($t_emergencyavl,2)?></div>
    	</div>			
    </div>
</div>
<?=_getSectionFooter()?>