<?php
if (!isset($_SESSION)) { 
  session_start();
}
function _getSectionHeader($sectionTitle='',$sectionDesc='',$pageStyle='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$themeInfo = $CI->sqlModel->getActiveThemeInfo();
	$temp = '';
	$temp = $themeInfo['THEME_HEADER_HTML'];
	$temp = str_replace('[SECTION_TITLE]',$sectionTitle,$temp);
	$temp = str_replace('[SECTION_DESC]',$sectionDesc,$temp);
	$temp = str_replace('[PAGE_STYLE]',$pageStyle,$temp);
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);	
	return $temp;
}
function _getSectionFooter($sectionFootDesc='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$themeInfo = $CI->sqlModel->getActiveThemeInfo();	
	$temp = '';
	$temp = $themeInfo['THEME_FOOTER_HTML'];
	if($sectionFootDesc=='')
	{
		$sectionFootDesc = 'Created on <strong>' . date("d-m-Y") . ' ' . date("h:i:sa") . '</strong>';
	}	
	$temp = str_replace('[SECTION_FOOT_DESC]',$sectionFootDesc,$temp);
	$CI->sqlModel->_getExecutionTime($funcId);
	return $temp;		
}

function _getSectionHeader1($sectionTitle='',$sectionDesc='',$pageStyle='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	$temp .= '<div class="container" style="' . $pageStyle . '">
				<h2>' . $sectionTitle . '</h2>
				<div class="container-body">
					<p>' . $sectionDesc . '</p>
					';
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);				
	return $temp;
}
function _getSectionFooter1($sectionFootDesc='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	if($sectionFootDesc=='')
	{
		$sectionFootDesc = 'Created on <strong>' . date("d-m-Y") . ' ' . date("h:i:sa") . '</strong>';
	}
	$temp .= ' 			
				</div>
				<p class="footer">' . $sectionFootDesc . '</p>
			</div>';
	$CI->sqlModel->_getExecutionTime($funcId);		
	return $temp;		
}

function _getSectionHeader2($sectionTitle='',$sectionDesc='',$pageStyle='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	$temp .= '<div class="floatLeft">
				<div class="sticker" style="width: 97%;">
					<div class="stickerHeading titillium"><h1>' . $sectionTitle . '</h1></div>
					<p>' . $sectionDesc . '</p>';
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);				
	return $temp;
}
function _getSectionFooter2($sectionFootDesc='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$temp = '';
	if($sectionFootDesc=='')
	{
		$sectionFootDesc = 'Created on <strong>' . date("d-m-Y") . ' ' . date("h:i:sa") . '</strong>';
	}
	$temp .= '		<p class="footer">' . $sectionFootDesc . '</p>
			</div></div>';
	$CI->sqlModel->_getExecutionTime($funcId);
	return $temp;		
}
function _getYearListWithForm($locationpath='',$formname='exp_item_form', $selectname='expitemyear',$jsfunc='showThisYearData',$yrsessname='exp_item_year')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$yearListArr = array();
	for($i = 2017; $i <= CURRENT_YEAR; $i++)
	{
		$yearListArr[] = array('year' => $i);
	}
	$tmp = '';
	
	$tmp .= '<div style="float:left;"><form name="' .  $formname . '" id="' .  $formname . '" action="" method="post">
		<select id="' .  $selectname . '" name="' .  $selectname . '" onchange="javascript:' .  $jsfunc . '(this.value,\'' .$locationpath . '\');">';
			$tmp .= '<option value="all">Show All</option>';
			foreach ($yearListArr as $yearkey => $yearval)
			{
				$tmp .= '<option value="' . $yearval['year'] . '"';
				if($yearval['year'] == $_SESSION['exp_item_year']) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $yearval['year'] . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form></div>';	
	$CI->sqlModel->_getExecutionTime($funcId,$locationpath);
	return $tmp;
}
function _showRemainingLinks($result,$href='',$what='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$tmp = '';
	if($what == 'item'){
		$tmp .= '<div style="clear:both;"></div>';
		$tmp .= '<div style="width:100%;margin-top:10px;">';
		$limit = 5;
	}else{
		$limit = 60;
		$tmp .= '<div style="float:right;">';
	}
	if(count($result) > 0)
	{
		$i=0;
		foreach ($result as $linkList)
		{	
			if($i > $limit)
			{
				$tmp .= '<div style="float:left:width:100%;">&nbsp;</div>';$i = 0;
			}
			$tmp .= '<span style="padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;background-color:' . $linkList["BG_COLOR"] . ';font-color:' . $linkList["FONT_COLOR"] . ';font-size: 1.17em;font-weight: bold;text-shadow: 2px 2px 4px #ccc;">';
			if($what == 'account')
				$tmp .= '<a href="/' . $href . '/' . $linkList['ID'] . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $linkList["EXP_ACCOUNT"]; 
			if($what == 'category')
				$tmp .= '<a href="/' . $href . '/' . $linkList['ID'] . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $linkList["EXP_ITEM_CAT"]; 
			if($what == 'item')
				$tmp .= '<a href="/' . $href . '/' . $linkList['ITEM_ID'] . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $linkList["EXP_ITEM_NAME"];		
			$tmp .= '</a></span>';
			
			$i++;
		}
	}
	$tmp .= '</div>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}
function _getNoDataErrorMsg ($msg='NO DATA')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmp .= '<div class="rTableRow rTableHeading">';
			$tmp .= '<div class="rTableHead" align="center"><span class="no-data-error">' . $msg . '</span></div>';
	$tmp .= '</div>';
	$CI->sqlModel->_getExecutionTime($funcId,$msg);	
	return $tmp;
}
function _getExpDisplayFormat($depo,$exp,$separator = "<br>")
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$show = $_SESSION['EXP_DISPLAY_TYPE'];
	$depoStr = '<span style="font-weight:bold;font-size:12px;color:green;">' . number_format($depo,2) . '</span>';
	$expStr = '<span style="font-weight:bold;font-size:12px;color:navy;">' . number_format($exp,2) . '</span>';
	$balStr = '<span style="font-weight:bold;font-size:12px;color:red;">' . number_format(($depo - $exp),2) . '</span>';

	if($show == 1){
		$tmp .= $depoStr;
	}elseif($show == 2){
		$tmp .= $expStr;
	}elseif($show == 3){
		$tmp .= $balStr;
	}elseif($show == 12){
		$tmp .= $depoStr . $separator . $expStr;
	}elseif($show == 123){
		$tmp .= $depoStr . $separator . $expStr . $separator . $balStr;
	}else{
		$tmp .= $expStr;
	}
	$CI->sqlModel->_getExecutionTime($funcId,$show);
	return $tmp;
	
}
function _getOptionLinkHtml($arr)
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$activeDivStyle = '"background: #823874;border-radius: 30px;clear: both;color: #fff;font-size: 14px;font-weight: bold;margin: 0 auto;text-align: center;width: 280px;cursor: pointer;text-decoration: none;padding:10px;margin-left:15px;"';
		
	foreach ($arr as $key => $val)
	{
		$explodedArr = explode('?', $val);
		$exploded = explode('/', $explodedArr[0]);
		//print_r($exploded);
		$option_id = end($exploded);
		if(isset($_SESSION['EXP_DISPLAY_TYPE']) && $option_id == $_SESSION['EXP_DISPLAY_TYPE']){
			$tmp .= '<span style=' . $activeDivStyle . '><a style="color:#fff;" href="' . $val . '">' . $key . '</a></span>';
		}
		else{
			$tmp .= '<span style="padding:10px;margin-left:15px;"><a href="' . $val . '">' . $key . '</a></span>';
		}
		
	}
	$CI->sqlModel->_getExecutionTime($funcId,'',$arr);
	return $tmp;
	
}
function _displayHeaderDataSection($monthArr,$mainColumnHeading='',$allCase='N', $what='', $mainColumnHeadingArr=array())
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	//$tmp = '<div id="scrolly">';
	$tmp .= '<div class="rTableRow rTableHeading">';
		$tmp .= '<div class="rTableHead">SL</div>';
		$tmp .= '<div class="rTableHead">' . $mainColumnHeading . '</div>';
		if($allCase == 'Y')	{
			if($what == 'ALL ITEMS'){
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[0] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[1] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[2] . '</div>';
			}else{
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[0] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[1] . '</div>';
				$tmp .= '<div class="rTableHead">' . $mainColumnHeadingArr[2] . '</div>';
			}
		}
		foreach ($monthArr as $monYearData){
			foreach ($monYearData as $monData){
				$tmp .= '<div class="rTableHead" style="background-color:#B6DDE8;"><a href="/showMonthlyStatusCurrent/?showMonth='. $monData['month_number'] . '-' . $monData['year'] . '">' . $monData['month_name'] . " " . $monData['year'] . '</a></div>';
			}
		}
		$tmp .= '<div class="rTableHead">G. Total</div>';
	$tmp .= '</div>';
	$CI->sqlModel->_getExecutionTime($funcId,$what,$monthArr);
	return $tmp;
}
function _displayMiddleDataSection($dataArr,$linkArr=array(),$allCase='N', $what='', $middleLinkArr=array(),$middleLinkLabelArr=array())
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmArr = array();
	foreach ($dataArr as $etDataKey => $etDataVal){
		$cnt = count($etDataVal['exp_details']);
		for($c = 0; $c < $cnt; $c++){
			$tmArr[$etDataVal['exp_details'][$c]['month_name'] . $etDataVal['exp_details'][$c]['year']] = array('EXP' => 0, 'DEPO' => 0);
		}
	}
	$sl = 0;
	foreach ($dataArr as $etDataKey => $etDataVal){
		$sl++;
		if(isset($etDataVal['item_status'])){
			$curr_item_id = $etDataVal['item_id'];
		}else{
			$curr_item_id = 0;
		}
		if(isset($etDataVal['item_status']) && $etDataVal['item_status'] == 'Y'){
			$tmpBgColor = '#000';$tmpFontColor = '#fff !important';$strikeText = '<strike>' . $etDataKey . '</strike> EXPIRED';
		}else{
			$tmpBgColor = $etDataVal['bg_color'];$tmpFontColor = $etDataVal['font_color'];
			if($allCase == 'Y')	{
				$strikeText = '<a href="/' . $middleLinkArr[0] . '/' .  $etDataVal[$middleLinkArr[1]] . '/' . $curr_item_id  . '?showItemYear=' . $_SESSION['exp_item_year'] . '">' . $etDataKey . '</a>';
			}else{
				$strikeText = '<a href="/' . $linkArr[0] . '/' .  $etDataVal[$linkArr[1]] . '/' . $curr_item_id  . '">' . $etDataKey . '</a>';
			}
		}
		$tmp .= '<div class="rTableRow" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">';
			$tmp .= '<div class="rTableCell" style="width:10px !important;">' . $sl . '</div>';
			if($allCase == 'Y')	{
				$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . $strikeText . '</div>';
				$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . $etDataVal['pay_day'] . '</div>';
				if($what == 'ALL ITEMS'){
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[0] . '/' . $etDataVal[$middleLinkArr[1]] . '">' . $etDataVal[$middleLinkLabelArr[0]] . '</a></div>';
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[2] . '/' . $etDataVal[$middleLinkArr[3]] . '">' . $etDataVal[$middleLinkLabelArr[1]] . '</a></div>';
				}else{
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[0] . '/' . $etDataVal[$middleLinkArr[1]] . '">' . $etDataVal[$middleLinkLabelArr[0]] . '</a></div>';
					$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $middleLinkArr[2] . '/' . $etDataVal[$middleLinkArr[3]] . '">' . $etDataVal[$middleLinkLabelArr[1]] . '</a></div>';
				}
			}else{
				$tmp .= '<div class="rTableCell" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';"><a href="/' . $linkArr[0] . '/' . $etDataVal[$linkArr[1]] . '">' . $etDataKey . '</a></div>';
			}
			$exp_total = 0;
			$depo_total = 0;
			foreach ($etDataVal['exp_details'] as $eTmonData){
				foreach($tmArr as $tmArrKey => $tmArrVal){
					if($tmArrKey == trim($eTmonData['month_name'] . $eTmonData['year'])){
						$tmArr[$tmArrKey]['EXP'] += $eTmonData['exp_amt'];
						$tmArr[$tmArrKey]['DEPO'] += $eTmonData['depo_amt'];
					}
				}
				$exp_total += $eTmonData['exp_amt'];
				$depo_total += $eTmonData['depo_amt'];
				$tmp .= '<div class="rTableCell" align="right" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . _getExpDisplayFormat($eTmonData['depo_amt'], $eTmonData['exp_amt']) . '</div>';
			}
			$tmp .= '<div class="rTableCell" align="right" style="background-color:' . $tmpBgColor . ';color:' . $tmpFontColor . ';">' . _getExpDisplayFormat($depo_total, $exp_total) . '</div>';
		$tmp .= '</div>';
	}
	/***Storing $tmArr to SESSION for use later **/
	$_SESSION['tmArr'] = $tmArr;
	$CI->sqlModel->_getExecutionTime($funcId,$what,$linkArr);
	return $tmp;	
}

function _displayBottomDataSection($allCase='N', $what='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmArr = $_SESSION['tmArr'];
	$tmp = '';
		$tmp .= '<div class="rTableRow rTableFoot">';
			$tmp .= '<div class="rTableCell">&nbsp;</div>';
			$tmp .= '<div class="rTableCell">Total</div>';
			if($allCase == 'Y')	{
				$tmp .= '<div class="rTableCell">&nbsp;</div>';
				$tmp .= '<div class="rTableCell">&nbsp;</div>';				
				if($what == 'ALL ITEMS'){
					$tmp .= '<div class="rTableCell">&nbsp;</div>';	
				}else{
					$tmp .= '<div class="rTableCell">&nbsp;</div>';
				}	
			}
			$exp_total = 0;
			$depo_total = 0;
			foreach($tmArr as $tmArrKey => $tmArrVal){
				$exp_total += $tmArr[$tmArrKey]['EXP'];
				$depo_total += $tmArr[$tmArrKey]['DEPO'];
				$tmp .= '<div class="rTableCell" align="right">' . _getExpDisplayFormat($tmArr[$tmArrKey]['DEPO'], $tmArr[$tmArrKey]['EXP']) . '</div>';
			}
			$tmp .= '<div class="rTableCell" align="right">' . _getExpDisplayFormat($depo_total, $exp_total) . '</div>';
		$tmp .= '</div>';
		//$tmp .= '</div>';//Scrolly Div End
		$CI->sqlModel->_getExecutionTime($funcId,$what);
	return $tmp;
}
function _startTable()
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmp .= '<div class="rTable">';
	$CI->sqlModel->_getExecutionTime($funcId);
	return $tmp; 
}
function _endTable()
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$tmp .= '</div>';
	$CI->sqlModel->_getExecutionTime($funcId);
	return $tmp; 
}

/****************AdminPanel HTML Helper functions Starts***************/ 
function _getAdminSectionHeader($sectionTitle='',$panelTitle='',$msg='')
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
	$temp = '';
    $temp .= '<div id="page-wrapper">';
    $temp .= '   <div class="row">';
    $temp .= '       <div class="col-lg-12">';
    $temp .= '            <h1 class="page-header">' . $sectionTitle . '</h1>';
    $temp .= '       </div>';
    $temp .= '       <!-- /.col-lg-12 -->';
    $temp .= '  </div>';

    $temp .= '        <div class="row">';
    $temp .= '            <div class="col-lg-12">';
    $temp .= '                <div class="panel panel-default">';
    $temp .= '                    <div class="panel-heading">' . $panelTitle;
									if (isset($msg) && $msg != '') { 
	$temp .= '					 	 <div class="alert alert-success alert-dismissable">';
    $temp .= '                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    $temp .=                             $msg;
    $temp .= '                          </div>';
									}                        
    $temp .= '                    </div>';
    $temp .= '                   <!-- /.panel-heading -->';

	$temp .= '<form id="postDataForm" method="post" action="/postData">';
	$temp .= '	<div class="panel-body">';	
	$CI->sqlModel->_getExecutionTime($funcId,$sectionTitle);	
	return $temp;
}
function _getAdminSetionFooter()
{
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);
    $temp = '';

	$temp .= '<!-- /.table-responsive -->';
	$temp .= '</div>	<!-- /.panel-body -->';
	$temp .= '</form>';	
	$temp .= '				</div>';
    $temp .= '                <!-- /.panel -->';
    $temp .= '            </div>';
    $temp .= '            <!-- /.col-lg-12 -->';
    $temp .= '        </div>';
    $temp .= '        <!-- /.row -->';
    $temp .= '            <!-- /.col-lg-6 -->';
    $temp .= '        </div>';
    $temp .= '        <!-- /.row -->';
    $temp .= '    </div>';
    $temp .= '    <!-- / page-wrapper -->';	
	$CI->sqlModel->_getExecutionTime($funcId,'');	
	return $temp;	
}
function _getSelectCombo($result,$selectName,$selectedId,$action){
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	
	$tmp .= '<select id="' .  $selectName . '" name="' .  $selectName . '"';
	if($action == 'delete'){$tmp .= ' disabled';}
	$tmp .= '>';
			foreach ($result as $rec)
			{
				$tmp .= '<option value="' . $rec['ID'] . '"';
				if($rec['ID'] == $selectedId) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $rec['selectItem'] . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}
function _getSelectColorCombo($result,$selectName,$selectedId,$action){
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	$tmp = '';
	$colorKey = $result[0]['selectItem'];
	if($selectedId > 0)
	{
		foreach ($result as $rec)
		{
			
			if($rec['ID'] == $selectedId) 
			{
				$colorKey = $rec['selectItem'];break;
			}
			
		}
	}//print'<pre>';print_r($result);die;
	$tmp .= '<select ' . $colorKey . ' name="' .  $selectName . '"';
	if($action == 'delete'){$tmp .= ' disabled';}
	$tmp .= ' id="' .  $selectName . '" onchange="colourFunction()">';
			foreach ($result as $rec)
			{
				$tmp .= '<option ' . $rec['selectItem'] . ' value="' . $rec['ID'] . '"';
				if($rec['ID'] == $selectedId) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $rec['selectItemText'] . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}
function _getActiveSelectCombo($selectName,$selectedId,$action,$num=''){
	$CI =& get_instance();
	$funcId = $CI->sqlModel->_setFunctionHistory(__method__);	
	if($num == 'number')
	{
		$result = array();
		for($i = 0; $i <= 31; $i++)
		{
			$result[] = $i;
		}
	}else{
		$result = array('Y' => 'Yes', 'N' => 'No');
	}
	$tmp = '';
	
	$tmp .= '<select name="' .  $selectName . '"';
	if($action == 'delete'){$tmp .= ' disabled';}
	$tmp .= '>';
			foreach ($result as $reckey => $recval)
			{
				$tmp .= '<option value="' . $reckey . '"';
				if($reckey == $selectedId) 
				{
					$tmp .= ' selected="true"';
				}
				$tmp .= '>' . $recval . '</option>';
			}
		$tmp .= '</select>';
	$tmp .= '</form>';	
	$CI->sqlModel->_getExecutionTime($funcId,$result);
	return $tmp;	
}




/****************AdminPanel HTML Helper functions End***************/ 
			

function busi_get_ticketBox($index,$ticketBoxData, $showOuterDiv='')
{
	$CI =& get_instance();
	$tmp = '';
	foreach ($ticketBoxData as $tData){
		if($index == $tData['ID']){
			$extraMarginTop = '10px';
			if($showOuterDiv != ''){
				$extraMarginTop = '0px';
				$tmp .= '<div style="float:left;margin:0px;padding:5px;width:9.15%;">';
			}
					$tmp .= '<div id="' . $tData['ID'] . '" style="float:left;width:98%;border:4px solid ' . $tData['ticket_color'] . ';margin-top:' . $extraMarginTop . ';border-radius:10px;">';
						$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:7px;padding-bottom:7px;font-size:16px;font-family:arial;font-weight:700;color:#fff;background-color:' . $tData['ticket_color'] . ';">';
							$tmp .= $tData['ticket_name'];
						$tmp .= '</div>';
						$tmp .= '<div align="center" style="text-align:center;width:100%;padding:0px;margin:0px;background-color:#eeeeee;">';
							$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:#f536cb;">';
								$tmp .= 'No House';
							$tmp .= '</div>';						
							$tmp .= '<div id="plPos' . $tData['ID'] .'" align="center" style="text-align:center;min-height:100px;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#000;background-color:#eeeeee;vertical-align:middle;">';
								$playerPosition = $CI->businessSqlModel->checkPlayerPosition($tData['ID']);
								if(!empty($playerPosition)){
									foreach ($playerPosition as $pPos){
										$tmp .= '<div style="background-color:' . $pPos['player_color'] . ';padding-top:5px;padding-bottom:5px;font-size:12px;">' . $pPos['player_name'] . ' Arrived</div>';
									}
								}
								

							$tmp .= '</div>';						
							$ticketOwner = $CI->businessSqlModel->getTicketOwner($tData['ID']);
							if($ticketOwner == 'Available'){
								$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:green">';
									$tmp .= $ticketOwner;
								$tmp .= '</div>';						
							}else if($ticketOwner == ''){
								$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;">';
									$tmp .= '&nbsp;';
								$tmp .= '</div>';								
							}else{
								$tmp .= '<div align="center" style="text-align:center;width:100%;margin:0px;padding-top:5px;padding-bottom:5px;font-size:12px;font-family:arial;font-weight:700;color:#fff;background-color:red">';
									$tmp .= 'Sold to ' . $ticketOwner;
								$tmp .= '</div>';								
							}
						$tmp .= '</div>';
						$tmp .= '<div align="center" style="text-align:center;width:100%%;margin:0px;padding:0px;padding-top:5px;padding-bottom:5px;font-size:16px;font-family:arial;font-weight:700;color:#fff;background-color:' . $tData['ticket_color'] . ';">';
							$tmp .= '$ ' . $tData['ticket_price'];
						$tmp .= '</div>';
					$tmp .= '</div>';
			if($showOuterDiv != ''){
				$tmp .= '</div>';
			}
		}
	}	

	
	return $tmp;
}


?>