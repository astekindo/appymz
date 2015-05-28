<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Single Window</title>
	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	$this->load->view('header_single');
	?>
	<link href="<?php echo base_url(); ?>asset/css/style-single.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>asset/css/chosen.css" rel="stylesheet" type="text/css">
</head>

<script>
function bolehUbah()
{
	document.getElementById("qty").readOnly=false;
}

function onlyNumbers(evt) {
	// Mendapatkan key code	
	var charCode = (evt.which) ? evt.which : event.keyCode;

	// Validasi hanya tombol angka
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

  $(document).ready(function(){
	$("#flistbarang").validate();
  });

</script>
<body>

<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
		<?php 
			$atr = array('name' => 'flistbarang', 'id' => 'flistbarang', 'method' => 'POST'); 
			echo form_open('penjualan_barang/addcart',$atr); 
		?>

            <div class="whead"><h6>Data - Master Produk</h6>
						<select data-placeholder="Cari nama produk..." class="chzn-select" style="float: right;margin-top:30px;margin-right:50px;width:100px;" tabindex="1" name="kd_produk" id="kd_produk">
							<option value=""></option> 
								<?php
									foreach($tm_produk->result_array() as $db)
									{
								?>
									<option value="<?php echo $db['kd_produk']; ?>"><?php echo $db['nama_produk']; ?></option>
								<?php
									}
								?>
						</select></div>
            
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
                <thead>
                    <tr>
                    </tr>
                </thead>
				<tbody>
					<tr>
						<div id="data_produk" class="formRow"></div>
					</tr>
					<tr>
					<td colspan="2"><input type="submit" name="addcart" id="addcart" value="Tambah" class="buttonM bBlue" /></td>
					</tr>
                </tbody>
            </table>
			<?php echo form_close(); ?>
	
					<script src="<?php echo base_url(); ?>asset/js/jquery.min.js" type="text/javascript"></script>
					<script src="<?php echo base_url(); ?>asset/js/chosen.jquery.js" type="text/javascript"></script>
					<script type="text/javascript"> $(".chzn-select").chosen().change(function(){ 
								var kd_produk = $("#kd_produk").val(); 
								$.ajax({ 
								url: "<?php echo base_url(); ?>penjualan_barang/ambil_data_produk",
								data: "kd_produk="+kd_produk, 
								cache: false, 
								success: function(msg){ 
								$("#data_produk").html(msg);
								document.frm.addcart.disabled=false;
							} 
						})
						});
					</script>
		</div>
	</div>		
</div>
</fieldset>
</body>
</html>