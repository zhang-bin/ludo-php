<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? '修改用户' : '添加用户';
include tpl ('header');
Load::js('bootstrap-multiselect');
?>
<form method="post" class="form-horizontal form" action="<?=$change ? url('permission/changeUser') : url('permission/addUser')?>">
    <div class="form-group">
        <label for="username" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-4">
            <?php if($user['id'] == '') {?>
                <input type="text" name="username" id="username" class="form-control" value="<?=$user['username']?>" required="required" />
                <span class="help-block">初始密码: <?=Permission::DEFAULT_PASSWORD?></span>
            <?php } else {?>
                <p class="form-control-static"><?=$user['username']?></p>
            <?php }?>
        </div>
    </div>
    <div class="form-group">
        <label for="nickname" class="col-sm-2 control-label">昵称</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="nickname" name="nickname" value="<?=$user['nickname']?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">角色</label>
        <div class="col-sm-4">
            <select name="role[]" id="role" class="selectpicker" multiple="multiple" class="form-control" data-size="10">
                <?php foreach ($roles as $role) {?>
                    <option value="<?=$role['id']?>" <?=(is_array($userRoles) && in_array($role['id'], $userRoles)) ? 'selected' : ''?>><?=$role['role']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" id="id" name="id" value="<?=$user['id']?>" />
            <input id="submitBtn" type="submit" value="<?=SUBMIT?>" class="btn btn-success" />
            <input type="hidden" name="_token" value="<?=csrf_token()?>" />
            <input type="button" onclick="javascript:history.back();" value="<?=CANCEL?>" class="btn btn-default" />
        </div>
    </div>
</form>
<?php View::startJs();?>
<script type="text/javascript">
$(document).ready(function(){

});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>