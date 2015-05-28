<!DOCTYPE html>

	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	$this->load->view('header_single');
	?>
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

		<?php 
			$atr = array('name' => 'flistbarang', 'id' => 'flistbarang', 'method' => 'POST'); 
			echo form_open('mutasibarang/addcart',$atr); 
		?>

<fieldset>

<div class="wrapper">
	<div class="fluid">
		<div class="widget grid10">
			<div class="whead"><h6>Data - Master Produk</h6><div class="clear"></div></div>
			<div class="formRow">
				<div class="grid4"><label>Masukan kode atau nama </label></div>
				<div class="grid5"><input type="text" name="kd_produk" value="" style="width:220px;" class="required" id="kd_produk" /></div>
				<div class="grid1"><input type="button" name="cari" id="cari" value="Cari" onclick="loadproduk()" class="buttonM bBlue" /></div>
				<div class="clear"></div>
			</div>

			<?php echo form_close(); ?>
	
					<script type="text/javascript"> 
								function loadproduk(){ 
								var kd_produk = $("#kd_produk").val(); 
								$.ajax({ 
								url: "<?php echo base_url(); ?>mutasibarang/ambil_data_produk",
								data: "kd_produk="+kd_produk,
								cache: false, 
								success: function(msg){ 
								$("#data_produk").html(msg);
								document.frm.addcart.disabled=false;
								} 
								})
								};

			</script>
		</div>
	</div>		
</div>
</fieldset>

<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<div class="whead"><h6>Tabel Produk</h6><div class="clear"></div></div>
			<table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
				<thead>
				<tr>
					<th>NO</th>
					<th>KODE PRODUK</th>
					<th>NAMA PRODUK</th>
					<th>LOKASI TERSIMPAN</th>
					<th>QTY OH</th>
					<th>ACTION</th>
				</tr>
				</thead> 
				<tbody id="data_produk">
				
				</tbody> 
			</table> 
		</form>
		</div>
	</div>
</div>
<?php $this->load->view('footer');?>