<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fkategori").validate();
      });
    </script>
<form action="<?=base_url();?>kategori1/save" id="fkategori" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid6">
			<div class="whead"><h6>Input Kategori 1</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_kategori1"><input type="hidden" name="id_kategori1" id="id_kategori1" value="<?=$id_kategori1?>"></label>
				<label for="kd_kategori1"><input type="hidden" style="width:60px;text-align:right;" name="kd_kategori1" id="kd_kategori1" value="<?=$kd_kategori1?>"></label>

                    <div class="formRow">
                        <div class="grid3"><label>Kategori 1 :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="nama_kategori1" name="nama_kategori1" value="<?=$nama_kategori1?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>kategori1'" /></p>
			</div>
		</div>
</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>