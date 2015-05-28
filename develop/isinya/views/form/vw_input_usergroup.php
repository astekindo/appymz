<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fUsergroup").validate();
      });
    </script>
<div class="wrapper">
	<form action="<?=base_url();?>usergroup/save" id="fUsergroup" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input usergroup</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Nama usergroup:</label></div>
		                <div class="grid9 noSearch">
							<input type="hidden" name="id_usergroup" id="id_usergroup" value="<?=$id_usergroup?>" />
							<input type="text" id="nama_usergroup" name="nama_usergroup" value="<?=$nama_usergroup?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Deskripsi:</label></div>
		                <div class="grid9">
							<textarea rows="5" cols="" name="deskripsi" id="deskripsi"><?=$deskripsi?></textarea>
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>usergroup'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>

<?php $this->load->view('footer');?>