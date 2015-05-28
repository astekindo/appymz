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
<fieldset>
<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Tabel Supplier</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>menu/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
				<div style="overflow:auto;">
				<table cellpadding="0" cellspacing="0" border="0" class="dTable" >
				<thead>
					<tr>
						<th>NO</th>
						<th>ID MENU</th>
						<th>ID PARENT</th>
						<th>NAMA MENU</th>
						<th>CONTROLLER</th>
						<th>DESKRIPSI</th>
						<th>SEQUENCE</th>
						<th>BVIEW</th>
						<th>BINSERT</th>
						<th>BUPDATE</th>
						<th>BDELETE</th>
						<th>ACTION</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcmenu;?>
				</tbody> 
			</table> 
			</div>
		</div>
	</div>
</div>
</fieldset>

<?php $this->load->view('footer');?>