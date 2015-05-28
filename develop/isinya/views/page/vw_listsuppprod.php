<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Tabel Supplier per Barang</h6><div class="clear"></div></div>
		<div class="shownpars">
			<a href="<?=base_url()?>supp_prod/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
			<table cellpadding="0" cellspacing="0" border="0" class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Supplier</th>
						<th>Nama Supplier</th>
						<th>Detail</th>
						<th>Action</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcsuppprod;?>
				</tbody> 
			</table> 
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>