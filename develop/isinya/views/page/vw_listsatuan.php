<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Satuan</h6><div class="clear"></div></div>
		<div class="shownnpars">
			<a href="<?=base_url()?>satuan/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Satuan</th>
						<th>Keterangan</th>
						<th>Action</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcsatuan;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>