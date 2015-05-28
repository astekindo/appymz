<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('header');
?>

<fieldset>
	<div class="wrapper">
		<div class="fluid">
			<div class="widget">
				<div class="whead"><h6>APPROVAL PURCHASE ORDER</h6><div class="clear"></div>
				</div>
				<div id="dyn" class="hiddenpars">
					
					<table cellpadding="0" cellspacing="0" border="0" class="record dTable">
						<thead>
							<tr>
								<th>No</th>
								<th>No. PO</th>
								<th>No. PR</th>
								<th>Subject / keterangan</th>
								<th>Masa Berlaku</th>
								<th>Jumlah</th>
								<th>Grand Total</th>
								<th>Tgl dibuat</th>
								<th>Dibuat oleh</th>
								<th>Status</th>
								<th>Detail</th>
							</tr>
						</thead> 
						<tbody> 
							<?=$rcapproval_po;?>
						</tbody>
					</table>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</fieldset>

<?php $this->load->view('footer'); ?>