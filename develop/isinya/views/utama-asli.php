<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header_login');

?>
<!-- Top line begins -->
<div id="top">
	<div class="wrapper">
    	<a href="#" title="" class="logo"><img src="images/logo.png" alt="" /></a>
        </div>
</div>
<!-- Top line ends -->

<div class="loginWrapper">
	<form action="" method="post" id="fLogin" name="fLogin">
        <div class="loginPic">
            <a href="#" title=""></a>
            <span>Login</span>
            <div class="loginActions">
                <div><a href="#" title="Change user" class="logleft flip"></a></div>
                <div><a href="#" title="Forgot password?" class="logright"></a></div>
            </div>
        </div>
        
        <input type="text" name="lUser" id="lUser" placeholder="Username" class="loginUsername" />
        <input type="password" name="lPass" id="lPass" placeholder="Password" class="loginPassword" />
        
        <div class="logControl">
            <div class="memory"><input type="checkbox" checked="checked" class="check" id="remember1" /><label for="remember1">Remember me</label></div>
            <input type="submit" name="login" id="login" value="Login" class="buttonM bBlue" />
            <div class="clear"></div>
        </div>
    </form>

	
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