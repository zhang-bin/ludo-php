<?php 
$change = isset($_GET['id']) ? true : false;
include tpl('header');
?>
<form class="layui-form" action="<?=$change ? url('permission/changeRole/'.$role['id']) : url('permission/addRole')?>">
    <div class="layui-form-item">
        <label class="layui-form-label">角色名称</label>
        <div class="layui-input-inline">
            <input type="text" name="role" id="role" class="layui-input" value="<?=$role['role']?>" required="required" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色描述</label>
        <div class="layui-col-xs4">
            <input type="text" name="descr" class="layui-input" id="descr" value="<?=$role['descr']?>" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">权限</label>
        <div class="layui-col-xs6">
            <?php foreach ($permissions as $resource => $operations) { ?>
            <div class="legend">
                <span class="legend-title layui-bg-gray">
                    <input type="checkbox" data-resource="<?=$resource?>" lay-filter="checkAll" title="<?=$permissionConf[$resource]['name']?>" <?=$permissionConf[$resource]['checked'] ? 'checked' : ''?> />
                </span>
                <div class="legend-content">
                <?php foreach ($operations as $operation => $operationId) {?>
                    <input type="checkbox" data-resource-ref="<?=$resource?>" name="permission[<?=$operationId?>]" title="<?=$permissionConf[$resource]['operations'][$operation]['name']?>" <?=(!empty($rolePermissions) && in_array($operationId, $rolePermissions)) ? 'checked' : ''?> />
                <?php }?>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="submit">确定</button>
            <button class="layui-btn layui-btn-primary close-layer">取消</button>
        </div>
    </div>
</form>
<?php View::startJs();?>
<script type="text/javascript">
layui.config({
    base : "/public/img/layuicms/js/"
});
layui.use(['form', 'element', 'jquery', 'common'], function() {
    var form = layui.form;
    $ = layui.jquery;
    form.on('checkbox(checkAll)', function(data) {
        var resource = $(this).attr("data-resource");
        if (data.elem.checked) {
            $("[data-resource-ref="+resource+"]").prop("checked", true);
        } else {
            $("[data-resource-ref="+resource+"]").prop("checked", false);
        }
        form.render();
    });

    form.on('submit(submit)', function(data) {
        var submit = $(this);
        submit.text("提交中...").attr("disabled","disabled").addClass("layui-disabled");
        $.post(data.form.action, data.field, function(result){
            submit.text("确定").removeAttr("disabled").removeClass("layui-disabled");
            return layui.common.ajaxHandler(result);
        }, 'json');
        return false;
    });
});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>