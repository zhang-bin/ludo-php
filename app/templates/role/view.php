<?php 
$gTitle = '查看角色权限';
include tpl('header');
?>
<div class="row-fluid form-horizontal">
    <div class="control-group">
        <label class="control-label">角色名称: </label>
        <div class="controls"><p class="form-control-static"><?=$role['role']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label">角色描述: </label>
        <div class="controls"><p class="form-control-static"><?=$role['descr']?></p></div>
    </div>
    <div class="control-group">
        <div class="controls">
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
                                    <input type="checkbox" checked disabled />
                                    &nbsp;
                                    <span><?=$operation?></span>
                                    &emsp;
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
                                    <input type="checkbox" checked disabled />
                                    &nbsp;
                                    <span><?=$operation?></span>
                                <?php }?>
                            </div>
                        </fieldset>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <a href="<?=url('permission/changeRole/'.$role['id'])?>" class="btn btn-primary">编辑</a>
        <a href="javascript:history.go(-1);" class="btn">返回</a>
    </div>
</div>
<?php include tpl('footer');?>