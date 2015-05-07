<?php 
$gTitle = '查看角色权限';
include tpl('header');
?>
<div class="form-horizontal">
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">角色名称: </label>
        <div class="col-sm-4"><p class="form-control-static"><?=$role['role']?></p></div>
    </div>
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">角色描述: </label>
        <div class="col-sm-4"><p class="form-control-static"><?=$role['descr']?></p></div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10 ludo-tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#function" data-toggle="tab">功能模块</a></li>
                <li><a href="#menu" data-toggle="tab">菜单</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="function">
                    <?php foreach ($modulePermissions as $resource => $operations) {?>
                        <fieldset class="permission">
                            <legend>
                                <div class="resource"><?=$resource?></div>
                            </legend>
                            <div class="operation">
                                <?php foreach ($operations as $operation) {?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" checked disabled />
                                        <?=$operation?>
                                    </label>
                                <?php }?>
                            </div>
                        </fieldset>
                    <?php }?>
                </div>
                <div class="tab-pane" id="menu">
                    <?php foreach ($menuPermissions as $resource => $operations) {?>
                        <fieldset class="permission">
                            <legend>
                                <div class="resource"><?=$resource?></div>
                            </legend>
                            <div class="operation">
                                <?php foreach ($operations as $operation) {?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" checked disabled />
                                        <?=$operation?>
                                    </label>
                                <?php }?>
                            </div>
                        </fieldset>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="<?=url('permission/changeRole/'.$role['id'])?>" class="btn btn-primary"><?=MODIFY?></a>
            <a href="javascript:history.go(-1);" class="btn btn-default"><?=CANCEL?></a>
        </div>
    </div>
</div>
<?php include tpl('footer');?>