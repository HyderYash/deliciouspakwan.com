<?=_getCategoryHeading('Delicious Pakwan Sitemap', 'small','display:flex;padding-left:20px;margin-bottom: -10px !important;','h1')?>
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