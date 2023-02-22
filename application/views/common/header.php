<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$_SESSION['DP_META_TITLE']?></title>
	<meta name="description" content="<?=$_SESSION['DP_META_DESC']?>" />
	<meta name="keywords" content="<?=$_SESSION['DP_META_KEYWORDS']?>" />
	<meta name="robots" content="INDEX, FOLLOW">	
	<meta name="theme-color" content="#317EFB"/>
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="canonical" href="<?=$_SESSION['DP_CANONICAL_URL']?>" />	
        <?php
		$requiredCss = array('style.css','all_support_css.css','nav_bar.css');
		echo "<style>";
		for($i = 0; $i < count($requiredCss);$i++){
			
			$buffer = file_get_contents(HTDOCS_PATH . "/css/" . $requiredCss[$i]);
			// Remove comments
			$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
			// Remove space after colons
			$buffer = str_replace(': ', ':', $buffer);
			// Remove whitespace
			$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
			// Write everything out			
			echo $buffer;
		}
        echo "</style>";
		?>

    <?php 	//if(DEVICETYPE == 'DESKTOP' && ENV_NAME  == 'www') {		?>
    <!--<script data-ad-client="ca-pub-1068223879020616" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script-->
    <?php 	//}	?>

</head>
<body>
<section class="fixed-header">
<nav>
  <ul class="menu">
    <li class="logo"><a href="/">Delicious Pakwan</a></li>
    <li class="item"><a href="/">Home</a></li>
    <li class="item"><a href="/playlists">Top Playlists</a></li>
	<li class="item"><a href="/sitemap.html">Sitemap</a></li>
    <!--li class="item has-submenu">
      <a tabindex="0">Services</a>
      <ul class="submenu">
        <li class="subitem"><a href="#">Design</a></li>
        <li class="subitem"><a href="#">Development</a></li>
        <li class="subitem"><a href="#">SEO</a></li>
        <li class="subitem"><a href="#">Copywriting</a></li>
      </ul>
    </!--li>
    <li class="item has-submenu">
      <a tabindex="0">Plans</a>
      <ul class="submenu">
        <li class="subitem"><a href="#">Freelancer</a></li>
        <li class="subitem"><a href="#">Startup</a></li>
        <li class="subitem"><a href="#">Enterprise</a></li>
      </ul>
    </li>
    <li class="item"><a href="#">Blog</a></li>
    <li class="item"><a href="#">Contact</a>
    </li>
    <li class="item button"><a href="#">Log In</a></li>
    <li-- class="item button secondary"><a href="#">Sign Up</a></li-->
    <li class="toggle"><a href="#"><div class="sprite_bar-nav"></div></a></li>
  </ul>
</nav>
</section>
