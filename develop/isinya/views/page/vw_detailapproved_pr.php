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

	<?php 
		$atr = array('name' => 'frm', 'id' => 'frm', 'method' => 'POST'); 
		echo form_open('approval_pr/approve',$atr); 
	?>

<div class="wrapper">
	<div class="widget">
            <div class="whead"><h6>Detail Purchase Request - No PR : <? echo $no_pr ?><input type="hidden" name="no_pr" value="<?php echo $no_pr ?>" /></h6><div class="clear"></div></div>
            
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
				<thead>
					<tr>
						<th>NO</th>
						<th>KODE PRODUK</th>
						<th>NAMA PRODUK</th>
						<th>QTY OH</th>
						<th>QTY</th>
						<th>SATUAN</th>
						<th>APPROVE</th>
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
									<td class="td-keranjang" align="right"><?php echo number_format($items['qty_oh']); ?></td>
									<?if ($sts=="0"){?>
									<td class="td-keranjang" align="right">
									<input type="text" onKeyPress="return isNumberKey(event)" value="<?php echo number_format($items['qty']); ?>" class="input-read-only" style="width:40px;align:right;" name="qty[]" />
									</td>
									<td class="td-keranjang" align="center"><?php echo $items['satuan']; ?></td>
									<td align="center">
										<select name="status[]">
											<option value="A">Yes</option>
											<option value="N">No</option>
										</select>
									</td>
									<?}else{?>
									<td class="td-keranjang" align="right">
									<input type="text" onKeyPress="return isNumberKey(event)" value="<?php echo number_format($items['qty']); ?>" class="input-read-only" readonly style="width:40px;align:right;" name="qty[]" />
									</td>
									<td class="td-keranjang" align="center"><?php echo $items['satuan']; ?></td>
									<td align="center">
										<select name="status[]" disabled >
											<option value="A"><? if($items['status']=="A") { echo 'Yes'; } else { echo 'No';} ?></option>
										</select>
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
<div class="wrapper">
	<div class="widget" style="height:40px;margin-top:0px;">
		<div class="whead">
		<div class="grid4" align="right" style="height:40px;margin-top:0px;">
		<input type="text" name="totbaris" value="<?php echo intval($no)-1; ?>" readonly style="float: center;margin-top:5px;margin-right:5px;width:0px;"/>
				<?if ($sts=="0"){?>
				<input type="submit" onclick="return confirm('Anda menyetujui ?')" style="float: center;margin-top:5px;margin-right:5px;" name="approve" id="approve" value="Approve" class="buttonM bBlue" />
				<a href="#" onClick="confnotapprove('<? echo $no_pr ?>','<?php echo base_url(); ?>approval_pr/notapprove');" />
				<input type="button" style="float: center;margin-top:5px;margin-right:5px;" name="notapprove" id="notapprove" value="Not Approve" class="buttonM bBlue" /></a> 
				<?}?>
		</div>
</div>
</div>
<?php echo form_close(); ?>

</body>
</html>