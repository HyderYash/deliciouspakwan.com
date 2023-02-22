<?php
$optionLinksArray = array(
'Show Expenditure' => '/showExpForAccountByItemsMonthly/'. $acctId . '/' . $itemId . '/2?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit' => '/showExpForAccountByItemsMonthly/'. $acctId . '/' . $itemId . '/1?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit & Expenditure' => '/showExpForAccountByItemsMonthly/'. $acctId . '/' . $itemId . '/12?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit, Expenditure & Balance' => '/showExpForAccountByItemsMonthly/'. $acctId . '/' . $itemId . '/123?showItemYear=' . $_SESSION['exp_item_year'],
);
?>
<?php
$displayName = $acctName;
if($itemId > 0){
	$displayName = $itemName;
}
?>
<?=_getSectionHeader('Showing.. ' . $displayName . ' in Year ' . $_SESSION['exp_item_year'],'All TIME STATUS: ' . _getExpDisplayFormat($acctTotalDepoAmt, $acctTotalExpAmt, ' | ') . _getOptionLinkHtml($optionLinksArray) . '<br><br>ACTUAL TOTAL:&nbsp;&nbsp;' . _getExpDisplayFormat($acctTotalDepoAmtActual, $acctTotalExpAmtActual, ' | '))?>

<?=_getYearListWithForm('showExpForAccountByItemsMonthly/' . $acctId . '/' . $itemId . '/' . $_SESSION['EXP_DISPLAY_TYPE'])?>
<?php
if(trim($acctName) != 'ALL ITEMS'){
	print _showRemainingLinks($restOfAccounts,'showExpForAccountByItemsMonthly','account');
}
?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '<div id="scrolly">';
?>
<?=_startTable()?>
	<?php if(empty($expForAccountByItemsMonthly)){ ?>
		<?=_getNoDataErrorMsg()?>
	<?php }else{ ?>
		<?=_displayHeaderDataSection($listOfMonthsByExpTypeExpenditure,'Items List','Y',$acctName,array('Pay Day','Exp Category','Account'))?>
		<?=_displayMiddleDataSection($expForAccountByItemsMonthly,array(),'Y',$acctName, array('showExpForCategoryByItemsMonthly','cat_id','showExpForAccountByItemsMonthly','acct_id',),array('cat_name','acct_name'))?>
		<?=_displayBottomDataSection('Y',$acctName)?>
	<?php }	?>
<?=_endTable()?>
<?php
if(trim($acctName) != 'ALL ITEMS'){
	if($itemId > 0){
		print _showRemainingLinks($restOfItems,'showExpForAccountByItemsMonthly/' . $acctId,'item');
	}
}
?>	
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '</div>';
?>
<?=_getSectionFooter()?>