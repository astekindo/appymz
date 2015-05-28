<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Sub Blok Lokasi</h6><div class="clear"></div></div>
		<div class="shownnpars">
			<a href="<?=base_url()?>sub_blok/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Lokasi</th>
						<th>Kode Blok</th>
						<th>Kode Sub Blok</th>
						<th>Nama Lokasi</th>
						<th>Nama Blok</th>
						<th>Nama Sub Blok</th>
						<th>Kapasitas</th>
						<th>Action</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcsubblok;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>
<?php $this->load->view('footer');?>