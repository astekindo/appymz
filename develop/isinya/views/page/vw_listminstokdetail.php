<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Detail Lokasi Barang</title>
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
	document.getElementById("qty").readOnly=false;
}
</script>
	
</head>

<body>

	<?php 
		$atr = array('name' => 'frm', 'id' => 'frm', 'method' => 'POST'); 
		echo form_open('approval_pr/approve',$atr); 
	?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Detail Lokasi Produk - Kode Produk : <? echo $kd_produk ?> </h6><div class="clear"></div></div>
            <div class="shownpars">
				<table cellpadding="0" cellspacing="0" border="0" class="record dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Lokasi</th>
						<th>Nama Lokasi</th>
						<th>Qty OH</th>
					</tr>
				</thead> 
				<tbody> 
					<?php $i = 1; $no=1;?>
					<?php foreach($this->cart->contents() as $items): ?>
					<?php echo form_hidden('rowid[]', $items['rowid']); ?>
					<tr class="content">
						<td class="td-keranjang" align="center"><?php echo $no; ?></td>
						<td class="td-keranjang" align="center"><?php echo $items['kode_lokasi']; ?></td>
						<td class="td-keranjang" align="left"><?php echo $items['nama_lokasi']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['qty_oh']; ?> </td>
					</tr>
					<?php $i++; $no++;?>
					<?php endforeach; ?>
				</tbody> 
			</table> 
		</div>
	</div>
</div>

<?php echo form_close(); ?>

</body>
</html>