<?=_getAdminSectionHeader('Account Menu','Account List')?>

	<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
			<tr>
				<th>Account Name</th>
				<th>Primary Bal</th>
				<th>Current Bal</th>
				<th>Color Code</th>
				<th>Action</th>
				<th>Action</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$rowCount = 0;
		foreach($accountData as $acctArr) {  
			echo '<tr class="odd gradeX">';
			echo '<td>'.$acctArr['EXP_ACCOUNT'].'</td>';
			echo '<td>'.$acctArr['PRIMARY_BAL'].'</td>';
			echo '<td>'.$acctArr['CURRENT_BAL'].'</td>';
			echo '<td>'.$acctArr['COLOR_CODE_ID'].'</td>';
			
			echo '<td><button type="button" class="btn btn-outline btn-primary btn-xs btn-block"><a href="hs-publisher-main-edit.php?affiliateKey='.$acctArr['ID'].'">Edit Publisher</a></button></td>';
			echo '<td><button type="button" class="btn btn-outline btn-primary btn-xs btn-block"><a href="hs-publishers-edit.php?affiliateKey='.$acctArr['ID'].'">Edit Service Config</a></button></td>';
			echo '<td><button value="'.$acctArr['ID'].'" type="button" class="xmlConfigShow btn btn-outline btn-primary btn-xs btn-block">View XML</button></td>';
			echo '<input type="hidden" value="'.$acctArr['ID'].'" id="affiliateKey">';
			echo '</tr>';
		}
		?>                                
		</tbody>
	</table>
<?=_getAdminSetionFooter()?>