<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready( function () {
        var oTable = $('#tarea').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?=base_url();?>area/getData"
        });
    });

</script>
<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Tabel Kategori 1</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>area/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
				<table cellpadding="0" cellspacing="0" border="0" class="dTable">
				<thead>
					<tr>
						<th>NO</th>
						<th>Nama Area</th>
						<th>Alamat</th>
						<th>Keterangan</th>
						<th>Action</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcarea;?>
				</tbody> 
			</table> 
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>