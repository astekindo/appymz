<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fLokasi").validate();
      });
    </script>
<div class="wrapper">
	<form action="<?=base_url();?>blok/save" id="fBlok" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Blok</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Nama Lokasi:</label></div>
		                <div class="grid9 noSearch">
							<input type="hidden" name="id_blok" id="id_blok" value="<?=$id_blok?>">
							<input type="hidden" name="kd_blok" id="kd_blok" value="<?=$kd_blok?>">
							<?
								if ( !empty($id_blok)) {
							?>
							<input type="hidden" id="kd_lokasi" name="kd_lokasi" value="<?=$kd_lokasi?>" />
							<input type="text" id="nama_lokasi" name="nama_lokasi" value="<?=$nama_lokasi?>" readonly="readonly" />
							<?
								} else {
							?>
							<?= form_dropdown('kd_lokasi', $listlokasi, $kd_lokasi, 'class="select"'); ?>
							<? } ?>
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Nama Blok:</label></div>
		                <div class="grid9">
							<input type="text" id="nama_blok" name="nama_blok" value="<?=$nama_blok?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>blok'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<?php $this->load->view('footer');?>