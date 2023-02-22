<?=_getCategoryHeading('Whoops! We can\'t seem to find the page you were looking for!! </br>You can still see below Delicious Recipes.', 'small','display:flex;padding-left:20px;padding-top: 20px;')?>
<div class="sitemap_main_div">
<?php
	echo '<div class="sitemap_div_section_css">';
		echo '<ul class="category-list">';
		$count = count($sitemapContentArr);
		$i = 0;
		foreach($sitemapContentArr as $rec){ 
			echo '<li class="sitemap_li_class"><a href="' . $rec['SITE_URL'] . '" title="' . $rec['SITEMAP_LABEL'] . '">' . $rec['SITEMAP_LABEL'] . '</a></li>';
			if($i == round((($count/3)*2))){
					echo '</ul>';
				echo '</div>';
				echo '<div class="sitemap_div_section_css">';
					echo '<ul class="category-list">';
			}else{		
				if($i == round(($count/3))){
						echo '</ul>';
					echo '</div>';
					echo '<div class="sitemap_div_section_css">';
						echo '<ul class="category-list">';
				}
			}
			$i++;
		}
		echo '</ul>';
	echo '</div>';
?>
</div>