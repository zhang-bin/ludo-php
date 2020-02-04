<?php
use Ludo\Support\Facades\Lang;

$gTitle = Lang::get('user.user_manage');
$gToolbox = sprintf('<a class="btn btn-primary btn-sm" href="%s"><i class="fa fa-user-plus"></i> %s</a>', url('permission/addUser'), Lang::get('user.user_add'));
include tpl('header');
?>
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th><?=Lang::get('user.username')?></th>
        <th><?=Lang::get('user.nickname')?></th>
        <th><?=Lang::get('base.action')?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)) { foreach ($users as $user) {?>
        <tr>
            <td><a href="<?=url('permission/viewUser/'.$user['id'])?>"><?=$user['username']?></a></td>
            <td><?=$user['nickname']?></td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?=Lang::get('base.action')?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="<?=url('permission/modifyUser/'.$user['id'])?>">
                                <span class="fa fa-edit" aria-hidden="true"></span>
                                <?=Lang::get('base.modify')?>
                            </a>
                        </li>
                        <li>
                            <?php if (!$user['reserved']) {?>
                                <a name="op" data-title="<?=Lang::get('base.delete')?>" data-body="<?=Lang::get('base.confirm_delete')?>" href="<?=url('permission/delUser/'.$user['id'])?>">
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