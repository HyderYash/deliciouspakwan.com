// This is being USed for nav bar toggle
const toggle = document.querySelector(".toggle");
const menu = document.querySelector(".menu");
const items = document.querySelectorAll(".item");		
	/* Event Listeners */
	toggle.addEventListener("click", toggleMenu, false);
	for (let item of items) {
	  if (item.querySelector(".submenu")) {
		item.addEventListener("click", toggleItem, false);
	  }
	  item.addEventListener("keypress", toggleItem, false);
	}
	document.addEventListener("click", closeSubmenu, false);	

	// This is being used for Defer images
	deferInit();			

function deferInit() {
	var imgDefer = document.getElementsByTagName('img');
	for (var i=0; i<imgDefer.length; i++) {
		if(imgDefer[i].getAttribute('data-src')) {
			imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
		} 
	} 
}
function moveToTop(elemId){
		var elem = document.getElementById(elemId);
		elem.addEventListener('click', function (e) {
			window.scroll({
				top: 0, 
				left: 0, 
				behavior: 'smooth' 
			});
		});	
}
/* Toggle mobile menu */
function toggleMenu() {
  if (menu.classList.contains("active")) {
    menu.classList.remove("active");
    toggle.querySelector("a").innerHTML = "<div class='sprite_bar-nav'></div>";
  } else {
    menu.classList.add("active");
    toggle.querySelector("a").innerHTML = "<div class='sprite_cross'></div>";
  }
}

/* Activate Submenu */
function toggleItem() {
  if (this.classList.contains("submenu-active")) {
    this.classList.remove("submenu-active");
  } else if (menu.querySelector(".submenu-active")) {
    menu.querySelector(".submenu-active").classList.remove("submenu-active");
    this.classList.add("submenu-active");
  } else {
    this.classList.add("submenu-active");
  }
}
/* Close Submenu From Anywhere */
function closeSubmenu(e) {
  let isClickInside = menu.contains(e.target);

  if (!isClickInside && menu.querySelector(".submenu-active")) {
    menu.querySelector(".submenu-active").classList.remove("submenu-active");
  }
}

