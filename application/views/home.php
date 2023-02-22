<style>
#nextSlide {
	 padding: 1em;
	 font-weight: bold;
	 text-transform: uppercase;
	 text-decoration: none;
	 color: #000;
	 display: inline-block;
	 margin: 2em auto;
	 text-align: center;
	 transition: 0.3s;
	 border: 2px solid #000;
}
 #nextSlide:hover {
	 background: #ccc;
}
 p {
	 color: #fff;
	 font-weight: bold;
}
.textSlider {
	 width: 100%;
	 min-height: 100px;
	 position: relative;
	 overflow: hidden;
	 background: #eee;
	 border: 2px solid #ccc;
}
.textSlider .slide {
	 display: flex;
	 flex-direction: column;
	 min-width: 100%;
	 position: relative;
}
.textSlider .slide:nth-of-type(3n+1) p:nth-of-type(3n+1) {
	 background: #046380;
}
.textSlider .slide:nth-of-type(3n+1) p:nth-of-type(3n+2) {
	 background: #a7a37e;
}
.textSlider .slide:nth-of-type(3n+1) p:nth-of-type(3n+3) {
	 background: #002f2f;
}
.textSlider .slide:nth-of-type(3n+2) p:nth-of-type(3n+1) {
	 background: #8e2800;
}
.textSlider .slide:nth-of-type(3n+2) p:nth-of-type(3n+2) {
	 background: #b64926;
}
.textSlider .slide:nth-of-type(3n+2) p:nth-of-type(3n+3) {
	 background: #ffb03b;
}
.textSlider .slide:nth-of-type(3n+3) p:nth-of-type(3n+1) {
	 background: #00a388;
}
.textSlider .slide:nth-of-type(3n+3) p:nth-of-type(3n+2) {
	 background: #79bd8f;
}
.textSlider .slide:nth-of-type(3n+3) p:nth-of-type(3n+3) {
	 background: #beeb9f;
}
.textSlider .slide.active p {
	 transform: translateX(0%);
}
.textSlider .slide.out p {
	 transform: translateX(-100%);
}
.textSlider .slide p {
	 display: flex;
	 align-items: center;
	 margin: 0;
	 padding: 0.3em;
	 flex: 1;
	 transform-origin: 0 0;
	 transform: translateX(100%);
	 transition: 0.3s;
}
.textSlider .slide p:nth-of-type(5n+1) {
	 transition: 1s;
}
.textSlider .slide p:nth-of-type(5n+2) {
	 transition: 0.5s;
}
.textSlider .slide p:nth-of-type(5n+3) {
	 transition: 1.5s;
}
.textSlider .slide p:nth-of-type(5n+4) {
	 transition: 0.75s;
}
.textSlider .slide p:nth-of-type(5n+5) {
	 transition: 1.25s;
}
 
</style>
<main>
	<!-- Display Slider card -- Recent Videos --  Start here -->
		<?=displaySliderCardForRecentVideos($recentVideos)?>
	<!-- Display Slider card -- Recent Videos --  End here -->
	
	<!-- Google ads will display here -->
		<!--div class="ads-main-container">
			<div class="ads-size-horizontal-big">
			Yash<br>

			</div>
		</div-->
	<!-- Google ads will display here -->
	
	<section class="container mobileclass">
		<div class="site-content">
			<div class="posts extra-top-mar">
				<?=_getCategoryHeading('Watch Top Videos Here')?>
				<?php
				$i = 0;
				$midAgedVideosCount = count($midAgedVideos);
				foreach($midAgedVideos as $rec){
					if($i == 1){
						if(count($dpCookingTipsSection) > 0){
							$tmp = '';
							$tmp .= _getCategoryHeading($dpCookingTipsSection[0]['TIPS_CATEGORY'],'big','margin-top: 0px !important;');
							$tmp .= '<div class="post-content" style="padding: 0.5rem 2rem;">';
								$tmp .= '<div class="textSlider">';
									$tipCount = 1;
									$tmp .= '<div class="slide active">';
										foreach($dpCookingTipsSection as $tips){
											$tmp .= '<p><a style="color:#fff !important;cursor:hand;" href="' . $tips['TIPS_LINK'] . '" title="' . $tips['TIPS_TITLE'] . '" target="_blank">' . $tips['TIPS_TITLE'] . '</a></p>';
											if($tipCount > 3){
												//$tmp .= '</div><div class="slide">';
												$tipCount = 1;
											}
											$tipCount++;
										}
									$tmp .= '</div>';
									$tmp = str_replace('<div class="slide"></div>','', $tmp);
								$tmp .= '</div>';
								//<a href="#" id="nextSlide">Next Text Slide</a>
							$tmp .= '</div>';	
							echo $tmp . _getHrLine();
						}
					}
					if($i == ($midAgedVideosCount - 1)){
						if(count($dpKitchenTipsSection) > 0){
							$tmp = '';
							$tmp .= _getCategoryHeading($dpKitchenTipsSection[0]['TIPS_CATEGORY'],'big','margin-top: 0px !important;');
							$tmp .= '<div class="post-content" style="padding: 0.5rem 2rem;">';
								$tmp .= '<div class="textSlider">';
									$tipCount = 1;
									$tmp .= '<div class="slide active">';
										foreach($dpKitchenTipsSection as $tips){
											$tmp .= '<p><a style="color:#fff !important;cursor:hand;" href="' . $tips['TIPS_LINK'] . '" title="' . $tips['TIPS_TITLE'] . '" target="_blank">' . $tips['TIPS_TITLE'] . '</a></p>';
											if($tipCount > 3){
												//$tmp .= '</div><div class="slide">';
												$tipCount = 1;
											}
											$tipCount++;
										}
									$tmp .= '</div>';
									$tmp = str_replace('<div class="slide"></div>','', $tmp);
								$tmp .= '</div>';
							$tmp .= '</div>';	
							echo $tmp . _getHrLine();
						}
					}
					echo displayBigZoominCards('no',$rec);					
					$i++;
				}
				?>					
			</div>
			<aside class="sidebar">
				<div class="category">
					<?=_getCategoryHeading('Our Top Playlists', 'small')?>
					<ul class="category-list">
						<?php
						if(is_array($listOfPlaylist) && count($listOfPlaylist) > 0) {
							echo _getListOfPlaylists($listOfPlaylist,$listOfNutritionFacts);
						}
						?>
					</ul>
				</div>

				<div class="popular-post" id="showVideos">
					<?=_getCategoryHeading('Our Popular Videos', 'small')?>
					<?php
					foreach($mostViewedRandomVideos as $rec){
						echo popularVideosSideBar($rec);
					}
					?>
				</div>
			</aside>
		</div>
	</section>
</main>
<script type="text/javascript">

</script>
