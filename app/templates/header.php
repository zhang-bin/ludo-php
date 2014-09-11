<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="<?=PROGRAM_CHARSET?>">
	<title><?=SITE_TITLE?></title>
	<meta name="author" content="Maitrox" />
	<meta name="Copyright" content="Maitrox" />
	<meta name="description" content="" />
	<meta http-equiv="X-UA-Compatible" content="IE=7">
<!--	<link rel="shortcut icon" href="<?=rurl('img/favicon.ico')?>" type="image/x-icon" />-->
	<?php
		Load::js('jquery');
		Load::js('bootstrap');
 		Load::css('style');
		Load::js('placeholder');
		Load::js('common');
		Load::helper('MenuHelper');
		Load::js('bootstrap-select');
		Load::js('bootstrap-switch');
	?>
</head>
<body data-spy="scroll" data-target=".sidebar">
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
            	<span class="icon-bar"></span>
            	<span class="icon-bar"></span>
            	<span class="icon-bar"></span>
          	</button>
          	<a href="<?=url()?>" class="brand">MIDH</a>
          	<div class="nav-collapse collapse">
          		<?=Menu::menuRender()?>
          		<div class="pull-right" style="color:#93C0E6;margin-top:10px;">
					<?php if(User::logined()) { ?>
						Welcome, <?=$_SESSION[USER]['nickname']?> <span>|</span>
						<a href="<?=url('user/logout')?>">Logout</a> 
					<?php } ?>
				</div>
          	</div>
        </div>
	</div>
</div>
<div id="loading">Loading</div>
<div class="alert-messages hide" id="message-drawer">
    <div class="message">
  		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="position:absolute;top:5px;right:10px;">×</button>
  		<div class="message-inside">
    		<span class="message-text"></span>
  		</div>
	</div>
</div>
<div class="container" style="min-height: 458px;">
	<div class="row-fluid page-header crumbs" style="padding:0px:">
		<?=Menu::naviRender($gTitle, $gToolbox)?>
	</div>