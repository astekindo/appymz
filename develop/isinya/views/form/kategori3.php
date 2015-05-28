<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">

      $(document).ready(function(){
        $("#fkategori").validate();
      });
</script>
<script type="text/javascript">
		$(document).ready(function() {
		$("#kd_kategori1").change(function(){
		var kd_kategori1 = {kd_kategori1:$("#kd_kategori1").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>grab_kategori2",
		data: kd_kategori1,
		success: function(callback){
		$('#kd_kategori2').html(callback);
		}
		});
		});
		});
</script>
<form action="<?=base_url();?>kategori3/save" id="fkategori" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid6">
			<div class="whead"><h6>Input Kategori 3</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_kategori3"><input type="hidden" name="id_kategori3" value="<?=$id_kategori3?>"></label>
				<label for="kd_kategori3"><input type="hidden" name="kd_kategori3" value="<?=$kd_kategori3?>"></label>
                 <? if ($id_kategori3=="") { ?>
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
				<? }else{ ?>	
				<label for="kd_kategori1"><input type="hidden" name="kd_kategori1" value="<?=$kd_kategori1?>"></label>
				<label for="kd_kategori2"><input type="hidden" name="kd_kategori2" value="<?=$kd_kategori2?>"></label>
				 <div class="formRow">
                        <div class="grid3"><label>Kode Kategori 3 : </label></div>
                        <div class="grid9">
                            <span class="grid6"><? echo $kd_kategori1;echo $kd_kategori2;echo $kd_kategori3;  ?></span>
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
				<?}?>
					<div class="formRow">
                        <div class="grid3"><label>Kategori 3 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="nama_kategori3" name="nama_kategori3" value="<?=$nama_kategori3?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>kategori3'" /></p>
			</div>
		</div>
</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>