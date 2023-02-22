<?=_getSectionHeader('WORD TEST','HTML TEST')?>
<div class="rTable">
	<div class="rTableRow rTableHeading">
		<div class="rTableHead">This is</div>
		<div class="rTableHead">to understand</div>
		<div class="rTableHead">in details.</div>
	</div>
	<?php
	$w = 1;
	foreach ($wordList as $wData)
	{
		?>
		<div class="rTableRow" style="">
			<div class="rTableCell"><?=$w?>. <?=$wData['word_name']?></div>
			<div class="rTableCell"><?=$wData['word_mean']?></div>
			<div class="rTableCell">
				<div class="rTable" style="border:0px solid red !important;">
					<?php
					$i = 1;
					foreach ($wData['sent_info'] as $sData)
					{
						?>
						<div class="rTableRow">
							<div class="rTableCell" style="border:0px solid red !important;border-bottom:1px solid gray !important;"><?=$i?>. <?=str_ireplace($wData['word_name'],"<strong>" . $wData['word_name'] . "</strong>",$sData['sent_text'])?></div>
						</div>
						<?php
						$i++;
					}
					?>
				</div>
			</div>
		</div>
		<?php
		$w++;
	}
	?>
</div>
<?=_getSectionFooter()?>