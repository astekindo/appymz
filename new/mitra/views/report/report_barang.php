<script type='text/javascript' src='<?php echo base_url()?>assets/js/jqgrid/jquery-1.7.2.min.js'></script>
    <script src="<?php echo base_url()?>assets/js/jqgrid/jquery.ui.core.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>assets/js/jqgrid/jquery.ui.widget.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>assets/js/jqgrid/jquery.ui.mouse.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>assets/js/jqgrid/jquery.ui.sortable.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>assets/js/jqgrid/jquery.jqGrid.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo base_url()?>assets/js/jqgrid/i18n/grid.locale-en.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/redmond/jquery.ui.core.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/redmond/jquery.ui.theme.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/jqgrid/ui.jqgrid.css" type="text/css" media="all" />
	<style>
	.ui-jqgrid tr.jqgrow td {
    white-space: normal !important;
    height:auto;
    vertical-align:text-top;
	font-size:1.2em;
	}
	</style><?php 	
		$url=site_url('report_barang/getReport/'.$id_report);
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
					
					loadonce:true,
					datatype: "json",
					mtype: "GET",
					height: "auto",
					width: "100%",
					colNames:["KodeLayanan","NamaLayanan","KodeUnit"],
					colModel:[{"name":"KodeLayanan","index":"KodeLayanan","width":35},{"name":"NamaLayanan","index":"NamaLayanan","width":35},{"name":"KodeUnit","index":"KodeUnit","width":35},],
					rowNum:20,
					rowList:[20,50,75],
					pager: "#pager",
					viewrecords: true,
					sortname:"'.$sidx.'",
					sortorder: "asc",
					autowidth:true,
					rownumbers: true,
					height: "100%",
					editurl: "report_barang/crud",
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
	?><script type="text/javascript">
var reportbarang = new Ext.Panel({
	id:'reportbarang',
    title: 'Report Barang',
	listeners: {
			'render': function()
				{
					Ext.Ajax.request({
						url: '<?= site_url('report_barang/loadView') ?>',
						success: function(response){
							Ext.getCmp('reportbarang').update( response.responseText );
						}
					});                
				}
		}	
    });
</script>