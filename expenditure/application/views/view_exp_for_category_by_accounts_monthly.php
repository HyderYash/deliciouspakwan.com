<?php
$optionLinksArray = array(
'Show Expenditure' => '/showExpForCategoryByAccountsMonthly/'. $catId . '/' . $itemId . '/2?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit' => '/showExpForCategoryByAccountsMonthly/'. $catId . '/' . $itemId . '/1?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit & Expenditure' => '/showExpForCategoryByAccountsMonthly/'. $catId . '/' . $itemId . '/12?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit, Expenditure & Balance' => '/showExpForCategoryByAccountsMonthly/'. $catId . '/' . $itemId . '/123?showItemYear=' . $_SESSION['exp_item_year'],
);
?>
<?php
$displayName = $itemCatName;
if($itemId > 0){
	$displayName = $itemName;
}
?>
<?=_getSectionHeader('Showing.. ' . $displayName . ' in year ' . $_SESSION['exp_item_year'] . ' by Accounts', 'All TIME STATUS: ' . _getExpDisplayFormat($itemCatTotalDepoAmt, $itemCatTotalExpAmt, ' | ') . '   [  <a href="/showExpForCategoryByItemsMonthly/' . $catId . '">Show by Items</a>  ]' . _getOptionLinkHtml($optionLinksArray) . '<br><br>ACTUAL TOTAL:&nbsp;&nbsp;' . _getExpDisplayFormat($itemCatTotalDepoAmtActual, $itemCatTotalExpAmtActual, ' | '))?>

<?=_getYearListWithForm('showExpForCategoryByAccountsMonthly/' . $catId . '/' . $itemId . '/' . $_SESSION['EXP_DISPLAY_TYPE'])?>
<?php
if(trim($itemCatName) != 'ALL ACCOUNTS'){
	print _showRemainingLinks($restOfCategories,'showExpForCategoryByAccountsMonthly','category');
}
?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '<div id="scrolly">';
?>
<?=_startTable()?>
	<?php if(empty($expForItemCategoryByAccountsMonthly)){ ?>
		<?=_getNoDataErrorMsg()?>
	<?php }else{ ?>
		<?=_displayHeaderDataSection($listOfMonthsByExpTypeExpenditure,'Account Type')?>
		<?=_displayMiddleDataSection($expForItemCategoryByAccountsMonthly,array('showExpForAccountByItemsMonthly','acct_id'))?>
		<?=_displayBottomDataSection()?>
	<?php }	?>
<?=_endTable()?>
<?php
if(trim($itemCatName) != 'ALL ITEMS'){
	if($itemId > 0){
		print _showRemainingLinks($restOfItems,'showExpForCategoryByAccountsMonthly/' . $catId,'item');
	}
}
?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '</div>';
?>
<?=_getSectionFooter()?>