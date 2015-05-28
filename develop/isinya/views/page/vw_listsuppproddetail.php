<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Detail Produk Supplier</title>
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
		<div class="whead"><h6>Detail Produk - Supplier : <? echo $kd_supplier ?> - <? echo $nama_supplier ?> </h6><div class="clear"></div></div>
            <div class="shownpars">
				<table cellpadding="0" cellspacing="0" border="0" class="record dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Produk</th>
						<th>Nama Produk</th>
						<th>Disk % 1</th>
						<th>Disk % 2</th>
						<th>Disk % 3</th>
						<th>Disk % 4</th>
						<th>Disk amt 1</th>
						<th>Disk amt 2</th>
						<th>Disk amt 3</th>
						<th>Disk amt 4</th>
						<th>TOP</th>
						<th>Konsinyasi</th>
						<th>Harga</th>
						<th>DPP</th>
					</tr>
				</thead> 
				<tbody> 
					<?php $i = 1; $no=1;?>
					<?php foreach($this->cart->contents() as $items): ?>
					<?php echo form_hidden('rowid[]', $items['rowid']); ?>
					<tr class="content">
						<td class="td-keranjang" align="center"><?php echo $no; ?></td>
						<td class="td-keranjang" align="center"><?php echo $items['id']; ?></td>
						<td class="td-keranjang" align="left"><?php echo $items['name']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp1']; ?> %</td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp2']; ?> %</td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp3']; ?> %</td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp4']; ?> %</td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp1']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp2']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp3']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp4']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['waktu_top']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['konsinyasi']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['hrg_supplier']; ?></td>
						<td class="td-keranjang" align="right"><?php echo $items['dpp']; ?></td>
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