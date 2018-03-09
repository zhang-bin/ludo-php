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
<body data-spy="scroll" data-target=".sidebar" style="background-color: #595C64;padding-top: 40px;">
<header class="navbar navbar-inverse navbar-fixed-top" style="margin-bottom: 0px;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?=url()?>" class="navbar-brand">Summer</a>
        </div>
        <nav class="navbar-collapse collapse bs-navbar-collapse">
            <?=Menu::menuRender()?>
            <div class="pull-right" style="color:#93C0E6;margin-top:15px;font-size:15px;">
                <?php if(logined()) { ?>
                    <?=Lang::get('game.welcome').', '.$_SESSION[USER]['nickname']?> <span>|</span>
                    <a href="<?=url('user/logout')?>"><?=Lang::get('game.logout')?></a>
                <?php } ?>
            </div>
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
    <div class="container-fluid">
        <div class="row-fluid page-header crumbs" style="padding:0px:">
            <?=Menu::navRender($gTitle, $gToolbox)?>
        </div>