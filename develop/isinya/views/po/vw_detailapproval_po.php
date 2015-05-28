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
		<div class="whead"><h6>Detail Purchase Order - No PO : <? echo $no_po ?><input type="hidden" name="no_po" value="<?php echo $no_po ?>" /></h6><div class="clear"></div></div>
            <div class="shownpars">
				<table cellpadding="0" cellspacing="0" border="0" class="record dTable">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Produk</th>
						<th>Nama Produk</th>
						<th>Satuan</th>
						<th>Qty OH</th>
						<th>Qty Beli</th>
						<th>Waktu Top</th>
						<th>Disk Supplier 1 (%)</th>
						<th>Disk Supplier 2 (%)</th>
						<th>Disk Supplier 3 (%)</th>
						<th>Disk Supplier 4 (%)</th>
						<th>Disk Supplier 1</th>
						<th>Disk Supplier 2</th>
						<th>Disk Supplier 3</th>
						<th>Disk Supplier 4</th>
						<th>Harga Supplier</th>
						<th>DPP</th>
						<th>Approve</th>
					</tr>
				</thead> 
				<tbody> 
								<?php $i = 1; $no=1;?>
								<?php foreach($this->cart->contents() as $items): ?>
								<?php echo form_hidden('rowid[]', $items['rowid']); ?>
									<tr class="content">
									<td class="td-keranjang" align="center"><?php echo $no; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['id']; ?><input type="hidden" name="kd_produk[]" value="<?php echo $items['id']; ?>" /></td>
									<td class="td-keranjang" align="left"><?php echo $items['namap']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['satuan']; ?></td>
									<td class="td-keranjang" align="right"><?php echo number_format($items['qty_oh']); ?></td>
									<?if ($app=="0"){?>
									<td class="td-keranjang" align="right">
									<input type="text" onKeyPress="return isNumberKey(event)" value="<?php echo number_format($items['qty_beli']); ?>" class="input-read-only" style="width:40px;align:right;" name="qty_beli[]" />
									</td>
									<td class="td-keranjang" align="center"><?php echo $items['waktu_top']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp1']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp2']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp3']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp4']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp1']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp2']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp3']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp4']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['hrg_supplier']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['dpp']; ?></td>
									<td align="center">
									<? if($items['approval']=="" or  $items['approval']=="N") { ?>
									<div class="grid9 on_off" onclick="bolehUbah();"><input type="checkbox" name="approval[]" /></div>
									<? }else{ ?>
									<div class="grid9 on_off" onclick="bolehUbah();"><input type="checkbox" name="approval[]" Checked /></div>
									<?}?>
									</td>
									<?}else{?>
									<td class="td-keranjang" align="right">
									<input type="text" onKeyPress="return isNumberKey(event)" value="<?php echo number_format($items['qty_beli']); ?>" class="input-read-only" readonly style="width:40px;align:right;" name="qty_beli[]" />
									</td>
									<td class="td-keranjang" align="center"><?php echo $items['waktu_top']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp1']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp2']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp3']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp4']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp1']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp2']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp3']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp4']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['hrg_supplier']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['dpp']; ?></td>
									<td align="center">
									<? if($items['approval']=="" or  $items['approval']=="N") { ?>
									<div class="grid9 on_off" onclick="bolehUbah();"><input type="checkbox" name="approval[]" disabled /></div>
									<? }else{ ?>
									<div class="grid9 on_off" onclick="bolehUbah();"><input type="checkbox" name="approval[]" Checked disabled /></div>
									<?}?>
									</td>
									<?}?>
								</tr>
								<?php $i++; $no++;?>
								<?php endforeach; ?>
				<?//=$rcdetailapproval_pr;?>
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
				<?if ($app=="0"){?>
				<input type="submit" onclick="return confirm('Anda menyetujui ?')" style="float: center;margin-top:5px;margin-right:5px;" name="approve" id="approve" value="Approve" class="buttonM bRed" />
				<a href="#" onClick="confnotapprove('<? echo $no_po ?>','<?php echo base_url(); ?>approval_po/notapprove');" />
				<input type="button" style="float: center;margin-top:5px;margin-right:5px;" name="notapprove" id="notapprove" value="Not Approve" class="buttonM bRed" /></a> 
				<?}?>
		</div>
</div>
</div>
<?php echo form_close(); ?>

</body>
</html>