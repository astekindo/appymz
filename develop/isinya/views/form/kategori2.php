<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fkategori").validate();
      });
    </script>

<form action="<?=base_url();?>kategori2/save" id="fkategori" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid6">
			<div class="whead"><h6>Input Kategori 2</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_kategori2"><input type="hidden" name="id_kategori2" value="<?=$id_kategori2?>"></label>
				<label for="kd_kategori2"><input type="hidden" name="kd_kategori2" value="<?=$kd_kategori2?>"></label>
                 <? if ($id_kategori2=="") { ?>
				 <div class="formRow">
                        <div class="grid3"><label>Kategori 1 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_kategori1', $listnama_kategori1, $kd_kategori1); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
				<? }else{ ?>
				<label for="kd_kategori1"><input type="hidden" name="kd_kategori1" value="<?=$kd_kategori1?>"></label>
				 <div class="formRow">
                        <div class="grid3"><label>Kode Kategori 2 : </label></div>
                        <div class="grid9">
                            <span class="grid6"><? echo $kd_kategori1;echo $kd_kategori2; ?></span>
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
				<?}?>
                    <div class="formRow">
                        <div class="grid3"><label>Kategori 2 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="nama_kategori2" name="nama_kategori2" value="<?=$nama_kategori2?>" class="required" minlength="2" maxlength="20"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>kategori2'" /></p>
			</div>
		</div>
</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>