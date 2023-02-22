    <footer class="footer">
        <div class="container justify-content-center">
            <div class="about-us">
                <h2 class="footer_h2">About us</h2>
                <p style="color:#fff;">Hello Friends,
                This is my Cooking Channel named Delicious Pakwan. Here I upload Easy and Delicious recipes that are easy to make at home. Please share your thought on which video should be next. Please do Like, Subscribe, Share, and Comment.</p>
            </div>
            <div class="newsletter">
                <h2 class="footer_h2">Subscription Link</h2>
                <p style="color:#fff;">Please click below button to subscribe our channel on YouTube</p>
				<a href="https://www.youtube.com/c/DeliciousPakwan?sub_confirmation=1" target="_blank"><button class="visitor_btn"><span>Subscribe</button></span></a>
            </div>
            <div class="follow">
                <h2 class="footer_h2">Follow us on</h2>
                <p><a style="color:yellow;" href="https://www.facebook.com/deliciouspakwan" target="_blank">Facebook</a></p>
				<p><a style="color:yellow;" href="https://www.instagram.com/deliciouspakwan" target="_blank">Instagram</a></p>
				<p><a style="color:yellow;" href="https://twitter.com/PakwanDelicious" target="_blank">Twitter</a></p>
				<p><a style="color:yellow;" href="https://bit.ly/3gJY3mQ" target="_blank">Youtube</a></p>
                <div>
                    <a title="See my Facebook page" href="https://www.facebook.com/Delicious-Pakwan-109586477431858" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a title="Watch my channel on Youtube" href="https://www.youtube.com/c/DeliciousPakwan?sub_confirmation=1" target="_blank"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="newsletter">
                <h2 class="footer_h2">Visit Counter</h2>
				<button class="visitor_btn"><span><?=$_SESSION['showVisitorCount']?></span></button>
            </div>
        </div>
		<?=_getMoveToTopButton(5555)?>
    </footer>
<script type="text/javascript">
	if(location.pathname == '/' || location.pathname == '/playlists'){
		var allJsInclude = 'yes';
	}else{
		var allJsInclude = '';
	}
	window.addEventListener("DOMContentLoaded", downloadSiteCustomJS, false);
		function downloadSiteCustomJS() {
			if(allJsInclude == 'yes'){
				var jSelement = document.createElement("script"); jSelement.src = "/js/all_support_js.js";  document.getElementsByTagName('head')[0].appendChild(jSelement);
			}
		}
		
	window.addEventListener("load", downloadSupportJS, false);
	function downloadSupportJS() {
		var jSelement = document.createElement("script"); jSelement.src = "/js/main.js";  document.getElementsByTagName('head')[0].appendChild(jSelement);
	}
</script>  
</body>
</html>