<?php
use Ludo\Support\Facades\Lang;

$gTitle = Lang::get('user.user_view');
include tpl('header');
?>
<div class="form-horizontal">
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.username')?></label>
        <div class="col-sm-4"><p class="form-control-static"><?=$user['username']?></p></div>
    </div>
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.nickname')?></label>
        <div class="col-sm-4"><p class="form-control-static"><?=$user['nickname']?></p></div>
    </div>
    <div class="form-group">
        <label for="permission" class="col-sm-2 control-label"><?=Lang::get('user.role')?></label>
        <div class="col-sm-8">
            <table class="table table-bordered">
                <?php foreach ($userRoles as $role) {?>
                    <tr>
                        <td><?=$role['role']?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="javascript:history.go(-1);" class="btn btn-default"><?=Lang::get('base.cancel')?></a>
        </div>
    </div>
</div>
<?php include tpl('footer');?>