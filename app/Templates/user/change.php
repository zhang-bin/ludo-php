<?php
use Ludo\Support\Facades\Lang;

$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? Lang::get('user.user_change') : Lang::get('user.user_add');
include tpl('header');
?>
<form class="form form-horizontal" action="<?=$change ? url('permission/changeUser') : url('permission/addUser')?>">
    <div class="form-group">
        <label for="username" class="col-sm-2 control-label"><?=Lang::get('user.username')?></label>
        <div class="col-sm-4">
            <input type="text" name="username" id="username" class="form-control" value="<?=$user['username']?>" required="required" <?=$change ? 'disabled' : ''?> />
        </div>
    </div>
    <div class="form-group">
        <label for="descr" class="col-sm-2 control-label"><?=Lang::get('user.nickname')?></label>
        <div class="col-sm-4">
            <input type="text" name="nickname" class="form-control" id="nickname" value="<?=$user['nickname']?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label"><?=Lang::get('user.role')?></label>
        <div class="col-sm-8">
            <select name="role[]" id="role" class="select2 form-control" multiple="multiple" data-size="10">
                <?php foreach ($roles as $role) {?>
                    <option value="<?=$role['id']?>" <?=(!empty($user['roles']) && in_array($role['id'], $user['roles'])) ? 'selected' : ''?>><?=$role['role']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" id="id" name="id" value="<?=$user['id']?>" />
            <button type="submit" class="btn btn-success"><?=Lang::get('base.submit')?></button>
            <a href="javascript:history.go(-1);" class="btn btn-default"><?=Lang::get('base.cancel')?></a>
        </div>
    </div>
</form>
<?php include tpl('footer');?>