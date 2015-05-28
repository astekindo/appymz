<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Single Window</title>
	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	$this->load->view('header_single');
	?>
	<link href="<?php echo base_url(); ?>asset/css/style-single.css" rel="stylesheet">

<script>
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
		       
        return true;
    }

function bolehUbah()
{
	document.getElementById("statuscheck[]").value='On';
}
</script>
	
</head>

<body>

<div class="wrapper">
	<div class="widget">
            <div class="whead"><h6>Detail Mutasi Barang - No Mutasi : <? echo $no_mutasi ?></h6><div class="clear"></div></div>
            
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
				<thead>
					<tr>
						<th>NO</th>
						<th>KODE PRODUK</th>
						<th>NAMA PRODUK</th>
						<th>LOKASI LAMA</th>
						<th>LOKASI BARU</th>
						<th>QTY</th>
					</tr>
				</thead> 
				<tbody> 
								<?php $i = 1; $no=1;
								foreach($mutasi_detail->result_array() as $db){?>
								<tr class="content">
									<td class="td-keranjang" align="center"><?php echo $no; ?></td>
									<td class="td-keranjang" align="center"><?php echo $db['kd_produk']; ?></td>
									<td class="td-keranjang" align="left"><?php echo $db['nama_produk']; ?></td>
									<td class="td-keranjang" align="left"><?php echo $db['nama_lokasi_lama']; ?></td>
									<td class="td-keranjang" align="left"><?php echo $db['nama_lokasi_baru']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $db['qty_mutasi']; ?>&nbsp;</td>
								</tr>
								<?php $i++; $no++; }?>
				</tbody> 
			</table> 
		</div>
	</div>
<div class="wrapper">
	<div class="widget" style="height:40px;margin-top:0px;">
		<div class="whead">
		<div class="grid4" align="right" style="height:40px;margin-top:0px;">
		</div>
</div>
</div>

</body>
</html>