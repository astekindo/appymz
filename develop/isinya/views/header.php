<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title><?=$title?></title>


<link href="<?=base_url()?>asset/css/styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>bootstrap/css/datepicker.css">
<!-- <link rel="stylesheet" type="text/css" href="<?=base_url()?>bootstrap/css/bootstrap-modal.css"> -->

<!--<link rel="stylesheet" type="text/css" media="screen" href="<?=base_url()?>asset/css/sunny/jquery-ui-1.8.16.custom.css" /> -->
<!--<link rel="stylesheet" type="text/css" media="screen" href="<?=base_url()?>asset/css/ui.jqgrid.css" /> -->

<!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->

<script>!window.jQuery && document.write('<script src="<?php echo base_url(); ?>asset/js/ajax/1.7/jquery.min.js"><\/script>');</script>
<link rel="stylesheet" href="<?php echo base_url(); ?>asset/css/colorbox/colorbox.css" />
<script src="<?php echo base_url(); ?>asset/css/colorbox/jquery.colorbox.js"></script>

<!-- <script type="text/javascript" src="<?=base_url()?>asset/js/ajax/1.7/jquery.min.js"></script> -->

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/ui.spinner.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.mousewheel.js"></script>
 
<script type="text/javascript" src="<?=base_url()?>asset/js/ajax/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/charts/jquery.flot.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/charts/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/charts/jquery.flot.pie.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/charts/jquery.flot.resize.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/tables/jquery.sortable.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/tables/jquery.resizable.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.uniform.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.autotab.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.chosen.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.ibutton.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/forms/jquery.validationEngine.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/wizards/jquery.form.wizard.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/wizards/jquery.validate.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/wizards/jquery.form.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.fileTree.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.sourcerer.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/others/jquery.fullcalendar.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/others/jquery.elfinder.js"></script>

<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/ui/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/plugins/superfish.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/files/bootstrap.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/files/functions.js"></script>

<script type="text/javascript" src="<?=base_url()?>bootstrap/js/bootstrap-datepicker.js"></script>

<!-- Mengincludekan Library Jquery UI
<script src="<?=base_url()?>asset/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
		
<!-- Mengincludekan Locale untuk JQGrid
<script src="<?=base_url()?>asset/js/i18n/grid.locale-en.js" type="text/javascript"></script>
		<!-- Mengincludekan Library untuk JQGrid 
<script src="<?=base_url()?>asset/js/jquery.jqGrid.min.js" type="text/javascript"></script>-->


<script type='text/javascript'>
function confirmationDel(a,b) {
  var answer = confirm("Apakah anda yakin ingin menghapus?")
  if (answer){
    window.location = b+'/'+a;
  }
}

function confapprove(a,b) {
  var answer = confirm("Anda menyetujui ?")
  if (answer){
    window.location = b+'/'+a;
  }
}

function confnotapprove(a,b) {
  var answer = confirm("Anda tidak menyetujui ?")
  if (answer){
    window.location = b+'/'+a;
  }
}

//<![CDATA[ 
window.onload=function(){


}//]]>  

</script>

	<script>
		  $(document).ready(function(){
			  //Examples of how to assign the ColorBox event to elements
			  $(".cbbarang").colorbox({rel:'group', iframe:true, width:"700", height:"500"});
			  $(".cbpelanggan").colorbox({rel:'group', iframe:true, width:"700", height:"90%"});
			  $(".cblsbarang").colorbox({rel:'group', iframe:true, width:"700", height:"70%"});
			  $(".cbuser").colorbox({rel:'group', iframe:true, width:"700", height:"60%"});
			  $(".cblsprodukpr").colorbox({rel:'group', iframe:true, width:"80%", height:"80%"});
			  $(".cblsupprod").colorbox({rel:'group', iframe:true, width:"1000", height:"70%"});
	
		  });
	</script>

</head>
<body>
<div id="top">
	<div class="wrapper">
		<a href="#" title="" class="logo"><img src="<?=base_url()?>images/logo.png" alt="" /></a>
        <ul class="altMenu">
        </ul>
        
        <div class="clear"></div>
    </div>
</div>
<!--Top-->

<!--Menu-->
	<div id="content">
		<div class="topNav">
			<ul id="navigation" class="sf-navbar">

			<?=$menu;?>

			</ul>
		</div>
	</div>
    <div id="contentTop" class="contentTop">
        <span class="pageTitle"><span class="icon-link"></span>MITRA BANGUNAN SUPERMARKET</span>
        <ul class="quickStats">
        </ul>
        <div class="clear"></div>
    </div>
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="index.html">MITRA</a></li>
                <li><a href="forms.html">HOME</a>
<!--                    <ul>
                        <li><a href="form_validation.html" title="">INPUT</a></li>
                        <li><a href="form_editor.html" title="">UPDATE</a></li>
                        <li><a href="form_wizards.html" title="">DELETE</a></li>
                    </ul>-->
                </li>
                <li class="current"><a href="forms.html" title="">Master</a></li>
            </ul>
        </div>
		
        <div class="breadLinks">
            <ul>
                <li><a href="<?=base_url()?>logout" title=""><i class="icos-locked"></i><span>Logout</span></a></li>
<!--                <li><a href="#" title=""><i class="icos-check"></i><span>Tasks</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>Invoices</span>
                        <span><img src="<?=base_url()?>asset/images/elements/control/hasddArrow.png" alt="" /></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>-->
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
