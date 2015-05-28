<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#farea").validate();
      });
    </script>
<form action="<?=base_url();?>area/save" id="farea" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid6">
			<div class="whead"><h6>Input Area</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_kategori1"><input type="hidden" name="id_area" id="id_area" value="<?=$id_area?>" /></label>
                    <div class="formRow">
                        <div class="grid3"><label>Nama Area : </label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="nama_area" name="nama_area" value="<?=$nama_area?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Alamat : </label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="alamat" name="alamat" value="<?=$alamat?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Keterangan : </label></div>
                        <div class="grid9">
                            <span class="grid6"><textarea id="keterangan" name="keterangan"><?=$keterangan?></textarea></span>
                        </div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>area'" /></p>
			</div>
		</div>
</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>