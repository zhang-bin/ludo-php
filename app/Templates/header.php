<?php
use App\Helpers\Load;
use Ludo\Support\Facades\Lang;
use Ludo\View\View;
use App\Helpers\Menu;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?=PROGRAM_CHARSET?>">
    <title><?=Lang::get('base.site_title')?></title>
    <meta name="author" content="Ludo" />
    <meta name="Copyright" content="Ludo" />
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=badge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    Load::web('jquery');
    Load::web('bootstrap');
    Load::web('select2');
    Load::web('adminlte');
    Load::web('common');
    View::loadCss();
    ?>

    <?php View::startJs();?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".table").on("click", "a[name=op]", function(){
                $(".modal-del").modal();
                $(".modal-del .modal-title").text($(this).attr("data-title"));
                if ($(this).attr("data-body") != '') {
                    $(".modal-del .modal-body p").text($(this).attr("data-body"));
                }
                $("#confirmProcess").attr("href", this.href);
                return false;
            });
            $("#confirmProcess").click(function(){
                $.posting($(this).attr("href"), {}, function(result) {
                    if(ajaxHandler(result)) return;
                    return false;
                }, "json");
                return false;
            });

            $("form.form").submit(function(){
                $.posting($(this).attr("action"), $(this).serialize(), function(result) {
                    ajaxHandler(result);
                }, "json");
                return false;
            });
            $('.select2').select2();
        });
    </script>
    <?php View::endJs();?>
</head>
<body class="skin-blue sidebar-mini" style="height: auto; min-height: 100%;">
<div class="wrapper" style="height: auto; min-height: 100%;">
    <header class="main-header">
        <a href="<?=url()?>" class="logo">
            <span class="logo-mini"><b>LD</b></span>
            <span class="logo-lg"><b>Ludo</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?=imageUrl('face.jpg')?>" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?=$_SESSION[USER]['nickname']?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?=url('user/modifyPassword')?>"><?=Lang::get('base.modify_password')?></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?=url('user/modifyPassword')?>"><?=Lang::get('base.sign_out')?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <?=Menu::render()?>

    <div class="content-wrapper" style="min-height: 901px;">
        <section class="content-header">
            <h1>
                <?=$gTitle?>
            </h1>
            <?=Menu::breadcrumb()?>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="box-tools"><?=$gToolbox?></div>
                        </div>
                        <div class="box-body">