<?=_getSectionHeader('QUES ANS','HTML TEST')?>
<div class="rTable">
	<div class="rTableRow rTableHeading">
		<div class="rTableHead">Sl</div>
		<div class="rTableHead">Question</div>
		<div class="rTableHead">Answer</div>
	</div>
	<?php
	$q = 1;
	foreach ($quesList as $qData)
	{
		?>
		<div class="rTableRow" style="">
			<div class="rTableCell"><?=$q?>.</div>
			<div class="rTableCell"><?=$qData['COM_QUES']?></div>
			<div class="rTableCell"><?=$qData['COM_ANS']?></div>
		</div>
		<?php
		$q++;
	}
	?>
</div>
<?=_getSectionFooter()?>