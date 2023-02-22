<table width="100%" class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td> Background Color : </td><td><input name="bg_color" type="hidden" id="bg_color" value="<?php if(isset($addEditFormData['BG_COLOR'])){ echo $addEditFormData['BG_COLOR'];}else{echo '#ececec';} ?>">
			<button class="jscolor {valueElement: 'bg_color'}" style="height:45px;width:240px;"><?php if(isset($addEditFormData['BG_COLOR'])){ echo $addEditFormData['BG_COLOR'];}else{echo '#ececec';} ?></button></td>
		</tr>
		<tr>
			<td> Font Color : </td><td><input name="font_color" type="hidden" id="font_color" value="<?php if(isset($addEditFormData['FONT_COLOR'])){ echo $addEditFormData['FONT_COLOR'];}else{echo '#000000';} ?>">
			<button class="jscolor {valueElement: 'font_color'}" style="height:45px;width:240px;"><?php if(isset($addEditFormData['FONT_COLOR'])){ echo $addEditFormData['FONT_COLOR'];}else{echo '#000000';} ?></button></td>
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
					<input type="submit" name="colorcode_submit" value="Delete Color Code">
					<?php					
				}else{
					?>
					<input type="submit" name="colorcode_submit" value="<?php if($id > 0){echo 'Edit Color Code';}else{echo 'Add Color Code';}?>">
					<?php
				}
				?>
				&nbsp;&nbsp;<?php if($id > 0) echo '<a href="/getListing/' . $what . '"><input type="button" name="id" value="Reset"></a>';?>
			</td>
		</tr>
	
	</tbody>
</table>