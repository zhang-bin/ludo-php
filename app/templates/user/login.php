<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=PROGRAM_CHARSET?>" />
	<title><?=SITE_TITLE.' : '.(isset($gTitle) ? strip_tags($gTitle) : '')?></title>
	<meta name="author" content="The great c2ms team" />
	<meta name="Copyright" content="版权声明 C2MS@2009 ALL RIGHTS RESERVED" />
	<meta name="description" content="" />
	<meta name="keywords" content="c2ms" />
	<link rel="shortcut icon" href="<?=rurl('img/favicon.ico')?>" type="image/x-icon" />
	<?php
		Load::js('jquery');
		Load::js('bootstrap');
		Load::js('common');
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
	<h1><a href="<?=url()?>"><img src="<?=turl('/img/logo.gif')?>" /></a></h1>
	<div class="control-group">
      	<input type="text" class="input-block-level" id="uname" name="uname" placeholder="<?=LG_USER_UNAME?>" />
      	<input type="password" class="input-block-level" id="password" name="password" placeholder="<?=LG_USER_PASSWORD?>" />
		<input type="hidden" id="timezoneOffset" name="timezoneOffset" />
		<input id="submitBtn" type="submit" class="btn btn-large btn-primary" value="<?=LG_USER_LOGIN?>" />
  	</div>
</form>
<div id="loading">Loading</div>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$("#timezoneOffset").val(new Date().getTimezoneOffset()/60);
  	$('#uname').focus();
 	var submitting = false; //prevent multiple submit flag
  	$('#form1').submit(function() {
	  	if ($("#uname").val() == "") {
			alert("<?=LG_USER_UNAME_EMPTY?>");
		  	return false;
	  	}
	  	if ($("#password").val() == "") {
		  	alert("<?=LG_USER_PASSWORD_EMPTY?>");
		  	return false;
	  	}
		if (submitting) return false;

	submitting = true;
	$.posting(this.action, $(this).serialize(), function(result) {
		submitting = false;
	   if(ajaxHandler(result)) return;
	});
    return false;
  });
});
//-->
</script>
</body>
</html>