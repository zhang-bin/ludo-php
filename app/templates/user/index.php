<?php
$gTitle = '用户管理';
$gToolbox .= '<a href="'.url('permission/addUser').'" class="add">添加用户</a>';
include tpl('header');
?>
<form class="form form-inline" method="get" action="<?=url('permission/user')?>">
    <select name="roleId" class="selectpicker" data-live-search="true">
        <option value="0">所有角色</option>
        <?php foreach ($roles as $role) {?>
            <option value="<?=$role['id']?>" <?=($role['id'] == $_GET['roleId']) ? 'selected' : ''?>><?=$role['role']?></option>
        <?php }?>
    </select>
    <input type="submit" class="btn btn-small btn-primary" value="搜索" />
</form>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>用户名</th>
        <th>昵称</th>
        <th>创建时间</th>
        <th>是否可用</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)) {foreach($users as $user) {?>
        <tr>
            <td><?=$user['username']?></td>
            <td><?=$user['nickname']?></td>
            <td><?=$user['createTime']?></td>
            <td><?=$user['enabled'] ? '是' : '否'?></td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-small dropdown-toggle" data-toggle="dropdown">
                        <?=ACTION?>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="edit" href="<?=url('permission/changeUser/'.$user['id'])?>">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                <?=MODIFY?>
                            </a>
                        </li>
                        <li>
                            <a name="del" title="删除用户" body="<?=CONFIRM_DELETE?>" href="<?=url('permission/delUser/'.$user['id'])?>">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                <?=DELETE?>
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php }}?>
    <tr>
        <?php if(!empty($pager)){?><td style="text-align: right;" colspan="9"><?=!empty($pager) ? $pager: '&nbsp;'?></td><?php }?>
    </tr>
    </tbody>
</table>
<?php include tpl('footer');?>