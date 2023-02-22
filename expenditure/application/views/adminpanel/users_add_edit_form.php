<table width="100%" class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td> User Name : </td><td><input type="text" name="user_name" value="<?php if(isset($addEditFormData['USER_NAME'])) echo $addEditFormData['USER_NAME']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
			<?php $active='U';if(isset($addEditFormData['USER_TYPE'])) $active = $addEditFormData['USER_TYPE']; ?>
			<td> User Type : </td><td><?=_getActiveSelectCombo('user_type',$active,$action)?></td>
		</tr>
		<tr>
			<td> User Email : </td><td><input type="email" name="user_email" value="<?php if(isset($addEditFormData['USER_EMAIL'])) echo $addEditFormData['USER_EMAIL']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
			<?php $active='Y';if(isset($addEditFormData['ACTIVE'])) $active = $addEditFormData['ACTIVE']; ?>
			<td> Active : </td><td><?=_getActiveSelectCombo('active',$active,$action)?></td>
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
					<input type="submit" name="user_submit" value="Delete User">
					<?php					
				}else{
					?>
					<input type="submit" name="user_submit" value="<?php if($id > 0){echo 'Edit User';}else{echo 'Add User';}?>">
					<?php
				}
				?>
				&nbsp;&nbsp;<?php if($id > 0) echo '<a href="/getListing/' . $what . '"><input type="button" name="id" value="Reset"></a>';?> 
				</td>
		</tr>
	
	</tbody>
</table>