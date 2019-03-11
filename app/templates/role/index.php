<?php
include tpl('header');
?>
<form class="layui-form" id="roleForm" data-table-tag="roleTable" data-add-tag="addRole" data-edit-title-name="修改角色"
      data-add-url="<?=url('permission/addRole')?>" data-edit-url="<?=url('permission/changeRole')?>">
    <blockquote class="layui-elem-quote quoteBox">
        <form class="layui-form">
            <div class="layui-inline layui-col-space20">
                <div class="layui-row">&nbsp;</div>
            </div>
            <div class="layui-inline layui-col-space20 pull-right">
                <a class="layui-btn layui-btn-normal" id="addRole">添加角色</a>
            </div>
        </form>
    </blockquote>

    <table id="roleTable" data-url="<?=url('permission/roleList')?>" data-delete-url="<?=url('permission/delRole')?>" lay-filter="roleTable"></table>

    <!--操作-->
    <script type="text/html" id="operation">
        <a class="layui-btn layui-btn-xs" lay-event="edit" data-title-name="修改角色">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

    <script type="text/html" id="roleTpl">
        <a href="<?=url('permission/viewRole/')?>{{d.id}}" class="layui-table-link row-view" data-title="查看角色">{{d.role}}</a>
    </script>
</form>
<?php View::startJs();?>
<script type="text/javascript">
    layui.config({
        base : "/public/img/layuicms/js/"
    });
    layui.use(['common'], function(){
        var column = [
            {field: 'role', title: '名称', templet: '#roleTpl'},
            {field: 'descr', title: '描述'},
            {field: 'createTime', title: '创建时间'},
            {title: '操作', toolbar: '#operation'}
        ];
        layui.common.tableRender('roleForm', column);
    });
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>