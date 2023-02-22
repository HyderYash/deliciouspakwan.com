<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo $MetaTitle; ?></title>
        <meta name="description" content="<?php if (isset($MetaDescription)) : echo $MetaDescription;
endif; ?>" />
        <meta name="keywords" content="<?php if (isset($MetaKeyWords)) : echo $MetaKeyWords;
endif; ?>" />
        <meta name="robots" content="<?php if (isset($MetaRobots)) : echo $MetaRobots;
endif; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=0.625, user-scalable=yes" />
	<style type="text/css">
	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }
	
	body {
		margin: 5px;
		font: 12px/16px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}
	a {
		color: #003399;
		background-color: transparent;
		font-weight: bold;
		text-decoration:none;
		pointer:hand;
	}
	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}
	/*********************************************************/	
	.rTable {
		display: table;
		width: 100%;
	}
	.rTableRow {
		display: table-row;
	}
	.rTableHeading {
		display: table-header-group;
		background-color: #D3DCE3;
		color:#0000ff;
	}
	.rTableCell, .rTableHead {
		display: table-cell;
		padding: 1px 7px;
		border: 1px solid rgb(160,160,255);
		vertical-align:middle;
	}
	.rTableHeading {
		display: table-header-group;
		background-color: #ddd;
		font-weight: bold;font-size: 12px;
	}
	.rTableFoot {
		display: table-footer-group;
		font-weight: bold;
		background-color: #ddd;font-size: 11px;color:#000;
	}
	.rTableBody {
		display: table-row-group;
	}
	.no-data-error {
		font-weight: bold;
		background-color: #ddd;font-size: 16px;color:#000;text-align:center;margin-top:20px;width:500px;
	}
	.rowheadercss {
		font-size: 14px;
	}
	/**************************************************************/	
	#scrolly{
		width: 99.5%;
		height: 100%;
		overflow: auto;
		overflow-y: hidden;
		margin: 0 auto;
		white-space: nowrap
	}
/*  LOGIN FORM CSS  START */		
	/* Bordered form */
	form {
		border: 3px solid #f1f1f1;
	}

	/* Full-width inputs */
	#login-container input[type=text], input[type=password], input[type=email] {
		width: 100%;
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		box-sizing: border-box;
	}

	/* Set a style for all buttons */
	button {
		background-color: #4CAF50;
		color: white;
		padding: 14px 20px;
		margin: 8px 0;
		border: none;
		cursor: pointer;
		width: 100%;
	}

	/* Add a hover effect for buttons */
	button:hover {
		opacity: 0.8;
	}

	/* Extra style for the cancel button (red) */
	.cancelbtn {
		width: auto;
		padding: 10px 18px;
		background-color: #f44336;
	}

	/* Center the avatar image inside this container */
	.imgcontainer {
		text-align: center;
		margin: 24px 0 12px 0;
	}

	/* Avatar image */
	img.avatar {
		width: 20%;
		border-radius: 50%;
	}

	/* Add padding to containers */
	.login_container {
		padding: 16px;
	}

	/* The "Forgot password" text */
	span.psw {
		float: right;
		padding-top: 16px;
	}

	/* Change styles for span and cancel button on extra small screens */
	@media screen and (max-width: 300px) {
		span.psw {
			display: block;
			float: none;
		}
		.cancelbtn {
			width: 100%;
		}
	}
/*  LOGIN FORM CSS  END */

/* SLIDER ON OFF BUTTON -- START */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
/* SLIDER ON OFF BUTTON -- END */	
/* THEME CSS FROM DATABASE -- START */
	<?php
	echo $themeInfo['THEME_CSS'];
	?>
/* THEME CSS FROM DATABASE -- END */

		
	</style>

</head>
<body>
<?php 
//print '<pre>';print_R($_SERVER);die;
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start('ob_gzhandler'); else ob_start(); ?>
<?php
if(isset($_SESSION['curr_user_token']) && $_SESSION['curr_user_token'] != '')
{
	?>
	<!--Navigation Start-->
	<?=_getSectionHeader('Expenditure Control System')?>
	<div id="scrolly">
    	<div class="rTable">
    		<div class="rTableRow">
    			<div class="rTableHead"><a href="/">Home</a></div>
    			<div class="rTableHead"><a href="/showMonthlyStatusCurrent">Monthly Data</a></div>
    			<div class="rTableHead"><a href="/showExpForCategoryByAccountsMonthly">Exp by Accounts</a></div>
    			<div class="rTableHead"><a href="/showExpByItemCategoryMonthly">Exp by Category</a></div>
    			<div class="rTableHead"><a href="/showExpForAccountByItemsMonthly">Exp by Items</a></div>
    			<div class="rTableHead"><a href="/adminDashboard">Admin Panel</a></div>
    			<div class="rTableHead"><a href="/comWordList">WordCom</a></div>
    			<div class="rTableHead"><a href="/comQuesList">QuesAnsCom</a></div>
    			<div class="rTableHead"><a href="/startBusiness">Play Business</a></div>
    			<div class="rTableHead"><a href="/dailyDraw">Daily Draw</a></div>
    			<div class="rTableHead"><a href="/showPrize">Show Prize</a></div>
    			<div class="rTableHead">
    			<label for="mo_year">Select Theme </label><select id="mo_year" name="mo_year" onchange="javascript:setCurrentTheme(this.value);">
    				<?php
    				foreach ($availableThemes as $availTheme)
    				{
    						print '<option value="' . $availTheme['ID'] . '"';
    						if($availTheme['ID'] == $themeInfo['THEME_ID']) 
    						{
    							print ' selected="true"';
    						}
    						print '>' . $availTheme['THEME_NAME'] . '</option>';
    					
    				}
    				?>
    			</select>			
    			</div>
    			<div class="rTableHead"><a href="/logout">Logout</a></div>
    		</div>
    	</div>
	</div>
	<!-- style="width:100%;" align="right">
		<div style="margin:0px auto;width:200px;border:1px solid red;"> 
		<div style="float:left;font-size:20px;font-weight:700;vertical-align:middle;padding-top:10px;">Debug Mode:</div>
		<div>
			<label class="switch">
			  <input type="checkbox">
			  <span class="slider round"></span>
			</label>
		</div>
		</div>
	</div-->

	<?=_getSectionFooter('&nbsp;')?>
	<!--Navigation End-->

<?php
}
?>