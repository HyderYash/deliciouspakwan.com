<?=_getSectionHeader('Current Winner','HTML TEST')?>

<div class="rTable">
	<div class="rTableRow rTableHeading">
		<div class="rTableHead" style="font-size:36px;height:100px;vertical-align:middle;">Congratulations <?=$drawValArr[1]?></div>
		<div class="rTableHead" style="font-size:36px;height:100px;vertical-align:middle;">Current Prize</div>
	</div>
	<div class="rTableRow" style="">
		<div class="rTableCell"><img src="<?=$drawValArr[2]?>" width="60%"></div>
		<div class="rTableCell" style="font-size:48px;vertical-align:middle;">INR <?=$drawValArr[3]?>.00</div>
		
	</div>
	<br><br>
	<div style="font-size:48px;vertical-align:bottom;border:0px solid green;height:100px;">
		<div><a href="/showPrize?id=<?=$drawValArr[0]?>&prize=<?=$drawValArr[3]?>&round=<?=$drawValArr[4]?>">Accept Your Prize</div>
		<div ></div>

	</div>
</div>



<script>
/*
for(i = 0; i < 10; i++)
{
	document.getElementById("demo").innerHTML = '';
	for(p = 1; p < 4; p++)
	{
		var myVar = setInterval(myTimer(i), 1000);
	}
}



function myTimer(i) {
    document.getElementById("demo").innerHTML = i;
}*/
</script>
<?=_getSectionFooter()?>