<?php
use Ludo\Support\Facades\Lang;

$gTitle = Lang::get('user.role_manage');
$gToolbox = sprintf('<a class="btn btn-primary btn-sm" href="%s"><i class="fa fa-user-plus"></i> %s</a>', url('permission/addRole'), Lang::get('user.role_add'));
include tpl('header');
?>
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th><?=Lang::get('user.role_name')?></th>
            <th><?=Lang::get('user.role_descr')?></th>
            <th><?=Lang::get('base.action')?></th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($roles)) { foreach ($roles as $role) {?>
        <tr>
            <td><a href="<?=url('permission/viewRole/'.$role['id'])?>"><?=$role['role']?></a></td>
            <td><?=$role['descr']?></td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?=Lang::get('base.action')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="<?=url('permission/modifyRole/'.$role['id'])?>">
                                <span class="fa fa-edit" aria-hidden="true"></span>
                                <?=Lang::get('base.modify')?>
                            </a>
                        </li>
                        <li>
                            <?php if (!$role['reserved']) {?>
                                <a name="op" data-title="<?=Lang::get('base.delete')?>" data-body="<?=Lang::get('base.confirm_delete')?>" href="<?=url('permission/delRole/'.$role['id'])?>">
                                    <span class="fa fa-trash" aria-hidden="true"></span>
                                    <?=Lang::get('base.delete')?>
                                </a>
                            <?php }?>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php }}?>
    </tbody>
</table>
<?=$page?>
<?php include tpl('footer');?>