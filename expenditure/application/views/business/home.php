<div id="outerDiv" style="float:left;width:100%;">
	<div id="headdiv" style="float:left;width:100%;">
		<?php
		for($t = 1; $t <= 10;$t++){
			echo busi_get_ticketBox($t,$ticketBoxData,'showOuterDiv');
		}
		?>
	</div>
	<div id="bodyDiv" style="clear:both;float:left;width:100%;">
		<div id="" style="float:left;margin:0px;padding:0px;width:24%;height:100px;border:1px solid green;">
			<?php
			for($l = 36; $l >= 29;$l--){
				echo busi_get_ticketBox($l,$ticketBoxData);
			}
			?>
		</div>
		<div id="" style="float:left;margin:0px;padding:0px;width:69.5%;border:1px solid green;" align="center">
			Banking Area
		</div>		
		<div id="" style="float:left;margin:0px;padding:0px;width:15%;">
			<?php
			for($r = 11; $r <= 18;$r++){
				echo busi_get_ticketBox($r,$ticketBoxData);
			}
			?>		
		</div>		
	</div>
	<div id="footdiv" style="float:left;width:100%;">
		<?php
		for($f = 28; $f >= 19;$f--){
			echo busi_get_ticketBox($f,$ticketBoxData,'showOuterDiv');
		}
		?>
	</div>	
</div>