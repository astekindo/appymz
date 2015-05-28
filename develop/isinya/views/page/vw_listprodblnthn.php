<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Barang per Bulan per Tahun</h6><div class="clear"></div></div>
		<div class="shownnpars">
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Bulan</th>
						<th>Tahun</th>
						<th>Kode Produk</th>
						<th>Nama Produk</th>
						<th>Jumlah Masuk</th>
						<th>Jumlah Keluar</th>
						<th>Jumlah Tersedia</th>
						<th>Mutasi Masuk</th>
						<th>Mutasi Keluar</th>
						<th>Jumlah Target</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcprodblnthn;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>
<?php $this->load->view('footer');?>