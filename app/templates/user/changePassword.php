<?php
$gTitle = '修改密码';
include tpl ('header');
?>
<form class="layui-form" action="<?=$url?>">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="username" id="username" class="layui-input" value="<?=$user['username']?>" required="required" readonly />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" id="newPassword" name="newPassword" value="" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" id="confirmPassword" name="confirmPassword" value="" />
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
    base : "/public/img/layuicms/js/"
});
layui.use(['form', 'element', 'jquery', 'common'], function() {
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