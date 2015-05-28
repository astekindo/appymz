<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Produk</h6><div class="clear"></div></div>
		<div class="shownnpars" style="overflow: auto;">
			<a href="<?=base_url()?>produk/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kategori 1</th>
						<th>Kategori 2</th>
						<th>Kategori 3</th>
						<th>Kategori 4</th>
						<th>Thn Registrasi</th>
						<th>No Urut</th>
						<th>Nama Produk</th>
						<th>Kode Produk</th>
						<th>Kode Produk Lama</th>
						<th>Kode Produk Supplier</th>
						<th>Satuan</th>
						<th>Kode Peruntukkan</th>
						<th>Jml Barang Masuk</th>
						<th>Jml Barang Keluar</th>
						<th>Jml Barang Tersedia</th>
						<th>Jml Barang DO</th>
						<th>Jml Barang Siap Jual</th>
						<th>Minimum Stok</th>
						<th>Maksimum Stok</th>
						<th>Minimum Order</th>
						<th>Harga Supplier</th>
						<th>Harga Pokok</th>
						<th>Harga Jual</th>
						<th>Disk Konsumen 1 (%)</th>
						<th>Disk Konsumen 1 (Rp)</th>
						<th>Disk Konsumen 2 (%)</th>
						<th>Disk Konsumen 2 (Rp)</th>
						<th>Disk Konsumen 3 (%)</th>
						<th>Disk Konsumen 3 (Rp)</th>
						<th>Disk Konsumen 4 (%)</th>
						<th>Disk Konsumen 4 (Rp)</th>
						<th>Action</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rcproduk;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>