<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready( function () {
        var oTable = $('#tkategori').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?=base_url();?>kategori4/getData"
        });
    });

</script>
<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Tabel Kategori 4</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>kategori4/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
				<table cellpadding="0" cellspacing="0" border="0" class="dTable">
				<thead>
					<tr>
						<th><b>NO</b></th>
						<th><b>KODE KATEGORI</b></th>
						<th align="left"><b>NAMA KATEGORI</b></th>
						<th><b>ACTION</b></th>
					</tr>
					</thead> 
					<tbody> 
						<?=$rckategori4;?>
					</tbody> 
				</table> 
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>