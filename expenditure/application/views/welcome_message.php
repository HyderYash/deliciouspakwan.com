<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	
	
.rTable {
  	display: table;
  	width: 100%;
}
.rTableRow {
  	display: table-row;
}
.rTableHeading {
  	display: table-header-group;
  	background-color: #D3DCE3;
	color:#0000ff;
}
.rTableCell, .rTableHead {
  	display: table-cell;
  	padding: 3px 10px;
  	border: 1px solid #fff;
}
.rTableHeading {
  	display: table-header-group;
  	background-color: #ddd;
  	font-weight: bold;
}
.rTableFoot {
  	display: table-footer-group;
  	font-weight: bold;
  	background-color: #ddd;
}
.rTableBody {
  	display: table-row-group;
}
.odd {
    background: #e5e5e5 none repeat scroll 0 0;
}
.even {
    background: #d5d5d5 none repeat scroll 0 0;
}	
        #scrolly{
            width: 99%;
            height: 190px;
            overflow: auto;
            overflow-y: hidden;
            margin: 0 auto;
            white-space: nowrap
        }
	</style>
</head>
<body>
<!-- All Account Status -->
<div id="container">
	<h1>Current Monthly Status</h1>
	<div id="body">
		<div id="scrolly">
			<div class="rTable">
				<?php
				$c = 0;
				foreach($allMonthsData as $months => $amtArr)
				{
					if($c < 1)
					{
					?>
						<div class="rTableRow rTableHeading">
							<div class="rTableHead">&nbsp;</div>
							<?php
							foreach($amtArr as $amtdata)
							{
								?>
								<div class="rTableHead"><?=$amtdata['acct_name']?></div>
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
								<div class="rTableHead">In</div>
								<div class="rTableHead">Out</div>
								<div class="rTableHead">Bal</div>
								<div class="rTableHead">CF</div>
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

				foreach($allMonthsData as $months => $amtArr)
				{
					
					if ($m % 2 == 0) {
						$rowbgcss = 'even';
					}
					else
					{
						$rowbgcss = 'odd';
					}					
					?>
					<div class="rTableRow <?=$rowbgcss?> <?php if($months == 'Total') echo 'rTableFoot';?>">
						<div class="rTableHead" style="background-color:#B6DDE8;"><?=$months?></div>
						<?php
						foreach($amtArr as $amtdata)
						{
							?>
							<div class="rTableCell" align="right"><?=number_format($amtdata['depo_amt'],2)?></div>
							<div class="rTableCell" align="right"><?=number_format($amtdata['exp_amt'],2)?></div>
							<div class="rTableCell" align="right"><?=number_format($amtdata['bal_amt'],2)?></div>
							<div class="rTableCell" align="right"><?=number_format($amtdata['cf_amt'],2)?></div>
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
	<p class="footer">Created on <strong><?=date("d-m-Y") . ' ' . date("h:i:sa")?></strong></p>
	</div>
</div>


<p>&nbsp;</p>
<div id="container">
	<h1>January</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>
		<div class="rTable">
			<div class="rTableRow rTableHeading">									
				<div class="rTableHead">Items</div>
				<div class="rTableHead">Account</div>
				<div class="rTableHead">Exp Category</div>
				<div class="rTableHead">Allocated</div>
				<div class="rTableHead">Available</div>
				<div class="rTableHead">Exp1</div>
				<div class="rTableHead">Exp2</div>
				<div class="rTableHead">Exp3</div>
				<div class="rTableHead">Exp4</div>
				<div class="rTableHead">Exp5</div>
				<div class="rTableHead">Total</div>
			</div>
			<?php
			//print '<pre>';print_r($dashboardData);die;
			$m = 0;
			$t_depo_amt = 0;
			$t_available_amt = 0;
			$t_exp_1 = 0;
			$t_exp_2 = 0;
			$t_exp_3 = 0;
			$t_exp_4 = 0;
			$t_exp_5 = 0;
			$t_total_exp = 0;
			foreach ($monthlyData as $mData)
			{
				$t_depo_amt += $mData['depo_amt'];
				$t_available_amt += $mData['available_amt'];
				$t_total_exp += $mData['total_exp'];
	
				if ($m % 2 == 0) {
					$rowbgcss = 'even';
				}
				else
				{
					$rowbgcss = 'odd';
				}				
				?>			
				<div class="rTableRow <?=$rowbgcss?>">
					<div class="rTableCell"><?=$mData['item_name']?></div>
					<div class="rTableCell"><?=$mData['acct_name']?></div>
					<div class="rTableCell"><?=$mData['item_cat']?></div>
					<div class="rTableCell" align="right"><?=number_format($mData['depo_amt'],2)?></div>
					<div class="rTableCell" align="right"><?=number_format($mData['available_amt'],2)?></div>
					<?php
					for($i = 0; $i < 5; $i++)
					{
						print '<div class="rTableCell" align="right">';
						if(is_array($mData['exp_arr']) && isset($mData['exp_arr'][$i]['EXP_AMT']))
						{
							print number_format($mData['exp_arr'][$i]['EXP_AMT'],2);
							${'t_exp_'.($i+1)} += $mData['exp_arr'][$i]['EXP_AMT'];
						}
						print '</div>';
					}
					?>
					<div class="rTableCell" align="right"><?=number_format($mData['total_exp'],2)?></div>
				</div>
				<?php
				$m++;
			}
			?>
			<div class="rTableRow rTableFoot">
				<div class="rTableCell" align="right">Total</div>
				<div class="rTableCell" align="right">&nbsp;</div>
				<div class="rTableCell" align="right">&nbsp;</div>
				<div class="rTableCell" align="right"><?=number_format($t_depo_amt,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_available_amt,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_exp_1,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_exp_2,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_exp_3,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_exp_4,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_exp_5,2)?></div>
				<div class="rTableCell" align="right"><?=number_format($t_total_exp,2)?></div>
			</div>				
		</div>
	<p class="footer">Created on <strong><?=date("d-m-Y") . ' ' . date("h:i:sa")?></strong></p>
	</div>
</div>


<p>&nbsp;</p>
	<div id="container">
		<h1>Dashboard</h1>

		<div id="body">
			<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>
			<div class="rTable">
				<div class="rTableRow rTableHeading">
					<div class="rTableHead">Accounts</div>
					<div class="rTableHead">System Bal</div>
					<div class="rTableHead">Primary Bal</div>
					<div class="rTableHead">Savings</div>
					<div class="rTableHead">Recurring</div>
					<div class="rTableHead">CF</div>
					<div class="rTableHead">All Savings</div>
					<div class="rTableHead">Expected Sys Bal</div>
					<div class="rTableHead">Current Bal</div>
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
					if ($i % 2 == 0) {
						$rowbgcss = 'even';
					}
					else
					{
						$rowbgcss = 'odd';
					}

					
					?>
					<div class="rTableRow <?=$rowbgcss?>">
						<div class="rTableCell"><?=$dData['Accounts']?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['System Bal'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['Primary Bal'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['Savings'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['Recurring'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['CF'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['All Savings'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['Expected Sys Bal'],2)?></div>
						<div class="rTableCell" align="right"><?=number_format($dData['Current Bal'],2)?></div>
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
					<div class="rTableCell" align="right"><?=number_format($t_actualavl,2)?></div>
					<div class="rTableCell" align="right"><?=number_format($t_emergencyavl,2)?></div>
				</div>			
			</div>
		</div>

		
		<p class="footer">Created on <strong><?=date("d-m-Y") . ' ' . date("h:i:sa")?></strong></p>
	</div>
</body>
</html>