<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Informasi Stok</h6><div class="clear"></div></div>
		<div class="shownnpars">
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Produk</th>
						<th>Nama Produk</th>
						<th>Jml Tersedia</th>
						<th>Stok Minimal</th>
						<th>Detail</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcminstok;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>
<?php $this->load->view('footer');?>