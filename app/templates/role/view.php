<?php 
$gTitle = '查看角色';
include tpl('header');
?>
<form class="layui-form">
    <div class="layui-form-item layui-row">
        <label class="layui-form-label">角色名称</label>
        <div class="layui-input-inline">
            <input type="text" name="role" id="role" class="layui-input" value="<?=$role['role']?>" readonly />
        </div>
    </div>
    <div class="layui-form-item layui-row">
        <label class="layui-form-label">角色描述</label>
        <div class="layui-col-xs4">
            <input type="text" name="descr" class="layui-input" id="descr" value="<?=$role['descr']?>" readonly />
        </div>
    </div>
    <div class="layui-form-item layui-row">
        <label class="layui-form-label">权限</label>
        <div class="layui-col-xs6">
            <?php foreach ($permissions as $resource => $operations) { ?>
                <div class="legend">
                <span class="legend-title layui-bg-gray">
                    <input type="checkbox" data-resource="<?=$resource?>" lay-filter="checkAll" title="<?=$permissionConf[$resource]['name']?>" <?=$permissionConf[$resource]['checked'] ? 'checked' : ''?> readonly />
                </span>
                    <div class="legend-content">
                        <?php foreach ($operations as $operation => $operationId) {?>
                            <input type="checkbox" data-resource-ref="<?=$resource?>" name="permission[<?=$operationId?>]" title="<?=$permissionConf[$resource]['operations'][$operation]['name']?>" <?=(!empty($rolePermissions) && in_array($operationId, $rolePermissions)) ? 'checked' : ''?> readonly />
                        <?php }?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <div class="layui-form-item layui-row">
        <div class="layui-input-block">
            <button class="layui-btn layui-btn-primary close-layer">取消</button>
        </div>
    </div>
</form>
<?php include tpl('footer');?>