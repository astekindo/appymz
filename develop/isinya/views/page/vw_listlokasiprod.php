<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Table Lokasi Barang</h6><div class="clear"></div></div>
		<div class="shownnpars">
			<table cellpadding="0" cellspacing="0" border="0"  class="dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Lokasi</th>
						<th>Nama Blok</th>
						<th>Nama Sub Blok</th>
						<th>Kapasitas</th>
						<th>Detail</th>
					</tr>
				</thead> 
				<tbody> 
					<?=$rclokasiprod;?>
				</tbody> 
			</table>
		</div>
	</div>
</div>
<?php $this->load->view('footer');?>