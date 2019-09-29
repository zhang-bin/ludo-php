<?php
use Ludo\Support\Facades\Lang;

$gTitle = Lang::get('user.change_password');
include tpl('header');
?>
<form class="form form-horizontal" action="<?=url('user/changePassword')?>">
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.username')?></label>
        <div class="col-sm-4">
            <input type="text" name="username" id="username" class="form-control" value="<?=$user['username']?>" disabled />
        </div>
    </div>
    <div class="form-group">
        <label for="newPassword" class="col-sm-2 control-label"><?=Lang::get('user.password')?></label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="newPassword" id="newPassword" />
        </div>
    </div>
    <div class="form-group">
        <label for="confirmPassword" class="col-sm-2 control-label"><?=Lang::get('user.confirm_password')?></label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success"><?=Lang::get('base.submit')?></button>
            <a href="javascript:history.go(-1);" class="btn btn-default"><?=Lang::get('base.cancel')?></a>
        </div>
    </div>
</form>
<?php include tpl('footer');?>