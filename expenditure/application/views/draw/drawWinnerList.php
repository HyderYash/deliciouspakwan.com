<?=_getSectionHeader('Prize List','HTML TEST')?>
<div class="rTable">
	<div class="rTableRow rTableHeading">
		<div class="rTableHead" style="font-size:16px;vertical-align:middle;">Name</div>
		<div class="rTableHead" style="font-size:16px;vertical-align:middle;">Prize</div>

	</div>
	<?php
	$w = 1;
	if(isset($showPrizeArr) && count($showPrizeArr) > 0)
	foreach ($showPrizeArr as $wData)
	{
		?>
		<div class="rTableRow" style="">
			<div class="rTableCell" style="font-size:16px;vertical-align:middle;"><?=$wData['name']?></div>
			<div class="rTableCell" style="font-size:16px;vertical-align:middle;"><?=$wData['prize']?></div>

			
		</div>
		<?php
		$w++;
	}
	?>
</div>
<?=_getSectionFooter()?>