<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready( function () {
        var oTable = $('#tmutasibarang').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?=base_url();?>mutasibarang/getData"
        });
    });

</script>

	<script>
		  $(document).ready(function(){
			  //Examples of how to assign the ColorBox event to elements
			  $(".cbdetailmutasi").colorbox({rel:'group', iframe:true, width:"1200", height:"500"});
		  });
		  
	</script>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Tabel Mutasi Barang</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>mutasibarang/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
				<table cellpadding="0" cellspacing="0" border="0" class="dTable">
				<thead>
					<tr>
						<th><b>NO</b></th>
						<th><b>NO MUTASI</b></th>
						<th><b>KETERANGAN</b></th>
						<th><b>CREATED BY</b></th>
						<th><b>CREATED DATE</b></th>
						<th><b>ACTION</b></th>
					</tr>
					</thead> 
					<tbody> 
						<?=$rcmutasibarang;?>
					</tbody> 
				</table> 
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>