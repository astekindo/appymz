<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header_login');
//$this->load->view('header');
?>
<div style="margin-top:230px">
</div>
<div class="loginWrapper">
<div id="formLogin">
<form id="fLogin" name="fLogin" action="" method="post">
	
		<label>Username:</label>
			<input type="text" id="lUser" name="lUser" class="required" />
		<label>Password:</label>
			<input type="password" id="lPass" name="lPass" class="required" />
			<input type="submit" name="login" id="login" value="log in" class="submit" />
			<!--<div id="lingNew">New Member</div>-->
</form>	
</div>
<div id="formRegister">
<form id="fRegister" name="fRegister" action="" method="post">
	
		<label>Company Name:</label>
			<input type="text" id="rCompany" name="rCompany" class="required" />
		<label>Email:</label>
			<input type="text" id="rEmail" name="rEmail" class="required email" />
		<label>Password:</label>
			<input type="password" id="rPass" name="rPass" class="required" />
			<input type="submit" name="register" id="register" value="Register" class="submit" />
			<!--<div id="lingLogin">Already Member</div>-->
</form>	
</div>
</div>

</body>
</html>