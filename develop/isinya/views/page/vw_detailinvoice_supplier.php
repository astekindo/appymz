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
	document.getElementById("qty").readOnly=false;
}
</script>
	
</head>

<body>

	<?php 
		$atr = array('name' => 'frm', 'id' => 'frm', 'method' => 'POST'); 
		echo form_open('approval_po/approve',$atr); 
	?>

<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Detail Invoice Supplier - No RO : <? echo $no_ro ?><input type="hidden" name="no_ro" value="<?php echo $no_ro ?>" /></h6><div class="clear"></div></div>
            <div class="shownpars">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tDefault">
									<thead>
										<tr>
											<th>NO</th>
											<th>KODE PRODUK</th>
											<th>NAMA PRODUK</th>
											<th>QTY TERIMA</th>
											<th>Disc % 1</th>
											<th>Disc % 2</th>
											<th>Disc % 3</th>
											<th>Disc % 4</th>
											<th>Amt 1</th>
											<th>Amt 2</th>
											<th>Amt 3</th>
											<th>Amt 4</th>
											<th>Harga Supplier</th>
											<!--<th>ACTION</th>-->
										</tr>
									</thead> 
									<tbody> 
										<?php $i = 1; $no=1;?>
										<?php foreach($this->cart->contents() as $items): ?>
										<?php echo form_hidden('rowid[]', $items['rowid']); ?>
											<tr class="content">
											<td class="td-keranjang" align="center"><?php echo $no; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['id']; ?><input type="hidden" name="kd_produk[]" value="<?php echo $items['id']; ?>" /></td>
											<td class="td-keranjang" align="left"><?php echo $items['name']; ?></td>
											<td class="td-keranjang" align="right"><?php echo number_format($items['qty']); ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp1']; ?>%</td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp2']; ?>%</td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp3']; ?>%</td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp4']; ?>%</td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp1']; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp2']; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp3']; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp4']; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['hrg_supplier']; ?></td>
										<!--	<td align="center">
												<a href="<?php echo base_url(); ?>receiveorder/pilihlokasi" class="cblsbarang" />
												<input type="button" name="lokasi" id="lokasi" value="Lokasi" class="buttonM bBlue" />
												</a>
											</td>-->
										</tr>
										<?php $i++; $no++;?>
										<?php endforeach; ?>
									</tbody> 
								</table>
		</div>
	</div>
</div>
<div class="wrapper">
	<div class="widget" style="height:40px;margin-top:0px;">
		<div class="whead">
		<div class="grid4" align="right" style="height:40px;margin-top:0px;">
		<input type="text" name="totbaris" value="<?php echo intval($no)-1; ?>" readonly style="float: center;margin-top:5px;margin-right:5px;width:0px;"/>
				
		</div>
</div>
</div>
<?php echo form_close(); ?>

</body>
</html>