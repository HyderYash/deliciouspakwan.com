<?=_getSectionHeader('Transfer Required','Monthly and Yearly Transfer required along with Expenditures in Categories')?>
<div id="scrolly">
    <div class="rTable">
    	<div class="rTableRow">
    		<div class="rTableCell" style="border:0px solid gray;">
    			<div class="rTable">
    				<div class="rTableRow rTableHeading">
    					<div class="rTableHead">To Account</div>
    					<div class="rTableHead">Monthly</div>
    					<div class="rTableHead">Yearly</div>
    					<div class="rTableHead">Transfer</div>
    					<div class="rTableHead">Cur. Adjst.</div>
    					<div class="rTableHead">Extra Amt. Req.</div>
    				</div>
    				<?php
    				$t_monthlyAmt = 0;
    				$t_yearlyAmt = 0;
    				$t_tranAmt = 0;
    				$t_currAdjAmt = 0;
    				$t_extraAdjReq = 0;
    				$i = 0;
    				foreach ($transferData as $dData)
    				{
    					$t_monthlyAmt += $dData['MonthlyAmt'];
    					$t_yearlyAmt += $dData['YearlyAmt'];
    					$t_tranAmt += $dData['Transfer'];
    					$t_currAdjAmt += $dData['Curr_Adjustment'];
    					$t_extraAdjReq += $dData['Extra_Amt_Req'];
    					if ($i % 2 == 0) {
    						$rowbgcss = 'even';
    					}
    					else
    					{
    						$rowbgcss = 'odd';
    					}
    
    					?>
    					<div class="rTableRow <?=$rowbgcss?>" style="background-color:<?=$dData['bg_color']?>;color:<?=$dData['font_color']?>;">
    						<div class="rTableCell">To <a href="/showExpForAccountByItemsMonthly/<?=$dData['AccountID']?>"><?=$dData['Accounts']?></a></div>
    						<div class="rTableCell" align="right"><?=number_format($dData['MonthlyAmt'],2)?></div>
    						<div class="rTableCell" align="right"><?=number_format($dData['YearlyAmt'],2)?></div>
    						<div class="rTableCell" align="right"><?=number_format($dData['Transfer'],2)?></div>
    						<div class="rTableCell" align="right"><?=number_format($dData['Curr_Adjustment'],2)?></div>
    						<div class="rTableCell" align="right"><?=number_format($dData['Extra_Amt_Req'],2)?></div>
    					</div>
    					<?php
    					$i++;
    				}
    				?>
    				<div class="rTableRow rTableFoot">
    					<div class="rTableCell" align="right">Total</div>
    					<div class="rTableCell" align="right"><?=number_format($t_monthlyAmt,2)?></div>
    					<div class="rTableCell" align="right"><?=number_format($t_yearlyAmt,2)?></div>
    					<div class="rTableCell" align="right"><?=number_format($t_tranAmt,2)?></div>
    					<div class="rTableCell" align="right"><?=number_format($t_currAdjAmt,2)?></div>
    					<div class="rTableCell" align="right"><?=number_format($t_extraAdjReq,2)?></div>
    
    				</div>			
    			</div>
    		</div>
    		<div class="rTableCell" style="border:0px solid gray;">
    			<div class="rTable">
    				<div class="rTableRow rTableHeading">
    					<div class="rTableHead">Exp Category</div>
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
    	</div>
    </div>
</div>
<?=_getSectionFooter()?>