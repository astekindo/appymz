<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fUser").validate();
      });
    </script>
<div class="wrapper">
	<form action="<?=base_url();?>user/save" id="fBlok" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input User</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Username:</label></div>
		                <div class="grid9">
							<input type="hidden" name="id_user" id="id_user" value="<?=$id_user?>">
							<input type="text" id="username" name="username" value="<?=$username?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Password:</label></div>
		                <div class="grid9">
							<input type="password" id="passwd" name="passwd" value="<?=$passwd?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Email:</label></div>
		                <div class="grid9">
							<input type="text" id="email" name="email" value="<?=$email?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Usergroup:</label></div>
		                <div class="grid9">
							<span class="grid6"><?= form_dropdown('id_usergroup', $listusergroup, $id_usergroup); ?></span>
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>user'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<?php $this->load->view('footer');?>