<?php
$optionLinksArray = array(
'Show Expenditure' => '/showExpForCategoryByItemsMonthly/'. $catId . '/' . $itemId . '/2?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit' => '/showExpForCategoryByItemsMonthly/'. $catId . '/' . $itemId . '/1?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit & Expenditure' => '/showExpForCategoryByItemsMonthly/'. $catId . '/' . $itemId . '/12?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit, Expenditure & Balance' => '/showExpForCategoryByItemsMonthly/'. $catId . '/' . $itemId . '/123?showItemYear=' . $_SESSION['exp_item_year'],
);
?>
<?php
$displayName = $itemCatName;
if($itemId > 0){
	$displayName = $itemName;
}
?>
<?=_getSectionHeader('Showing.. ' . $displayName . ' in year ' . $_SESSION['exp_item_year'] . ' by Items', 'All TIME STATUS: ' . _getExpDisplayFormat($itemCatTotalDepoAmt, $itemCatTotalExpAmt, ' | ') . '   [  <a href="/showExpForCategoryByAccountsMonthly/' . $catId . '">Show by Account</a>  ]' . _getOptionLinkHtml($optionLinksArray) . '<br><br>ACTUAL TOTAL:&nbsp;&nbsp;' . _getExpDisplayFormat($itemCatTotalDepoAmtActual, $itemCatTotalExpAmtActual, ' | '))?>

<?=_getYearListWithForm('showExpForCategoryByItemsMonthly/' . $catId . '/' . $itemId . '/' . $_SESSION['EXP_DISPLAY_TYPE'])?>
<?php
if($itemCatName != 'ALL CATEGORIES'){
	print _showRemainingLinks($restOfCategories,'showExpForCategoryByItemsMonthly','category');
}
?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '<div id="scrolly">';
?>
<?=_startTable()?>
	<?php if(empty($expForItemCategoryByItemsMonthly)){ ?>
		<?=_getNoDataErrorMsg()?>
	<?php }else{ ?>
		<?=_displayHeaderDataSection($listOfMonthsByExpTypeExpenditure,'Items List','Y',$itemCatName,array('Pay Day','Exp Category','Account'))?>
		<?=_displayMiddleDataSection($expForItemCategoryByItemsMonthly,array(),'Y',$itemCatName, array('showExpForCategoryByItemsMonthly','current_cat_id','showExpForCategoryByAccountsMonthly','cat_id'),array('cat_name','acct_name'))?>
		<?=_displayBottomDataSection('Y',$itemCatName)?>
	<?php }	?>
<?=_endTable()?>
<?php
if(trim($itemCatName) != 'ALL ITEMS'){
	if($itemId > 0){
		print _showRemainingLinks($restOfItems,'showExpForCategoryByItemsMonthly/' . $catId,'item');
	}
}
?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '</div>';
?>
<?=_getSectionFooter()?>