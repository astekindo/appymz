<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>List Retur Order</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>retur_order/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
				<table cellpadding="0" cellspacing="0" border="0" class="dTable">
				<thead>
					<tr>
						<th><b>No</b></th>
						<th><b>No Retur</b></th>
						<th><b>Tgl Retur</b></th>
						<th><b>Supplier</b></th>
						<th><b>Created By</b></th>
						<th><b>Created Date</b></th>
					</tr>
					</thead> 
					<tbody> 
						<?=$rcreturorder;?>
					</tbody> 
				</table> 
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>