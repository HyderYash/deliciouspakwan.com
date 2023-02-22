<main>
	<section class="container">
		<div class="site-content">
			<div class="posts">
				<?=_getCategoryHeading($currentPlaylistName . ' Playlist')?>
				<?php
				if(is_array($listOfVideosInPlaylist) && count($listOfVideosInPlaylist) > 0) {
					$i = 0;
					foreach($listOfVideosInPlaylist as $rec){	
						if($rec['VIDEO_ID'] ==  $selectedVideoId){
							echo displayIframeZoominCards($rec,'PlaylistVideos');
						}
						$i++;
					}
				}
				?>					
			</div>
			<!-- This Section is being used for Comments -->
				<section class="ac-container">
				<?php
					echo showCommentsOnVideoDetailsPage($listOfCommentsInVideos);
				?>
				</div>
				</section>
			<!--*****************************************-->
		</div>	
	</section>		
	<section class="container">
		<div class="site-content">
			<div class="posts">
				<?php
				if(is_array($listOfVideosInPlaylist) && count($listOfVideosInPlaylist) > 0) {
					$i = 0;
					foreach($listOfVideosInPlaylist as $rec){
							if($rec['VIDEO_ID'] !=  $selectedVideoId) {	
								echo displayBigZoominCards('no',$rec,'PlaylistVideos');
						}
						$i++;
					}
				}else{
					echo '<div style="height: 300px;" class="blog-content" data-aos="fade-right" data-aos-delay="200">';
					echo '<div class="blog-title" style="padding-bottom:14px !important;">Playlists Videos are coming soon...</div>';
					echo '</div>';
				}
				?>					
			</div>
			<aside class="sidebar">
				<div class="popular-post" id="showVideos">
					<?=_getCategoryHeading('Other Playlists', 'small')?>
				<?php
					if(is_array($listOfPlaylist) && count($listOfPlaylist) > 0) {
						foreach($listOfPlaylist as $rec) {
							if($rec['PLAYLIST_TITLE'] !== $currentPlaylistName)
								echo popularPlaylistsSideBar($rec);
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