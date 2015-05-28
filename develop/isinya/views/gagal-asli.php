<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<div id="wrap">

	<div id="header">
		<div id="site-name">&nbsp;</div>
		<!--<ul id="nav">
		<?= $menu; ?>
		</ul>-->
	</div>
	
	<div id="content-wrap">
	
		<div id="content">
		
		<form action="" method="post" class="form colours">
		<fieldset>
			<legend>Login</legend>
			<p>Username/Password anda salah..</p>
			<p><label><a href="<?=base_url(); ?>">login</a></label></p>
	
		</fieldset>
	</form>
			<br><br><br>
		</div>
		
		<!--<div id="poweredby"><a href="http://farcry.daemon.com.au/"><img src="wsimages/mollio.gif" alt="FarCry - Mollio" /></a></div>-->
		
	</div>

</div>
<?php $this->load->view('footer');?>