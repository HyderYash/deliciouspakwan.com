<?php
$optionLinksArray = array(
'Show Expenditure' => '/showExpByItemCategoryMonthly/2?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit' => '/showExpByItemCategoryMonthly/1?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit & Expenditure' => '/showExpByItemCategoryMonthly/12?showItemYear=' . $_SESSION['exp_item_year'],
'Show Deposit, Expenditure & Balance' => '/showExpByItemCategoryMonthly/123?showItemYear=' . $_SESSION['exp_item_year'],
);
?>

<?=_getSectionHeader('Showing.. ALL CATEGORIES' . ' in year ' . $_SESSION['exp_item_year'],'All TIME STATUS: ' . _getExpDisplayFormat($catTotalDepoAmt, $catTotalExpAmt, ' | ') . _getOptionLinkHtml($optionLinksArray) . '<br><br>ACTUAL TOTAL:&nbsp;&nbsp;' . _getExpDisplayFormat($catTotalDepoAmtActual, $catTotalExpAmtActual, ' | '))?>

<?=_getYearListWithForm('showExpByItemCategoryMonthly/' . $_SESSION['EXP_DISPLAY_TYPE'])?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '<div id="scrolly">';
?>
<?=_startTable()?>
	<?php if(empty($expByItemCategoryMonthly)){ ?>
		<?=_getNoDataErrorMsg()?>
	<?php }else{ ?>
		<?=_displayHeaderDataSection($listOfMonthsByExpTypeExpenditure,'Exp Categories')?>
		<?=_displayMiddleDataSection($expByItemCategoryMonthly,array('showExpForCategoryByItemsMonthly','cat_id'))?>
		<?=_displayBottomDataSection()?>
	<?php } ?>
<?=_endTable()?>
<?php 
if($_SESSION['exp_item_year'] == 'all')
	print '</div>';
?>
<?=_getSectionFooter()?>