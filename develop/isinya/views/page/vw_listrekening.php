<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Rekening</h6><div class="clear"></div></div>
		<div class="shownnpars">
			<a href="<?=base_url()?>rekening/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Rekening</th>
						<th>Nama Rekening</th>
						<th>Action</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcrekening;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>