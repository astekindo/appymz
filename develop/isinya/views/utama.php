<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header_login');

?>

<!-- Top line begins -->
<div id="top-depan">
	<div class="wrapper">
    	<a href="#" title="" class="logo"><img src="images/logo.png" alt="" /></a>
    </div>
</div>

<link rel="stylesheet" href="<?=base_url()?>asset/css/style-login.css">

	<div style="padding: 130px 0 0 0;">
		<div class="login">
			<h1>MITRA BANGUNAN SUPERMARKET</h1>
			<form action="" method="post" id="fLogin" name="fLogin">
				<p><input type="text" name="lUser" id="lUser" placeholder="Username"></p>
				<p><input type="password" name="lPass" id="lPass" placeholder="Password"></p>
				<p class="remember_me">
				<label>
					<input type="checkbox" name="remember_me" id="remember_me">
					Remember me on this computer
				</label>
				</p>
				<p class="submit"><input input type="submit" name="login" id="login" value="Login"></p>
			</form>
		</div>
    </div>
		

<!--
<div id="formRegister">
<form id="fRegister" name="fRegister" action="" method="post">
	
		<label>Company Name:</label>
			<input type="text" id="rCompany" name="rCompany" class="required" />
		<label>Email:</label>
			<input type="text" id="rEmail" name="rEmail" class="required email" />
		<label>Password:</label>
			<input type="password" id="rPass" name="rPass" class="required" />
			<input type="submit" name="register" id="register" value="Register" class="submit" />
</form>	
</div>
-->

</body>
</html>