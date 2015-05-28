<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">
      $(document).ready(function(){
        $("#fkategori").validate();
      });

	  $(document).ready(function() {
		$("#kd_lokasi").change(function(){
		var kd_lokasi = {kd_lokasi:$("#kd_lokasi").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>grab_kategori3",
		data: kd_lokasi,
		success: function(callback){
		$('#kd_blok').html(callback);
		}
		});
		});
		});

		$(document).ready(function() {
		$("#kd_blok").change(function(){
		var kd_blok = {kd_blok:$("#kd_blok").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>grab_kategori4",
		data: kd_blok,
		success: function(callback){
		$('#kd_subblok').html(callback);
		}
		});
		});
		});
</script>

<form action="<?=base_url();?>kategori4/save" id="fkategori" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid6">
			<div class="whead"><h6>Input Kategori 4</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_kategori4"><input type="hidden" name="id_kategori4" value="<?=$id_kategori4?>"></label>
				<label for="kd_kategori4"><input type="hidden" name="kd_kategori4" value="<?=$kd_kategori4?>"></label>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 1 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_lokasi', $listkategori1, $kd_lokasi, 'id = "kd_lokasi"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 2 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_blok', $listkategori2, $kd_blok, 'id = "kd_blok"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 3 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_subblok', $listkategori3, $kd_subblok, 'id = "kd_subblok"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
					<label for="kd_lokasi"><input type="hidden" name="kd_lokasi" value="<?=$kd_lokasi?>"></label>
					<label for="kd_blok"><input type="hidden" name="kd_blok" value="<?=$kd_blok?>"></label>
					<label for="kd_subblok"><input type="hidden" name="kd_subblok" value="<?=$kd_subblok?>"></label>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>kategori4'" /></p>
			</div>
		</div>
</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>