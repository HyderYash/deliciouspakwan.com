<?=_getAdminSectionHeader(ucfirst($what) .' Menu', ucfirst($what) .' List and Add/Edit/Delete ' . ucfirst($what) .' Form', $msg)?>
<?php
if(!is_array($listingData)){
	print $listingData; 
}else
{
?>
<?php
$this->load->view('adminpanel/' . $what . '_add_edit_form.php',$addEditFormData, '');
?>
<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
	<thead>
		<tr>
			<?php
			foreach($listingData['field'] as $fieldArr) {
			echo '<th>' . $fieldArr . '</th>';
			}
			echo '<th>Action</th>';
			?>
		</tr>
	</thead>
	<tbody>
	<?php
	$rowCount = 0;
	foreach($listingData['data'] as $listArr) { 
		echo '<tr class="odd gradeX">';
			foreach($listArr as $list) {
				if(strpos($list,"#") !== false){
					echo '<td><button style="background-color:' . $list . ';height:40px;width:100px;" disabled>' . $list . '</button></td>';
				}else{
					echo '<td>'.$list.'</td>';
				}
			}
			echo '<td><button type="button" class="btn btn-outline btn-primary"><a href="/getListing/' . $what . '/edit/'. $listArr[0] .'">Edit</a></button>&nbsp;&nbsp;<button type="button" class="btn btn-outline btn-primary"><a href="/getListing/' . $what . '/delete/'. $listArr[0] .'">Delete</a></button></td>';
		echo '</tr>';
	}
	?>                                
	</tbody>
</table>
<?php
}
?>
<?=_getAdminSetionFooter()?>