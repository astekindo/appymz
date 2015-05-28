<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fLokasi").validate();
		
		$("#kd_lokasi").change(function(){
			var kd_lokasi = {kd_lokasi:$("#kd_lokasi").val()};
			$.ajax({
				type: "POST",
				url : "<?php echo base_url(); ?>sub_blok/get_blok",
				data: kd_lokasi,
				success: function(callback){
					$('#kd_blok').html(callback);
				}
			});
		});
		
      });
    </script>
<div class="wrapper">
	<form action="<?=base_url();?>sub_blok/save" id="fBlok" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Sub Blok</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Nama Lokasi:</label></div>
		                <div class="grid9">
							<?
								if ( !empty($id_sub_blok)) {
							?>
							<input type="hidden" id="kd_lokasi" name="kd_lokasi" value="<?=$kd_lokasi?>" />
							<input type="text" id="nama_lokasi" name="nama_lokasi" value="<?=$nama_lokasi?>" readonly="readonly" />
							<?
								} else {
							?>
							<span class="grid6"><?= form_dropdown('kd_lokasi', $listlokasi, $kd_lokasi, 'id = "kd_lokasi"'); ?></span>
							<? } ?>
							
							<input type="hidden" name="id_sub_blok" id="id_sub_blok" value="<?=$id_sub_blok?>">
							<input type="hidden" name="kd_sub_blok" id="kd_sub_blok" value="<?=$kd_sub_blok?>">
							
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Nama Blok:</label></div>
		                <div class="grid9">
							<?
								if ( !empty($id_sub_blok)) {
							?>
							<input type="hidden" id="kd_blok" name="kd_blok" value="<?=$kd_blok?>" />
							<input type="text" id="nama_blok" name="nama_blok" value="<?=$nama_blok?>" readonly="readonly" />
							<?
								} else {
							?>
							<span class="grid6"><?= form_dropdown('kd_blok', $listblok, $kd_blok, 'id = "kd_blok"'); ?></span>
							<? } ?>
							
							
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Nama Sub Blok:</label></div>
		                <div class="grid9">
							<input type="text" id="nama_sub_blok" name="nama_sub_blok" value="<?=$nama_sub_blok?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kapasitas:</label></div>
		                <div class="grid9 onlyNums">
							<input type="text" id="kapasitas" name="kapasitas" value="<?=$kapasitas?>" style="width:50px;" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>sub_blok'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<?php $this->load->view('footer');?>