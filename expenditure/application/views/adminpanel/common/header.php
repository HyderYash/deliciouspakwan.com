<!DOCTYPE html>
<html lang="en">
<head>
    
	<meta charset="utf-8">
	<title><?php echo $MetaTitle; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php if (isset($MetaDescription)) : echo $MetaDescription;
endif; ?>" />
        <meta name="keywords" content="<?php if (isset($MetaKeyWords)) : echo $MetaKeyWords;
endif; ?>" />
        <meta name="robots" content="<?php if (isset($MetaRobots)) : echo $MetaRobots;
endif; ?>" />
        
    <!-- Bootstrap Core CSS -->
    <link href="/adminpanel_js_css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="/adminpanel_js_css/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="/adminpanel_js_css/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="/adminpanel_js_css/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/adminpanel_js_css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="/adminpanel_js_css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		xmp {
		 white-space: pre-wrap;       /* css-3 */
		 white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
		 white-space: -pre-wrap;      /* Opera 4-6 */
		 white-space: -o-pre-wrap;    /* Opera 7 */
		 word-wrap: break-word;       /* Internet Explorer 5.5+ */
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
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>