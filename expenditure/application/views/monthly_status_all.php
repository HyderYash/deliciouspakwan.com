<!-- All Account Status -->
<?php
$yearList = _getYearListWithForm('index.php');
?>
<?=_getSectionHeader("Year " . $_SESSION['exp_item_year'] . " Monthly Status", 'All TIME EXP AMOUNT: ' . number_format($acctTotalAmt, 2) .$yearList)?>
<div id="scrolly">
	<div class="rTable">
		<?php
		$c = 0;
		foreach($allMonthsData as $months => $amtArr){
			if($c < 1)
			{
			?>
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">&nbsp;</div>
					<?php
					foreach($amtArr as $amtdata)
					{
						if($amtdata['acct_name'] == 'TOTAL')
						{
							?>
							<div class="rTableHead" style="background-color:<?=$amtdata['bg_color']?>;color:<?=$amtdata['font_color']?>;"><?=$amtdata['acct_name']?></div>
							<?php								
						}
						else
						{
							?>
							<div class="rTableHead" style="background-color:<?=$amtdata['bg_color']?>;color:<?=$amtdata['font_color']?>;"><a href="/showExpForAccountByItemsMonthly/<?=$amtdata['acct_id']?>"><?=$amtdata['acct_name']?></a></div>
							<?php
						}
						?>
						<div class="rTableHead">&nbsp;</div>
						<div class="rTableHead">&nbsp;</div>
						<div class="rTableHead">&nbsp;</div>
						<?php							
					}
					?>
				</div>
				<div class="rTableRow rTableHeading">
					
					<div class="rTableHead" style="background-color:#B6DDE8;">Months</div>
					<?php
					foreach($amtArr as $amtdata)
					{
						?>
						<div class="rTableHead" style="background-color:<?=$amtdata['bg_color']?>;color:<?=$amtdata['font_color']?>;">In</div>
						<div class="rTableHead" style="background-color:<?=$amtdata['bg_color']?>;color:<?=$amtdata['font_color']?>;">Out</div>
						<div class="rTableHead" style="background-color:<?=$amtdata['bg_color']?>;color:<?=$amtdata['font_color']?>;">Bal</div>
						<div class="rTableHead" style="background-color:<?=$amtdata['bg_color']?>;color:<?=$amtdata['font_color']?>;">CF</div>
						<?php							
					}
					?>
				</div>					
				<?php
			}
			$c++;
		}
		?>	
		<?php
		$m = 0;

		foreach($allMonthsData as $months => $amtArr){
			?>
			<div class="rTableRow <?php if($months == 'Total') echo 'rTableFoot';?>">
				<?php
				if($months == 'Total'){
					?>
					<div class="rTableHead" style="background-color:#B6DDE8;"><?=$months?></div>
					<?php
				}
				else{
					$monLink = $amtArr[0]['month_number'] . '-' . $amtArr[0]['month_year'];
					?>
					<div class="rTableHead" style="background-color:#B6DDE8;">
						<a href="/showMonthlyStatusCurrent/?showMonth=<?=$monLink?>"><?=$months?></a>
					</div>
					<?php					
				}//print '<pre>';print $monLink;die;
				$style = '';
				foreach($amtArr as $amtdata){
					if(isset($amtdata['bg_color']))
					{
						$style = ' style="background-color:' . $amtdata['bg_color'] . ';color:' . $amtdata['font_color'] . ';"';
					}
					?>
					<div class="rTableCell" align="right"<?=$style?>><?=number_format($amtdata['depo_amt'],2)?></div>
					<div class="rTableCell" align="right"<?=$style?>><?=number_format($amtdata['exp_amt'],2)?></div>
					<div class="rTableCell" align="right"<?=$style?>><?=number_format($amtdata['bal_amt'],2)?></div>
					<div class="rTableCell" align="right"<?=$style?>><?=number_format($amtdata['cf_amt'],2)?></div>
					<?php
				}
				?>
			</div>
			<?php
			$m++;
		}
		?>
	</div>
</div>
<?=_getSectionFooter()?>	