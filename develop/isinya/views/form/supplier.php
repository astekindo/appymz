<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fSupplier").validate();
		$("#since").datepicker();
		$('#pkp').attr('checked' ) == true
      });
    </script>
    
<form action="<?=base_url();?>supplier/save" id="fSupplier" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid9">
			<div class="whead"><h6>Input Supplier</h6><div class="clear"></div></div>
			<div class="formRow">
				<label for="id_supplier"><input type="hidden" name="id_supplier" id="id_supplier" value="<?=$id_supplier?>"></label>
                    <div class="formRow">
                        <div class="grid1"><label>Kode :</label></div>
                        <div class="grid5">
                            <span class="grid6"><input type="text" style="width:60px;text-align:right;" name="kd_supplier" id="kd_supplier" value="<?=$kd_supplier?>" readonly /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow fluid">
                        <div class="grid1"><label>Nama : </label></div>
						<div class="grid4"><input type="text" style="width:250px;text-align:left;" id="nama_supplier" name="nama_supplier" value="<?=$nama_supplier?>" class="required " maxlength="100" /></div>
						<div class="grid2"> Alias : <input type="text" style="width:40px;text-align:left;" id="alias_supplier" name="alias_supplier" maxlength="2" minlength="2" value="<?=$alias_supplier?>" class="required" /></div>
						<div class="grid4"> PIC : <input type="text" style="width:200px;text-align:left;" id="pic" name="pic" value="<?=$pic?>" class="required" /></div>
						<div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label>Alamat :</label></div>
                        <div class="grid9"><textarea rows="5" cols="" name="alamat" class="auto"><?=$alamat?></textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow fluid">
                        <div class="grid1"><label>Telpon :</label></div>
						<div class="grid3"><input type="text" id="telpon" name="telpon" value="<?=$telpon?>" style="width:150px;text-align:left;" class="required" maxlength="50"/></div>
						<div class="grid3">Fax :  <input type="text" id="fax" name="fax" value="<?=$fax?>" style="width:150px;text-align:left;" class="required" maxlength="50"/></div>
						<div class="grid4">Email :  <input type="text" id="email" name="email" value="<?=$email?>" style="width:200px;text-align:left;" class="required email" /></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow fluid">
                        <div class="grid1"><label>Pkp :</label></div>
                        <div class="grid2 on_off">
							<div class="floatL mr10"><input type="checkbox" id="pkp" <?=$pkp?> name="pkp" /></div>
                        </div>
                        <div class="grid1"><label>Npwp :</label></div>
                        <div class="grid3">
                            <span class="grid6"><input type="text" id="npwp" name="npwp" value="<?=$npwp?>"  style="width:150px;text-align:left;" class=" number" maxlength="20" /></span>
                        </div>
                        <div class="grid1"><label>Status :</label></div>
                        <div class="grid2 on_off">
							<div class="floatL mr10"><input type="checkbox" id="status" <?=$status?> name="status" /></div>
                        </div>
                        <div class="clear"></div>
                    </div>
				<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>supplier'" /></p>
			</div>
		</div>
	</div>
</div>
</fieldset>
</form>
<?php $this->load->view('footer');?>