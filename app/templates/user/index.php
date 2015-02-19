<?php
$gTitle = '用户管理';
$gToolbox .= '<a href="'.url('permission/addUser').'" class="add">添加用户</a>';
include tpl('header');
?>
<form class="form-inline" method="get" action="<?=url('permission/user')?>">
    <select name="role" class="selectpicker" data-live-search="true">
        <option value="0">所有角色</option>
        <?php foreach ($roles as $role) {?>
            <option value="<?=$role['id']?>" <?=($role['id'] == $_GET['roleId']) ? 'selected' : ''?>><?=$role['role']?></option>
        <?php }?>
    </select>
    <input type="submit" class="btn btn-small btn-primary" value="搜索" />
</form>
<table class="table table-hover">
    <thead>
    </thead>
    <tbody>
    <?php if (!empty($roles)) {foreach($roles as $role) {?>
    <tr>
    </tr>
    <?php }}?>
</tbody>
<?php include tpl('footer');?>