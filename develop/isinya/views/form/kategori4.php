<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">
      $(document).ready(function(){
        $("#fkategori").validate();
      });

	  $(document).ready(function() {
		$("#kd_kategori1").change(function(){
		var kd_kategori1 = {kd_kategori1:$("#kd_kategori1").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>grab_kategori3",
		data: kd_kategori1,
		success: function(callback){
		$('#kd_kategori2').html(callback);
		}
		});
		});
		});

		$(document).ready(function() {
		$("#kd_kategori2").change(function(){
		var kd_kategori2 = {kd_kategori2:$("#kd_kategori2").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>grab_kategori4",
		data: kd_kategori2,
		success: function(callback){
		$('#kd_kategori3').html(callback);
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
                 <? if ($id_kategori4=="") { ?>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 1 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_kategori1', $listkategori1, $kd_kategori1, 'id = "kd_kategori1"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 2 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_kategori2', $listkategori2, $kd_kategori2, 'id = "kd_kategori2"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 3 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_kategori3', $listkategori3, $kd_kategori3, 'id = "kd_kategori3"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
					<? }else{ ?>	
					<label for="kd_kategori1"><input type="hidden" name="kd_kategori1" value="<?=$kd_kategori1?>"></label>
					<label for="kd_kategori2"><input type="hidden" name="kd_kategori2" value="<?=$kd_kategori2?>"></label>
					<label for="kd_kategori3"><input type="hidden" name="kd_kategori3" value="<?=$kd_kategori3?>"></label>
					<div class="formRow">
							<div class="grid3"><label>Kode Kategori 4 : </label></div>
							<div class="grid9">
								<span class="grid6"><? echo $kd_kategori1;echo $kd_kategori2;echo $kd_kategori3;echo $kd_kategori4; ?></span>
							</div>
							<div class="clear"></div>
					  </div>
					<div class="formRow">
							<div class="grid3"><label>Kategori 1 : </label></div>
							<div class="grid9">
								<span class="grid6"><? echo $nama_kategori1;?></span>
							</div>
							<div class="clear"></div>
					  </div>
					<div class="formRow">
							<div class="grid3"><label>Kategori 2 : </label></div>
							<div class="grid9">
								<span class="grid6"><? echo $nama_kategori2;?></span>
							</div>
							<div class="clear"></div>
					  </div>
					<div class="formRow">
							<div class="grid3"><label>Kategori 3 : </label></div>
							<div class="grid9">
								<span class="grid6"><? echo $nama_kategori3;?></span>
							</div>
							<div class="clear"></div>
					  </div>
					<?}?>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 4 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="nama_kategori4" name="nama_kategori4" value="<?=$nama_kategori4?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>kategori4'" /></p>
			</div>
		</div>
</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>