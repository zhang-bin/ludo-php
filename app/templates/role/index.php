<?php
$gTitle = '角色管理';
$gToolbox .= '<a href="'.url('permission/addRole').'" class="add">添加角色</a>';
include tpl('header'); 
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>角色名称</th>
            <th>角色描述</th>
            <th>查看权限</th>
            <th>查看用户</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($roles)) {foreach($roles as $role) {?>
    <tr>
        <td><?=$role['role']?></td>
        <td><?=$role['descr']?></td>
        <td><a href="<?=url('permission/permissions/'.$role['id'])?>">查看权限</a></td>
        <td><a href="<?=url('permission/user/roleId/'.$role['id'])?>">查看用户</a></td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <?=ACTION?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?=url('permission/changeRole/'.$role['id'])?>">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <?=MODIFY?>
                        </a>
                    </li>
                    <li>
                    <?php if (!$role['reserved']) {?>
                        <a name="del" title="删除角色" body="<?=CONFIRM_DELETE?>" href="<?=url('permission/delRole/'.$role['id'])?>">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            <?=DELETE?>
                        </a>
                    <?php }?>
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