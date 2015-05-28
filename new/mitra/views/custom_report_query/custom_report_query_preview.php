<!DOCTYPE html>
<html>

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

	<title>JqGrid</title>

    <script type='text/javascript' src='<?php echo base_url()?>js/jqgrid/jquery-1.7.2.min.js'></script>
    <script src="<?php echo base_url()?>js/jqgrid/jquery.ui.core.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>js/jqgrid/jquery.ui.widget.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>js/jqgrid/jquery.ui.mouse.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>js/jqgrid/jquery.ui.sortable.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>js/jqgrid/jquery.jqGrid.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo base_url()?>js/jqgrid/i18n/grid.locale-en.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>css/redmond/jquery.ui.core.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/redmond/jquery.ui.theme.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo base_url()?>css/jqgrid/ui.jqgrid.css" type="text/css" media="all" />
	<style>
	.ui-jqgrid tr.jqgrow td {
    white-space: normal !important;
    height:auto;
    vertical-align:text-top;
	font-size:1.2em;
	}
	</style>
</head>
<body>
	<?php 	
		$url=site_url('custom_report_query/getReport/'.$id_report);
		$order='ASC';
	?>
	<table id="grid"></table>
	<div id="pager"></div>
	
	<?php
	
	$grid='<script type="text/javascript">';

	$grid	.=	'jQuery("#grid").jqGrid({
					sortable: { update: function(relativeColumnOrder) {
					var columnNames 	= $("#grid").jqGrid("getGridParam","colNames");
					var sortName 		= $("#grid").jqGrid("getGridParam","sortname");
					var sortOrder 		= $("#grid").jqGrid("getGridParam","sortorder");
					}},
					url:"'.site_url("custom_report_query/get_json_data/".$id_report."/".$sidx."/".$order).'",
					loadonce:true,
					datatype: "json",
					mtype: "GET",
					height: "auto",
					width: "100%",
					colNames:'.$colNames.',
					colModel:'.$colModels.',
					rowNum:20,
					rowList:[20,50,75],
					pager: "#pager",
					viewrecords: true,
					sortname:"'.$sidx.'",
					sortorder: "asc",
					autowidth:true,
					rownumbers: true,
					height: "100%",
					editurl: "custom_report_query/crud",
					caption: "Laporan KLH" 
				});';
	   
    $grid 	.= 	'jQuery("#grid").jqGrid("navGrid","#pager",{edit:false,add:false,del:false,search:false});';
  	
	$grid 	.= 	'jQuery("#grid").jqGrid("navButtonAdd","#pager",{
				   caption:"Excel", 
				   onClickButton : function () { 
					var columnNames 	= $("#grid").jqGrid("getGridParam","colNames");
					var sortName 		= $("#grid").jqGrid("getGridParam","sortname");
					var sortOrder 		= $("#grid").jqGrid("getGridParam","sortorder");
					var col = "";
					for(i=1; i < columnNames.length; i++){
						if(i==1) {
							col = columnNames[i];
						} else {
							col = col + "/" + columnNames[i];
						}
					}
					
					window.location="'.$url.'/" + columnNames.length + "/" + col + "/" + sortName + "/" + sortOrder + "/excel";
				   } 
				});';
				
	$grid 	.= 	'jQuery("#grid").jqGrid("navButtonAdd","#pager",{
				   caption:"PDF", 
				   onClickButton : function () { 
					var columnNames 	= $("#grid").jqGrid("getGridParam","colNames");
					var sortName 		= $("#grid").jqGrid("getGridParam","sortname");
					var sortOrder 		= $("#grid").jqGrid("getGridParam","sortorder");
					var col = "";
					for(i=1; i < columnNames.length; i++){
						if(i==1) {
							col = columnNames[i];
						} else {
							col = col + "/" + columnNames[i];
						}
					}
					
					window.location="'.$url.'/" + columnNames.length + "/" + col + "/" + sortName + "/" + sortOrder + "/pdf";
				   } 
				});';

				
	$grid 	.= '</script>';
	echo $grid;
	?>
</body>
</html>

