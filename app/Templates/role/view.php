<?php
use Ludo\Support\Facades\Lang;

$gTitle = Lang::get('user.role_view');
include tpl('header');
?>
    <div class="form-horizontal">
        <div class="form-group">
            <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.role_name')?></label>
            <div class="col-sm-4"><p class="form-control-static"><?=$role['role']?></p></div>
        </div>
        <div class="form-group">
            <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.role_descr')?></label>
            <div class="col-sm-4"><p class="form-control-static"><?=$role['descr']?></p></div>
        </div>
        <div class="form-group">
            <label for="permission" class="col-sm-2 control-label"><?=Lang::get('user.permission')?></label>
            <div class="col-sm-8">
                <table class="table table-bordered">
                    <?php foreach ($permissions as $policy => $permission) {?>
                        <tr>
                            <td><input disabled type="checkbox" name="permission[]" value="<?=$policy?>" <?=(!empty($role['permissionPolicy']) && in_array($policy, $role['permissionPolicy'])) ? 'checked' : ''?> /></td>
                            <td><?=$permission['name']?></td>
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