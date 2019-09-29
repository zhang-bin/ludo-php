<?php
use Ludo\Support\Facades\Lang;

$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? Lang::get('user.role_change') : Lang::get('user.role_add');
include tpl('header');
?>
<form class="form form-horizontal" action="<?=$change ? url('permission/changeRole') : url('permission/addRole')?>">
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.role_name')?></label>
        <div class="col-sm-4">
            <input type="text" name="role" id="role" class="form-control" value="<?=$role['role']?>" required="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="descr" class="col-sm-2 control-label"><?=Lang::get('user.role_descr')?></label>
        <div class="col-sm-4">
            <input type="text" name="descr" class="form-control" id="descr" value="<?=$role['descr']?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="permission" class="col-sm-2 control-label"><?=Lang::get('user.permission')?></label>
        <div class="col-sm-8">
            <table class="table table-bordered">
                <?php foreach ($permissions as $policy => $permission) {?>
                    <tr>
                        <td><input type="checkbox" name="permission[]" value="<?=$policy?>" <?=(!empty($role['permissionPolicy']) && in_array($policy, $role['permissionPolicy'])) ? 'checked' : ''?> /></td>
                        <td><?=$permission['name']?></td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" id="id" name="id" value="<?=$role['id']?>" />
            <button type="submit" class="btn btn-success"><?=Lang::get('base.submit')?></button>
            <a href="javascript:history.go(-1);" class="btn btn-default"><?=Lang::get('base.cancel')?></a>
        </div>
    </div>
</form>
<?php include tpl('footer');?>