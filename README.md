# Ludo-PHP
#### Ludo-PHP是一个快速，面向对象的轻量级PHP开发框架。


# 简介
1. Ludo-PHP采用PDO Mysql Prepare方式操作数据库，避免SQL injection的风险。
2. 自动路由模式，程序会自动解析url中的controller和action，通过reflection调用对应的方法。
    
    > $method = new ReflectionMethod($this->_ctrl, $action);
    >
    > $output = $method->invoke($controller);
3. 自动记录request日志，日志内容包括$_GET, $_POST, $_COOKIE, $_SESSION, $_SERVER, SQL, ERROR信息。