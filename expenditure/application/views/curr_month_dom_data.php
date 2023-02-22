<?=_getSectionHeader("Showing Current Month's Expenditure by " . $showMonthlyItem,'Allocated - Expenditure = Balance' . "   [  <a href='/index.php/?Accounts=Y&timeDuration=" . $timeDuration . "'>Show by Accounts</a>  ]" . "   [  <a href='/index.php/?Category=Y&timeDuration=" . $timeDuration . "'>Show by Category</a>  ]"  . "   [  <a href='/index.php/?Items=Y&timeDuration=" . $timeDuration . "'>Show by Items</a>  ]")?>
<div id="scrolly">
    <div class="rTable" style="border:none;">
    	<div class="rTableRow" style="border:none;">
    		<div class="rTableCell" style="border:none;border-radius:100px;vertical-align:middle;width:200px;font-size:20px;text-align:center;background-color:<?=$currMonthDomData[0]['bg_color']?>;color:<?=$currMonthDomData[0]['font_color']?>;"><a href="/showMonthlyStatusCurrent"><?=$timeDuration?></a><br><br>
    	<select name="mo_year" onchange="javascript:showThisMonthDomData(this.value,'<?=$showMonthlyItem?>');" style="border:none;vertical-align:middle;width:160px;font-size:16px;text-align:center;color:<?=$currMonthDomData[0]['font_color']?>;">
    		<?php
    		print '<option value="All_Time"';
    				if($timeDuration == 'All_Time'){
    					print ' selected="true"';
    				}
    				print '>All_Time</option>';
    		foreach ($monthlselect as $monyear => $monname){
    			$year = $monyear;
    			foreach ($monname as $monname){
    				$month = $monname['month_number'];
    				print '<option value="' . $month . '-' . $year . '"';
    				if($month . '-' . $year == $timeDuration){
    					print ' selected="true"';
    				}
    				print '>' . $monname['month_name'] . ' ' . $year . '</option>';
    			}
    		}
    		?>
    	</select>		
    		
    		</div>
    		<div class="rTableCell" style="border:none;">
    			<div class="rTable" style="border:none;">
    				<div class="rTableRow" style="border:none;">
    				<?php
    				$t_depo_amt = 0;
    				$t_exp_amt = 0;
    				$t_bal_amt = 0;
    				$i = 1;
    				foreach ($currMonthDomData as $dData)
    				{
    					$t_depo_amt += $dData['depo_amt'];
    					$t_exp_amt += $dData['exp_amt'];
    					$t_bal_amt += $dData['bal_amt'];
    					if($i > 3)
    					{
    						?></div><br><div class="rTableRow" style="margin:30px; style='border:none;'"><?php
    						$i = 1;
    					}
    					?>
    					<div class="rTableCell" style="vertical-align:top;font-size:14px;text-align:center;background-color:<?=$dData['bg_color']?>;color:<?=$dData['font_color']?>;border:none;width:300px;padding-top:10px;padding-bottom:10px;border-radius:100px;">
    						<div class="rTable">
    							<div class="rTableRow">
    								<div class="rTableCell" style="border:none;"><a href="<?=$dData['temp_link']?>"><?=$dData['temp_name']?></a></div>
    							</div><br>
    							<div class="rTableRow">
    								<div class="rTableCell" style="border:none;vertical-align:top;font-size:12px;text-align:center;<?php if($dData['bal_amt'] < 0){echo "background-color:red;";}?>"><?=number_format($dData['depo_amt'],2)?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=number_format($dData['exp_amt'],2)?>&nbsp;&nbsp;=&nbsp;&nbsp;<?=number_format($dData['bal_amt'],2)?></div>
    							</div><br>
    							<div class="rTableRow">
    								<div class="rTableCell" style="border:none;vertical-align:top;font-size:14px;text-align:center;">100%&nbsp;&nbsp;-&nbsp;&nbsp;<?=number_format($dData['exp_percent'],2)?>%&nbsp;&nbsp;=&nbsp;&nbsp;<?=number_format($dData['bal_percent'],2)?>%</div>
    							</div>							
    						</div>
    					</div><div style="width:10px;">&nbsp;</div>
    					<?php
    					
    					$i++;
    				}
    				if($t_depo_amt > 0){
    					$t_expPercent = ($t_exp_amt * 100)/$t_depo_amt;
    					$t_balPercent = (100 - $t_expPercent);
    				}else{
    					$t_expPercent = 0;
    					$t_balPercent = 0;					
    				}				
    				?>				
    				</div>
    			</div>
    		</div>
    		<div class="rTableCell" style="border:none;border-radius:100px;vertical-align:middle;width:200px;font-size:20px;text-align:center;background-color:<?=$currMonthDomData[0]['bg_color']?>;color:<?=$currMonthDomData[0]['font_color']?>;">
    			<div class="rTable">
    				<div class="rTableRow">
    					<div class="rTableCell" style="border:none;">TOTAL</div>
    				</div><br>
    				<div class="rTableRow">
    					<div class="rTableCell" style="border:none;vertical-align:top;font-size:12px;text-align:center;"><?=number_format($t_depo_amt,2)?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=number_format($t_exp_amt,2)?>&nbsp;&nbsp;=&nbsp;&nbsp;<?=number_format($t_bal_amt,2)?></div>
    				</div><br>
    				<div class="rTableRow">
    					<div class="rTableCell" style="border:none;vertical-align:top;font-size:14px;text-align:center;">100%&nbsp;&nbsp;-&nbsp;&nbsp;<?=number_format($t_expPercent,2)?>%&nbsp;&nbsp;=&nbsp;&nbsp;<?=number_format($t_balPercent,2)?>%</div>
    				</div>							
    			</div>
    		</div>
    	</div>
    </div>
</div>
<?=_getSectionFooter()?>