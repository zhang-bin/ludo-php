<?php
$gTitle = '查看用户';
include tpl('header');
?>
<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" name="username" id="username" class="layui-input" value="<?=$user['username']?>" required="required" readonly />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">昵称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" id="nickname" name="nickname" value="<?=$user['nickname']?>" readonly />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
            <?php foreach ($userRoles as $userRole) {?>
                <input type="checkbox" title="<?=$userRole['role']?>" checked disabled class="layui-bg-green" />
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