<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fMenu").validate();
		$('#bview').attr('checked' ) == true
		$('#binsert').attr('checked' ) == true
		$('#bupdate').attr('checked' ) == true
		$('#bdelete').attr('checked' ) == true
      });
    </script>
<form action="<?=base_url();?>menu/save" id="fMenu" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid6">
			<div class="whead"><h6>INPUT MENU</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_menu"><input type="hidden" name="id_menu" id="id_menu" value="<?=$id_menu?>"></label>
                    <div class="formRow">
                        <div class="grid3"><label>PARENT MENU :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('id_parent', $listparents, $id_parent);?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>NAMA MENU :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="nama_menu" name="nama_menu" value="<?=$nama_menu?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>SEQUENCE :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="sequence" name="sequence" value="<?=$sequence?>" class="required number" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>CONTROLLER :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="controller" name="controller" value="<?=$controller?>" class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow fluid">
                        <div class="grid3"><label>BUTTON	:</label></div>
						<div class="grid9 on_off">
                            <div class="floatL mr10">VIEW<input type="checkbox" id="bview" <?=$bview?> name="bview" /></div>
                            <div class="floatL mr10">INSERT<input type="checkbox" id="binsert" <?=$binsert?> name="binsert" /></div>
                            <div class="floatL mr10">UPDATE<input type="checkbox" id="bupdate" <?=$bupdate?> name="bupdate" /></div>
                            <div class="floatL mr10">DELETE<input type="checkbox" id="bdelete" <?=$bdelete?> name="bdelete" /></div>
						</div>
						<div class="clear"></div>
					</div>
                    <div class="formRow">
                        <div class="grid3"><label>DESKRIPSI :</label></div>
                        <div class="grid9"><textarea rows="5" cols="" name="deskripsi" class="auto"><?=$deskripsi?></textarea></div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>menu'" /></p>

			</div>
		</div>
	</div>
</div>
</form>

<?php $this->load->view('footer');?>