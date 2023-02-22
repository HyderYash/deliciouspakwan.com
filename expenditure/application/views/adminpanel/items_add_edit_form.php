<table width="100%" class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td> Item Name : </td><td><input type="text" name="item_name" value="<?php if(isset($addEditFormData['Items'])) echo $addEditFormData['Items']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
			<?php $active='Y';if(isset($addEditFormData['ACTIVE'])) $active = $addEditFormData['ACTIVE']; ?> 
			<td> Active : </td><td><?=_getActiveSelectCombo('active',$active,$action)?></td>
		</tr>
		<tr>
			<?php $cat_id=0;if(isset($addEditFormData['cat_id'])) $cat_id = $addEditFormData['cat_id']; ?>
			<td> Category : </td><td><?=_getSelectCombo($categoryList,'cat_id',$cat_id,$action)?></td>
			<?php $active='N';if(isset($addEditFormData['FIXED_PAYEE'])) $active = $addEditFormData['FIXED_PAYEE']; ?>
			<td> Fixed Payee : </td><td><?=_getActiveSelectCombo('fixed_payee',$active,$action)?></td>
		</tr>
		<tr>
			<?php $acct_id=0;if(isset($addEditFormData['acct_id'])) $acct_id = $addEditFormData['acct_id']; ?>
			<td> Account : </td><td><?=_getSelectCombo($accountList,'acct_id',$acct_id,$action)?></td>
			<?php $active='N';if(isset($addEditFormData['EXPIRED'])) $active = $addEditFormData['EXPIRED']; ?>
			<td> Expired : </td><td><?=_getActiveSelectCombo('expired',$active,$action)?></td>
		</tr>
		<tr>
			<td> Default Deposit : </td><td><input type="text" name="def_depo" value="<?php if(isset($addEditFormData['DefDepo'])) echo $addEditFormData['DefDepo']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
			
			<?php $payday=0;if(isset($addEditFormData['PAY_DAY'])) $payday = $addEditFormData['PAY_DAY']; ?>
			<td> Pay Day : </td><td><?=_getActiveSelectCombo('payday',$payday,$action,'number')?></td>
		</tr>
		<tr>
			<td colspan="4"> 
				<input type="hidden" name="what" value="<?=$what?>">
				<input type="hidden" name="action" value="<?=$action?>">
				<input type="hidden" name="id" value="<?=$id?>">
				<?php
				if($action == 'delete')
				{
					?>
					<input type="submit" name="item_submit" value="Delete Item" onclick="return confirm('Are you sure you want to delete this item?');">
					<?php					
				}else{
					?>
					<input type="submit" name="item_submit" value="<?php if($id > 0){echo 'Edit Item';}else{echo 'Add Item';}?>">
					<?php
				}
				?>
				&nbsp;&nbsp;<?php if($id > 0) echo '<a href="/getListing/' . $what . '"><input type="button" name="id" value="Reset"></a>';?>
			</td>
		</tr>
	
	</tbody>
</table>