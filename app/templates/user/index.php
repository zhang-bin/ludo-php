<?php
include tpl('header');
?>
<form class="layui-form" id="userForm" data-table-tag="userTable" data-add-tag="addUser" data-add-url="<?=url('permission/addUser')?>">
    <blockquote class="layui-elem-quote quoteBox">
        <form class="layui-form">
            <div class="layui-inline layui-col-space20">
                <div class="layui-row">&nbsp;</div>
            </div>
            <div class="layui-inline layui-col-space20 pull-right">
                <a class="layui-btn layui-btn-normal" id="addUser">添加用户</a>
            </div>
        </form>
    </blockquote>

    <table id="userTable" data-url="<?=url('permission/userList')?>" lay-filter="userTable"></table>

    <!--操作-->
    <script type="text/html" id="operation">
        <a class="layui-btn layui-btn-xs" lay-event="popup" data-title-name="修改用户" data-url="<?=url('permission/changeUser/')?>{{d.id}}">编辑</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="popup" data-title-name="修改密码" data-url="<?=url('permission/changePassword/')?>{{d.id}}">修改密码</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="tips" data-title-name="确定删除吗?" data-url="<?=url('permission/delUser/')?>{{d.id}}">删除</a>
    </script>

    <script type="text/html" id="userTpl">
        <a href="<?=url('permission/viewUser/')?>{{d.id}}" class="layui-table-link row-view" data-title="查看用户">{{d.username}}</a>
    </script>

    <script type="text/html" id="enabledTpl">
        <input type="checkbox" name="enabled" lay-filter="enabled" lay-skin="switch" lay-text="启用|禁用" value="{{d.id}}" {{ d.enabled == 1 ? 'checked' : ''}}  />
    </script>
</form>
<?php View::startJs();?>
<script type="text/javascript">
layui.config({
    base : "/public/img/layuicms/js/"
});

layui.use(['common', 'table', 'form', 'jquery'], function(){
    var $ = layui.jquery;
    var column = [
        {field: 'username', title: '用户名', templet: '#userTpl'},
        {field: 'nickname', title: '昵称'},
        {field: 'createTime', title: '创建时间'},
        {field: 'enabled', title: '当前状态', templet: '#enabledTpl'},
        {title: '操作', toolbar: '#operation'}
    ];
    layui.common.tableRender('userForm', column);

    layui.form.on('switch(enabled)', function(obj) {
        if (obj.elem.checked) {
            var url = '<?=url('permission/enableUser')?>';
        } else {
            var url = '<?=url('permission/disabledUser')?>';
        }
        $.post(url, {'id': obj.elem.value}, function(result) {
            layui.common.ajaxHandler(result);
            return false;
        }, 'json');
    });

});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>