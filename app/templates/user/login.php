<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?=PROGRAM_CHARSET?>">
    <title><?=SITE_TITLE?></title>
    <meta name="author" content="Maitrox" />
    <meta name="Copyright" content="Maitrox" />
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=badge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		Load::js('jquery');
		Load::js('bootstrap');
		Load::js('common');
        Load::js('placeholder');
        View::loadCss();
	?>
<style>
body{
	background-color:#f5f5f5;	
}
#loading {
	right: 0;
	top: 0;
	width: 60px;
	height: 14px;
	vertical-align: middle;
	position: fixed;
	padding: 5px;
	padding-right: 46px;
	font-weight: bold;
	background: #f36 url(loading4.gif) no-repeat right;
	color: #fff;
	display: none;
	z-index: 99999;
	border-box:content-box;
	-webkit-box-sizing:content-box;
	-moz-box-sizing:content-box;
}
#form1{
	background-color:#fff;
	max-width:320px;
	margin: 0 auto 20px;
	padding: 19px 29px 29px;
	border: 1px solid #e5e5e5;
	-webkit-border-radius:5px;
	-webkit-box-shadow:0 1px 2px rgba(0,0,0,.05);
	border-radius:5px;
	-moz-box-shadow:0 1px 2px rgba(0,0,0,.05);
}
#form1 .input-block-level{
	font-size:16px;
	height:auto;
	margin-bottom:15px;
	padding:7px 9px;
}
</style>
</head>
<body style="padding-top:40px;">
<form class="form-horizontal" id="form1" method="post" action="<?=url('user/login')?>">
	<h1><a href="<?=url()?>"></a></h1>
    <div class="form-group">
        <input type="text" class="form-control input-block-level" id="username" name="username" placeholder="用户名" />
    </div>
    <div class="form-group">
      	<input type="password" class="form-control input-block-level" id="password" name="password" placeholder="密码" />
    </div>
    <div class="form-group">
		<input type="hidden" id="timezoneOffset" name="timezoneOffset" />
		<input id="submitBtn" type="submit" class="btn btn-lg btn-primary" value="<?=SIGN_IN?>" />
		<input type="hidden" id="jurl" name="jurl" value="<?=$_GET['jurl']?>" />
  	</div>
</form>
<div id="loading">Loading</div>
<?php View::loadJs();?>
<script type="text/javascript">
$(document).ready(function() {
  	$('#username').focus();

  	$('#form1').submit(function() {
        $.formSubmit(this);
        return false;
    });
});
</script>
</body>
</html>