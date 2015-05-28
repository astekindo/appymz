<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready( function () {
        var oTable = $('#the_table').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?=base_url();?>menu/getData"
        });
    });

</script>
<p>
<!-- <a href="<?=base_url()?>input_menu" class="btn btn btn-primary">New Data</a> -->
<a href="<?=base_url()?>input_menu" class="buttonL bGreyish">New Data</a>
</p>
<div class="widget">
	<div class="whead"><h6>Table Menu</h6><div class="clear"></div></div>
	<div id="dyn" class="hiddenpars">
		<table cellpadding="0" cellspacing="0" border="0"  class="dTable" id="dynamic"> 
			<thead>
			<tr>
				<th>ID</th>
				<th>Nama&nbsp;menu</th>
				<th>Index&nbsp;Menu</th>
				<th>Parent</th>
				<th>Controller</th>
				<th>Action</th>
			</tr>
			</thead> 
			<tbody> 
			<?=$rcmenu;?>
			</tbody> 
		</table> 

<?php $this->load->view('footer');?>