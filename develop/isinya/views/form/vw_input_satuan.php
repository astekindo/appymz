<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fSatuan").validate();
      });
    </script>
<div class="wrapper">
	<form action="<?=base_url();?>satuan/save" id="fSatuan" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Satuan</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Nama Satuan:</label></div>
		                <div class="grid9 noSearch">
							<input type="hidden" name="id_satuan" id="id_satuan" value="<?=$id_satuan?>" />
							<input type="text" id="nm_satuan" name="nm_satuan" value="<?=$nm_satuan?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Keterangan:</label></div>
		                <div class="grid9">
							<textarea rows="5" cols="" name="keterangan" id="keterangan"><?=$keterangan?></textarea>
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>satuan'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>

<?php $this->load->view('footer');?>