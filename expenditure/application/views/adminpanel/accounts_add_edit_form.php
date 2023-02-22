<table width="100%" class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td> Account Name : </td><td><input type="text" name="acct_name" value="<?php if(isset($addEditFormData['EXP_ACCOUNT'])) echo $addEditFormData['EXP_ACCOUNT']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
			<td> Current Balance : </td><td><input type="text" name="curr_bal" value="<?php if(isset($addEditFormData['CURRENT_BAL'])) echo $addEditFormData['CURRENT_BAL']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
		</tr>
		<tr>
			<td> Primary Balance : </td><td><input type="text" name="pri_bal" value="<?php if(isset($addEditFormData['PRIMARY_BAL'])) echo $addEditFormData['PRIMARY_BAL']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
			<?php $color_code_id=0;if(isset($addEditFormData['COLOR_CODE_ID'])) $color_code_id = $addEditFormData['COLOR_CODE_ID']; ?>
			<td> Color Code : </td><td><?=_getSelectColorCombo($colorCodeList,'colorCode',$color_code_id,$action)?></td>
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
					<input type="submit" name="account_submit" value="Delete Account">
					<?php					
				}else{
					?>
					<input type="submit" name="account_submit" value="<?php if($id > 0){echo 'Edit Account';}else{echo 'Add Account';}?>">
					<?php
				}
				?>
				&nbsp;&nbsp;<?php if($id > 0) echo '<a href="/getListing/' . $what . '"><input type="button" name="id" value="Reset"></a>';?>
			</td>
		</tr>
	
	</tbody>
</table>
<script language="javascript">
function colourFunction() {
var myselect = document.getElementById("colorCode"),
colour = myselect.options[myselect.selectedIndex].style.cssText;//alert(colour);
myselect.style.cssText=colour;
//myselect.blur(); //This just unselects the select list without having to click somewhere else on the page
}
</script>