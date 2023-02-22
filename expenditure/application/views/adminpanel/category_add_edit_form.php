<table width="100%" class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td> Category Name : </td><td><input type="text" name="cat_name" value="<?php if(isset($addEditFormData['EXP_ITEM_CAT'])) echo $addEditFormData['EXP_ITEM_CAT']; ?>"<?php if($action == 'delete'){echo ' disabled';}?>></td>
		</tr>
		
		<tr>
			<td colspan="2"> 
				<input type="hidden" name="what" value="<?=$what?>">
				<input type="hidden" name="action" value="<?=$action?>">
				<input type="hidden" name="id" value="<?=$id?>">
				<?php
				if($action == 'delete')
				{
					?>
					<input type="submit" name="category_submit" value="Delete Category">
					<?php					
				}else{
					?>
					<input type="submit" name="category_submit" value="<?php if($id > 0){echo 'Edit Category';}else{echo 'Add Category';}?>">
					<?php
				}
				?>
				&nbsp;&nbsp;<?php if($id > 0) echo '<a href="/getListing/' . $what . '"><input type="button" name="id" value="Reset"></a>';?>
			</td>
		</tr>
	
	</tbody>
</table>