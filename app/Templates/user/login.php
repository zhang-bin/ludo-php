<?php

use App\Helpers\Load;
use Ludo\Support\Facades\Lang;
use Ludo\View\View;

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
    Load::web('adminlte');
    Load::web('common');
    View::loadCss();
    ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo"><b><?=Lang::get('base.site_title')?></b></div>
    <div class="login-box-body">
        <form action="<?=url('user/login')?>" method="post" id="login">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="<?=Lang::get('base.username')?>" />
                <span class="fa fa-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="<?=Lang::get('base.password')?>" />
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn-lg btn-primary btn-block btn-flat"><?=Lang::get('base.sign_in')?></button>
                    <input type="hidden" name="callback" value="<?=$_GET['callback']?>" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="alert alert-danger alert-box" id="alert-box" style="display: none;" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <p></p>
</div>
<?php View::startJs();?>
<script type="text/javascript">
$(document).ready(function(){
    $("#login").submit(function() {
        $.formSubmit('login');
        return false;
    });
});
</script>
<?php View::endJs();?>

<?php
View::loadCss();
View::loadJs();
?>
</body>
</html>