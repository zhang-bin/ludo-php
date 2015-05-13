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
 		Load::css('style');
		Load::js('placeholder');
		Load::js('common');
		Load::helper('MenuHelper');
		Load::js('bootstrap-select');
		View::loadCss();
	?>
    <!--[if lt IE 9]>
    <script src="<?=LD_PUBLIC_PATH.'/img/html5shiv.min.js'?>"></script>
    <script src="<?=LD_PUBLIC_PATH.'/img/respond.min.js'?>"></script>
    <![endif]-->
</head>
<body style="padding-top: 70px;background-color: #595C64;">
<header class="navbar navbar-inverse ludo-nav navbar-fixed-top" id="top" role="banner">
    <div class="row">
        <div class="pull-right" style="color:#93C0E6;margin: 5px 50px 5px 0;">
            <?php if(logined()) { ?>
                欢迎, <?=$_SESSION[USER]['nickname']?> |
            <?php } ?>
            <a href="<?=url('user/logout')?>">退出</a>
        </div>
    </div>
    <div class="row ludo-menu" style="padding: 0 10px;">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?=url()?>" class="navbar-brand">Ludo-PHP</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse">
            <?=Menu::menuRender()?>
        </nav>
    </div>
</header>
<div id="loading">Loading</div>
<div class="alert-messages hide" id="message-drawer">
    <div class="message">
  		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="position:absolute;top:5px;right:10px;">×</button>
  		<div class="message-inside">
    		<span class="message-text"></span>
  		</div>
	</div>
</div>
<div style="min-height: 600px;background-color: #ffffff;padding-bottom: 70px;">
<div class="container" >
	<div class="row-fluid page-header crumbs ludo-crumbs" style="padding:0px;">
		<?=Menu::navRender($gTitle, $gToolbox)?>
	</div>