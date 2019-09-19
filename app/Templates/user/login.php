<!DOCTYPE html>
<html lang="en" class="loginHtml">
<head>
    <meta charset="<?=PROGRAM_CHARSET?>">
    <title><?=SITE_TITLE?></title>
    <meta name="author" content="Fantang" />
    <meta name="Copyright" content="Fantang" />
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=badge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
        Load::web('layui');
        Load::web('layuicms');
        View::loadCss();
	?>
</head>
<body class="loginBody">
    <form class="layui-form">
        <div style="background-color: #fff; padding: 20px;">
            <h4 style="text-align: center; font-size: 18px;">番糖管理平台</h4>
            <hr class="layui-bg-green">
            <br />
            <div class="layui-form-item input-item">
                <label for="username">用户名</label>
                <input type="text" placeholder="请输入用户名" autocomplete="off" name="username" id="username" class="layui-input" lay-verify="required">
            </div>
            <div class="layui-form-item input-item">
                <label for="password">密码</label>
                <input type="password" placeholder="请输入密码" autocomplete="off" name="password" id="password" class="layui-input" lay-verify="required">
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-block" lay-filter="login" lay-submit>登录</button>
                <input type="hidden" id="jurl" name="jurl" value="<?=$_GET['jurl']?>" />
            </div>
        </div>
    </form>
<?php View::loadJs();?>
<script type="text/javascript">
layui.config({
    base : "/public/img/layuicms/js/"
});
layui.use(['form','layer','jquery', 'common'],function(){
    var form = layui.form;
    $ = layui.jquery;

    //登录按钮
    form.on("submit(login)",function(data){
        var submit = $(this);
        submit.text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
        $.post("<?=url('user/login')?>", data.field, function(result){
            submit.text("登录").removeAttr("disabled").removeClass("layui-disabled");
            return layui.common.ajaxHandler(result);
        }, 'json');
        return false;
    });

    //表单输入效果
    $(".layui-form .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    });

    $(".layui-form .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    }).blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    });
})
</script>
</body>
</html>