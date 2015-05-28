<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fRekening").validate();
      });
    </script>
<div class="wrapper">
	<form action="<?=base_url();?>rekening/save" id="fRekening" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Rekening</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Kode Rekening:</label></div>
		                <div class="grid9 noSearch">
							<input type="hidden" name="id_rekening" id="id_rekening" value="<?=$id_rekening?>" />
							<input type="text" id="kd_rekening" name="kd_rekening" value="<?=$kd_rekening?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Nama Rekening:</label></div>
		                <div class="grid9">
							<input type="text" id="nm_rekening" name="nm_rekening" value="<?=$nm_rekening?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>rekening'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>

<?php $this->load->view('footer');?>