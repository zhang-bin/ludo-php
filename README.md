# Ludo-PHP
___
Ludo-PHP是一个快速，面向对象的轻量级PHP开发框架。

## 简介
___
1. Ludo-PHP采用PDO Mysql Prepare方式操作数据库，避免SQL injection的风险。
2. 自动路由模式，程序会自动解析url中的controller和action，通过reflection调用对应的方法。
    
    > $method = new ReflectionMethod($this->_ctrl, $action);
    >
    > $output = $method->invoke($controller);
3. 自动记录request日志，日志内容包括$_GET, $_POST, $_COOKIE, $_SESSION, $_SERVER, SQL, ERROR信息。

## 目录结构
___
    app
    |-conf                      功能配置文件
    |-controllers	            控制器文件
    |-daos		                表dao(Data Access Object)文件
    |-helpers                   辅助类文件
    |-languages                 语言文件
    |-templates		            视图文件
    bin                         命令行脚本执行文件
    img                         前端js，css文件
    includes                    框架核心文件
    sql                         数据库sql文件
    uploads                     上传目录，临时文件
    config.php                  系统配置文件
    config.production.inc.php   生产系统配置文件
    header.php                  初始化文件
    index.php	                入口文件