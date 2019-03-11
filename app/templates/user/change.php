<?php
$change = isset($_GET['id']) ? true : false;
include tpl('header');
Load::web('formSelect');
?>
<form class="layui-form" action="<?=$change ? url('permission/changeUser/'.$user['id']) : url('permission/addUser')?>">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="username" id="username" class="layui-input" value="<?=$user['username']?>" required="required" <?=$change ? 'readonly' : ''?> />
        </div>
        <?php if (!$change) {?>
        <div class="layui-form-mid layui-word-aux">初始密码: <?=Permission::DEFAULT_PASSWORD?></div>
        <?php }?>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">昵称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" id="nickname" name="nickname" value="<?=$user['nickname']?>" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-col-xs3">
            <select class="layui-input" name="role" xm-select="role">
                <?php foreach ($roles as $role) {?>
                    <option value="<?=$role['id']?>" <?=(!empty($userRoles) && in_array($role['id'], $userRoles)) ? 'selected' : ''?>><?=$role['role']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" id="id" name="id" value="<?=$user['id']?>" />
            <button class="layui-btn" lay-submit="" lay-filter="submit">确定</button>
            <button class="layui-btn layui-btn-primary close-layer">取消</button>
        </div>
    </div>
</form>
<?php View::startJs();?>
<script type="text/javascript">
layui.config({
    base : "/public/img/"
}).extend({
    common: 'layuicms/js/common',
    formSelects: 'formSelect/formSelects-v4'
});
layui.use(['form', 'element', 'jquery', 'common', 'formSelects'], function() {
    var form = layui.form;
    $ = layui.jquery;

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