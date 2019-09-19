<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=SITE_TITLE?></title>
    <meta name="author" content="Maitrox" />
    <meta name="Copyright" content="Maitrox" />
    <meta name="description" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=badge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    Load::web('layui');
    Load::web('layuicms');
    Load::web('main');
    View::loadCss();
    ?>
</head>
<body class="main_body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header header">
        <div class="layui-main mag0">
            <a href="#" class="logo">Ludo</a>
            <!-- 显示/隐藏菜单 -->
            <a href="javascript:;" class="seraph hideMenu icon-caidan"></a>
            <!-- 顶级菜单 移动端 -->
            <ul class="layui-nav mobileTopLevelMenus" mobile>
                <li class="layui-nav-item" data-menu="systemConfig">
                    <a href="javascript:;"><i class="seraph icon-caidan"></i><cite>菜单</cite></a>
                    <dl class="layui-nav-child">

                    </dl>
                </li>
            </ul>
            <!-- 顶级菜单 pc端 -->
            <ul class="layui-nav topLevelMenus" pc></ul>
            <!-- 顶部右侧菜单 -->
            <ul class="layui-nav top_menu">
                <li class="layui-nav-item" id="userInfo">
                    <a href="javascript:;"><img src="/public/img/layuicms/images/face.jpg" class="layui-nav-img userAvatar" width="35" height="35"><cite class="adminName"><?=$_SESSION[USER]['nickname']?></cite></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" data-url="<?=url('user/changePassword')?>"><i class="seraph icon-xiugai" data-icon="icon-xiugai"></i><cite>修改密码</cite></a></dd>
                        <dd><a href="<?=url('user/logout')?>" class="signOut"><i class="seraph icon-tuichu"></i><cite>退出</cite></a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-side layui-bg-black">
        <div class="user-photo">
            <a class="img" title="我的头像" ><img src="/public/img/layuicms/images/face.jpg" class="userAvatar"></a>
            <p>你好！<span class="userName"><?=$_SESSION[USER]['nickname']?></span>, 欢迎登录</p>
        </div>
        <div class="navBar layui-side-scroll" id="navBar">
            <ul class="layui-nav layui-nav-tree">
                <li class="layui-nav-item layui-this">
                    <a href="javascript:;" data-url="<?=url('index/home')?>"><i class="layui-icon" data-icon=""></i><cite>后台首页</cite></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-body layui-form">
        <div class="layui-tab mag0" lay-filter="bodyTab" id="top_tabs_box">
            <ul class="layui-tab-title top_tab" id="top_tabs">
                <li class="layui-this" lay-id="" data-url="<?=url('index/home')?>"><i class="layui-icon">&#xe68e;</i> <cite>后台首页</cite></li>
            </ul>
            <ul class="layui-nav closeBox">
                <li class="layui-nav-item">
                    <a href="javascript:;"><i class="layui-icon caozuo">&#xe643;</i> 页面操作</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" class="refresh refreshThis"><i class="layui-icon">&#x1002;</i> 刷新当前</a></dd>
                        <dd><a href="javascript:;" class="closePageOther"><i class="seraph icon-prohibit"></i> 关闭其他</a></dd>
                        <dd><a href="javascript:;" class="closePageAll"><i class="seraph icon-guanbi"></i> 关闭全部</a></dd>
                    </dl>
                </li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show">
                    <iframe src="<?=url('index/home')?>"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    <div class="layui-footer footer">
        <p>
            <span>copyright @2019</span>
        </p>
    </div>
</div>
<!-- 移动导航 -->
<div class="site-tree-mobile"><i class="layui-icon">&#xe602;</i></div>
<div class="site-mobile-shade"></div>
<?php View::loadJs();?>
</body>
</html>