<?=_getAdminSectionHeader('Item Menu','Item List')?>

	<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Item Name</th>
				<th>Category</th>
				<th>Account</th>
				<th>Deafult Deposit</th>
				<th>Pay Day</th>
				<th>Active</th>
				<th>Expired</th>
				<th>Fixed Payee</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$rowCount = 0;
		foreach($itemData as $itemArr) {  
			echo '<tr class="odd gradeX">';
			echo '<td>'.$itemArr['EXP_ITEM_NAME'].'</td>';
			echo '<td>'.$itemArr['EXP_ITEM_CAT'].'</td>';
			echo '<td>'.$itemArr['EXP_ACCOUNT'].'</td>';
			echo '<td>'.$itemArr['DEFAULT_DEPO_AMT'].'</td>';
			echo '<td>'.$itemArr['PAY_DAY'].'</td>';
			echo '<td>'.$itemArr['ACTIVE'].'</td>';
			echo '<td>'.$itemArr['EXPIRED'].'</td>';
			echo '<td>'.$itemArr['FIXED_PAYEE'].'</td>';
			
			
			echo '<input type="hidden" value="'.$itemArr['ID'].'" id="affiliateKey">';
			echo '</tr>';
		}
		?>                                
		</tbody>
	</table>
<?=_getAdminSetionFooter()?>