
<?=_getAdminSectionHeader('Dashboard','Overall Details')?>
<div class="col-lg-4">
    <div class="panel panel-default">
		<div class="panel-heading">All Time Status</div>
		<div class="panel-body">
			<div class="rTable">
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">Year</div>
					<div class="rTableHead">Deposit</div>
					<div class="rTableHead">Expenditure</div>
					<div class="rTableHead">Balance</div>
				</div>
				<?php
				foreach($allTimeData as $alldata)
				{
					print '<div class="rTableRow">';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . $alldata['depo_year'] . '</div>';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . $alldata['dipo_sum'] . '</div>';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . $alldata['exp_sum'] . '</div>';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . ($alldata['BALANCE']) . '</div>';
					print '</div>';	
				}
				?>
			</div>		
		
		</div>
	</div>
</div>
<div class="col-lg-5">
    <div class="panel panel-default">
		<div class="panel-heading">Expenditure Status</div>
		<div class="panel-body">
		<!-- Percent wise Expenditure Details -->
			<div class="rTableCell" style="border:0px solid gray;">
				<div class="rTable">
					<div class="rTableRow rTableHeading">
						<div class="rTableHead">Category</div>
						<div class="rTableHead">Monthly</div>
						<div class="rTableHead">Yearly</div>
						<div class="rTableHead">Percentage</div>
					</div>
					<?php
					$t_monCatAmt = 0;
					$t_yearCatAmt = 0;
					$t_percentCatAmt = 0;
					$i = 0;
					foreach ($catExpPercentData as $catData)
					{
						$t_monCatAmt += $catData['monthly_exp_amt'];
						$t_yearCatAmt += $catData['yearly_exp_amt'];
						$t_percentCatAmt += $catData['percent_exp_amount'];
						if ($i % 2 == 0) {
							$rowbgcss = 'even';
						}
						else
						{
							$rowbgcss = 'odd';
						}

						
						?>
						<div class="rTableRow <?=$rowbgcss?>" style="background-color:<?=$catData['bg_color']?>;color:<?=$catData['font_color']?>;">
							<div class="rTableCell"><a href="/showExpForCategoryByAccountsMonthly/<?=$catData['item_cat_id']?>"><?=$catData['item_cat_name']?></a></div>
							<div class="rTableCell" align="right"><?=number_format($catData['monthly_exp_amt'],2)?></div>
							<div class="rTableCell" align="right"><?=number_format($catData['yearly_exp_amt'],2)?></div>
							<div class="rTableCell" align="right"><?=number_format($catData['percent_exp_amount'],2)?>%</div>
						</div>
						<?php
						$i++;
					}
					?>
					<div class="rTableRow rTableFoot">
						<div class="rTableCell" align="right">Total</div>
						<div class="rTableCell" align="right"><?=number_format($t_monCatAmt,2)?></div>
						<div class="rTableCell" align="right"><?=number_format($t_yearCatAmt,2)?></div>
						<div class="rTableCell" align="right"><?=number_format($t_percentCatAmt,2)?>%</div>
					</div>			
				</div>
			</div>
		<!-- Percent wise Expenditure Details -->
		</div>
	</div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default">
		<div class="panel-heading">Color Code ( <?=count($colorData)?> )</div>
		<div class="panel-body">
			<div class="rTable">
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">BG COLOR + FONT COLOR</div>
				</div>
				<?php
				foreach($colorData as $ccdata)
				{
					print '<div class="rTableRow">';
						print '<div class="rTableCell" style="padding:5px;background-color:' . $ccdata['BG_COLOR'] . ';color:' . $ccdata['FONT_COLOR'] . ';height:20px;width:300px;" align="center">' . $ccdata['BG_COLOR'] . ' || ' . $ccdata['FONT_COLOR'] . '</div>';
					print '</div>';	
				}
				?>
			</div>		
		
		</div>
	</div>
</div> 
<div class="col-lg-4">
    <div class="panel panel-default">
		<div class="panel-heading">Accounts ( <?=count($accountsData)?> )</div>
		<div class="panel-body">
			<div class="rTable">
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">Account</div>
					<div class="rTableHead">Current Balance</div>
				</div>
				<?php
				foreach($accountsData as $adata)
				{
					print '<div class="rTableRow">';
						print '<div class="rTableCell" style="padding:5px;background-color:' . $adata['BG_COLOR'] . ';font-color:' . $adata['FONT_COLOR'] . ';">' . $adata['EXP_ACCOUNT'] . '</div>';
						print '<div class="rTableCell" style="padding:5px;background-color:' . $adata['BG_COLOR'] . ';font-color:' . $adata['FONT_COLOR'] . ';">' . $adata['CURRENT_BAL'] . '</div>';
					print '</div>';
				}
				?>
			</div>
		</div>
	</div>
</div> 
<div class="col-lg-4">
    <div class="panel panel-default">
		<div class="panel-heading">Categories ( <?=count($categoryData)?> )</div>
		<div class="panel-body">			<div class="rTable">
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">Catgory</div>
				</div>
				<?php
				foreach($categoryData as $cdata)
				{
					print '<div class="rTableRow">';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . $cdata['EXP_ITEM_CAT'] . '</div>';
					print '</div>';
				}
				?>
			</div>
		</div>
	</div>
</div> 
<div class="col-lg-4">
    <div class="panel panel-default">
		<div class="panel-heading">Items ( <?=count($itemsData)?> )</div>
		<div class="panel-body">
			<div class="rTable">
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">Item</div>
					<div class="rTableHead">Status</div>
				</div>
				<?php
				foreach($itemsData as $idata)
				{
					print '<div class="rTableRow">';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . $idata['EXP_ITEM_NAME'] . '</div>';
						print '<div class="rTableCell" style="padding:5px;background-color:#ececec;font-color:#000;">' . $idata['ACTIVE'] . '</div>';
					print '</div>';
				}
				?>
			</div>		
		</div>
	</div>
</div>



<?=_getAdminSetionFooter()?>