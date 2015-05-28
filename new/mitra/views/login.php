<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login Form - <?= $this->config->item('app_title') ?></title>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/login.css"/>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.loadmask.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.alerts.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.validation.css" media="screen"/>
<!--SCRIPTS-->
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery/jquery.loadmask.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery/jquery.alerts.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/js/jquery/login.js"></script>
</head>
<body>
<div class="loading">
<div id="wrapper">
    <div class="user-icon"></div>
    <div class="pass-icon"></div>

<form name="login-form" id="frmLogin" class="login-form" action="<?= site_url('auth/login') ?>" method="post">
    <div class="header">
    	<img src="<?= base_url() ?>assets/img/logo-ymz1.png" alt="">
    	<h1>Log In</h1>
    </div>
    <div class="content">
    <?php if($boleh):?>
		<input name="username" id="username" type="text" class="input username validate[required]" placeholder="Username" value="" tabindex="1"/>
		<input name="password" id="password" type="password" class="input password validate[required]" placeholder="Password" value="" tabindex="2"/>
    <?php else:?>
        <h3 style="color:#f00;text-align:center;padding: 50px 0 30px">access denied</h3>
    <?php endif;?>
    </div>

    <div class="footer">
    <?php if($boleh):?>
    	<input type="submit" name="submit" value="Login" class="button" tabindex="3"/>
    	<!--input type="checkbox" name="remember_me" id="remember_me">Remember me?-->
    <?php endif;?>
    </div>
</form>

</div>

<div class="gradient"></div>
</div>
</body>
</html>