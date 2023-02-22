<main>
    <section class="container">
        <div class="site-content">
            <div class="posts">
			<?=_getCategoryHeading('Click Play button to watch video here','big')?>
				<?php
				foreach($showAllVideosDetails as $rec){                            
					echo displayIframeZoominCards($rec);
				}
				?>					
			</div>
			<!-- Comment Section -->
			<section class="ac-container">
				<?php
					echo showCommentsOnVideoDetailsPage($listOfCommentsInVideos);
				?>
			</section>
			<!-- Comment Section -->
        </div>
    </section>			
	<section class="container">
		<div class="site-content">
			<div class="posts">
				<?php
				foreach($recentVideosForVideoPlay as $rec){
					if($video_id != $rec['VIDEO_ID']) {
						echo displayBigZoominCards('no',$rec); 
					}
				}
				?>					
			</div>
			<aside class="sidebar">
			<div class="popular-post" id="showVideos">
				<?=_getCategoryHeading('Our Popular Videos are here','small')?>
				<?php
				if(is_array($mostViewedRandomVideosForVideoPlay) && count($mostViewedRandomVideosForVideoPlay) > 0) {
					foreach($mostViewedRandomVideosForVideoPlay as $rec) {
						echo popularVideosSideBar($rec);
					}
				}else{
					echo '<div style="height: 300px;" class="blog-content" data-aos="fade-right" data-aos-delay="200">';
					echo '<div class="blog-title" style="padding-bottom:14px !important;">Playlists are coming soon...</div>';
					echo '</div>';
				}
				?>
			</div>
			</aside>
		</div>
	</section>
</main>