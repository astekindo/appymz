<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fLokasi").validate();
      });
</script>

<div class="wrapper">
	<form action="<?=base_url();?>lokasi/save" id="fLokasi" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Lokasi</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Nama:</label></div>
		                <div class="grid9">
							<input type="hidden" name="id_lokasi" id="id_lokasi" value="<?=$id_lokasi?>">
							<input type="hidden" name="kd_lokasi" id="kd_lokasi" value="<?=$kd_lokasi?>">
							<input type="text" id="nama_lokasi" name="nama_lokasi" value="<?=$nama_lokasi?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>lokasi'" class="buttonL bRed" />
					</div>
				</div>	
			</div>
		</fieldset>
	</form>
</div>
<?php $this->load->view('footer');?>