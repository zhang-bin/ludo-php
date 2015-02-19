<?php include_once("../config.inc.php");if (DEBUG) : if(@$_GET["clear"]) {file_put_contents("/Users/zhangbin/php/ludo-php-2/uploads//debug_console.php", ""); header("location:http://localhost/ludo-php-2/uploads/debug_console.php");}	?><h2>Time:2015-02-19 22:30:06:http://localhost/ludo-php-2/index.php/permission/user/roleId/8</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined index: permission/user',
  'file' => '/Users/zhangbin/php/ludo-php-2/app/helpers/MenuHelper.php',
  'line' => 15,
)</pre>@@@@<br />{"driver":"mysql","charset":"utf8","collation":"utf8_general_ci","prefix":"","name":"mysql","host":"localhost","database":"ludo-php","username":"root","password":"64297881"}<br /><table id="debugtable" width="100%" border="0" cellspacing="1" style="background:#DDDDF0;word-break: break-all;">
	<tr style="background:#A5BDD8;height:30px;Color:White;">
		<th>Query</th>
		<th width=100>Params</th>
		<th width=50>Error</th>
		<th width=100>ProcessTime</th>
	 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  count(*) FROM `UserRole` UserRole  left JOIN `User` User ON UserRole.`userId`=User.id  WHERE deleted = 0 and roleId = ?   </td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  UserRole.*, User.* FROM `UserRole` UserRole  left JOIN `User` User ON UserRole.`userId`=User.id  WHERE deleted = 0 and roleId = ?  ORDER BY createTime desc </td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0006</td>
					 </tr><tr style='background:#EEEEEE;Height:30px;text-align:center'>
					<td colspan=5>
						Total execute queries: 3&nbsp;Total ProcessTime:0.0013</td>
				 </tr>
</table><h2>GET:</h2><pre>array (
  'roleId' => '8',
)</pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
  'PHPSESSID' => 'pq8fk01pkjf5lv3b7hrm0kn6c0',
  'ECS_ID' => '06ecf42381c79f21986787816a34829ce3850807',
  'ECS' => 
  array (
    'visit_times' => '1',
  ),
  'ECSCP_ID' => 'aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027',
  'sid' => 's:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC/C/R3GWQO0guDCEY',
  'csrftoken' => 'wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
)</pre><h2>SESSION:</h2><pre>array (
  'user' => 
  array (
    'id' => 1,
    'username' => 'root',
    'nickname' => 'root',
    'isAdmin' => true,
    'formId' => '142435617254e5f34ceb28f',
  ),
  'flash.old' => 
  array (
  ),
  'flash.new' => 
  array (
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php-2/index.php/permission',
  'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.4.9 (Unix) PHP/5.5.14',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'REQUEST_SCHEME' => 'http',
  'CONTEXT_PREFIX' => '',
  'CONTEXT_DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php-2/index.php',
  'REMOTE_PORT' => '57335',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php-2/index.php/permission/user/roleId/8',
  'SCRIPT_NAME' => '/ludo-php-2/index.php',
  'PATH_INFO' => '/permission/user/roleId/8',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/user/roleId/8',
  'PHP_SELF' => '/ludo-php-2/index.php/permission/user/roleId/8',
  'REQUEST_TIME_FLOAT' => 1424356206.3940001,
  'REQUEST_TIME' => 1424356206,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2015-02-19 22:29:32:http://localhost/ludo-php-2/index.php/permission/addUser</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Constant LD_UPLOAD_PATH already defined',
  'file' => '/Users/zhangbin/php/ludo-php-2/bootstrap/paths.php',
  'line' => 14,
)</pre>@@@@<br />@@@@output:<pre>&lt;pre&gt;File [/Users/zhangbin/php/ludo-php-2/app/templates/user/change.php] Not Found

#0 /Users/zhangbin/php/ludo-php-2/app/controllers/Permission.php(286): Ludo\View\View-&gt;display()
#1 [internal function]: Permission-&gt;addUser()
#2 /Users/zhangbin/php/ludo-php-2/vendor/ludo/framework/src/Ludo/Foundation/Application.php(35): ReflectionMethod-&gt;invoke(Object(Permission))
#3 /Users/zhangbin/php/ludo-php-2/index.php(11): Ludo\Foundation\Application-&gt;run()
#4 {main}&lt;/pre&gt;</pre>@@@@{"driver":"mysql","charset":"utf8","collation":"utf8_general_ci","prefix":"","name":"mysql","host":"localhost","database":"ludo-php","username":"root","password":"64297881"}<br /><table id="debugtable" width="100%" border="0" cellspacing="1" style="background:#DDDDF0;word-break: break-all;">
	<tr style="background:#A5BDD8;height:30px;Color:White;">
		<th>Query</th>
		<th width=100>Params</th>
		<th width=50>Error</th>
		<th width=100>ProcessTime</th>
	 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0005</td>
					 </tr><tr style='background:#EEEEEE;Height:30px;text-align:center'>
					<td colspan=5>
						Total execute queries: 1&nbsp;Total ProcessTime:0.0005</td>
				 </tr>
</table><h2>GET:</h2><pre></pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
  'PHPSESSID' => 'pq8fk01pkjf5lv3b7hrm0kn6c0',
  'ECS_ID' => '06ecf42381c79f21986787816a34829ce3850807',
  'ECS' => 
  array (
    'visit_times' => '1',
  ),
  'ECSCP_ID' => 'aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027',
  'sid' => 's:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC/C/R3GWQO0guDCEY',
  'csrftoken' => 'wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
)</pre><h2>SESSION:</h2><pre>array (
  'user' => 
  array (
    'id' => 1,
    'username' => 'root',
    'nickname' => 'root',
    'isAdmin' => true,
    'formId' => '142435617254e5f34ceb28f',
  ),
  'flash.old' => 
  array (
  ),
  'flash.new' => 
  array (
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php-2/index.php/permission/user/roleId/8',
  'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.4.9 (Unix) PHP/5.5.14',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'REQUEST_SCHEME' => 'http',
  'CONTEXT_PREFIX' => '',
  'CONTEXT_DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php-2/index.php',
  'REMOTE_PORT' => '57331',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php-2/index.php/permission/addUser',
  'SCRIPT_NAME' => '/ludo-php-2/index.php',
  'PATH_INFO' => '/permission/addUser',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/addUser',
  'PHP_SELF' => '/ludo-php-2/index.php/permission/addUser',
  'REQUEST_TIME_FLOAT' => 1424356172.957,
  'REQUEST_TIME' => 1424356172,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2015-02-19 22:29:25:http://localhost/ludo-php-2/index.php/permission/user/roleId/8</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined index: permission/user',
  'file' => '/Users/zhangbin/php/ludo-php-2/app/helpers/MenuHelper.php',
  'line' => 15,
)</pre>@@@@<br />{"driver":"mysql","charset":"utf8","collation":"utf8_general_ci","prefix":"","name":"mysql","host":"localhost","database":"ludo-php","username":"root","password":"64297881"}<br /><table id="debugtable" width="100%" border="0" cellspacing="1" style="background:#DDDDF0;word-break: break-all;">
	<tr style="background:#A5BDD8;height:30px;Color:White;">
		<th>Query</th>
		<th width=100>Params</th>
		<th width=50>Error</th>
		<th width=100>ProcessTime</th>
	 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  count(*) FROM `UserRole` UserRole  left JOIN `User` User ON UserRole.`userId`=User.id  WHERE deleted = 0 and roleId = ?   </td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0005</td>
					 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  UserRole.*, User.* FROM `UserRole` UserRole  left JOIN `User` User ON UserRole.`userId`=User.id  WHERE deleted = 0 and roleId = ?  ORDER BY createTime desc </td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0016</td>
					 </tr><tr style='background:#EEEEEE;Height:30px;text-align:center'>
					<td colspan=5>
						Total execute queries: 3&nbsp;Total ProcessTime:0.0025</td>
				 </tr>
</table><h2>GET:</h2><pre>array (
  'roleId' => '8',
)</pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
  'PHPSESSID' => 'pq8fk01pkjf5lv3b7hrm0kn6c0',
  'ECS_ID' => '06ecf42381c79f21986787816a34829ce3850807',
  'ECS' => 
  array (
    'visit_times' => '1',
  ),
  'ECSCP_ID' => 'aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027',
  'sid' => 's:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC/C/R3GWQO0guDCEY',
  'csrftoken' => 'wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
)</pre><h2>SESSION:</h2><pre>array (
  'user' => 
  array (
    'id' => 1,
    'username' => 'root',
    'nickname' => 'root',
    'isAdmin' => true,
  ),
  'flash.old' => 
  array (
  ),
  'flash.new' => 
  array (
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php-2/index.php/permission',
  'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.4.9 (Unix) PHP/5.5.14',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'REQUEST_SCHEME' => 'http',
  'CONTEXT_PREFIX' => '',
  'CONTEXT_DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php-2/index.php',
  'REMOTE_PORT' => '57323',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php-2/index.php/permission/user/roleId/8',
  'SCRIPT_NAME' => '/ludo-php-2/index.php',
  'PATH_INFO' => '/permission/user/roleId/8',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/user/roleId/8',
  'PHP_SELF' => '/ludo-php-2/index.php/permission/user/roleId/8',
  'REQUEST_TIME_FLOAT' => 1424356165.0869999,
  'REQUEST_TIME' => 1424356165,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2015-02-19 22:29:24:http://localhost/ludo-php-2/index.php/permission</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined index: permission',
  'file' => '/Users/zhangbin/php/ludo-php-2/app/helpers/MenuHelper.php',
  'line' => 15,
)</pre>@@@@<br />{"driver":"mysql","charset":"utf8","collation":"utf8_general_ci","prefix":"","name":"mysql","host":"localhost","database":"ludo-php","username":"root","password":"64297881"}<br /><table id="debugtable" width="100%" border="0" cellspacing="1" style="background:#DDDDF0;word-break: break-all;">
	<tr style="background:#A5BDD8;height:30px;Color:White;">
		<th>Query</th>
		<th width=100>Params</th>
		<th width=50>Error</th>
		<th width=100>ProcessTime</th>
	 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  count(*) FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style='background:#EEEEEE;Height:30px;text-align:center'>
					<td colspan=5>
						Total execute queries: 2&nbsp;Total ProcessTime:0.0007</td>
				 </tr>
</table><h2>GET:</h2><pre></pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
  'PHPSESSID' => 'pq8fk01pkjf5lv3b7hrm0kn6c0',
  'ECS_ID' => '06ecf42381c79f21986787816a34829ce3850807',
  'ECS' => 
  array (
    'visit_times' => '1',
  ),
  'ECSCP_ID' => 'aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027',
  'sid' => 's:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC/C/R3GWQO0guDCEY',
  'csrftoken' => 'wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
)</pre><h2>SESSION:</h2><pre>array (
  'user' => 
  array (
    'id' => 1,
    'username' => 'root',
    'nickname' => 'root',
    'isAdmin' => true,
  ),
  'flash.old' => 
  array (
  ),
  'flash.new' => 
  array (
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_CACHE_CONTROL' => 'max-age=0',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php-2/index.php/permission/changeRole/8',
  'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.4.9 (Unix) PHP/5.5.14',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'REQUEST_SCHEME' => 'http',
  'CONTEXT_PREFIX' => '',
  'CONTEXT_DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php-2/index.php',
  'REMOTE_PORT' => '57321',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php-2/index.php/permission',
  'SCRIPT_NAME' => '/ludo-php-2/index.php',
  'PATH_INFO' => '/permission',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission',
  'PHP_SELF' => '/ludo-php-2/index.php/permission',
  'REQUEST_TIME_FLOAT' => 1424356164.1500001,
  'REQUEST_TIME' => 1424356164,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2015-02-19 22:29:23:http://localhost/ludo-php-2/index.php/permission</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined index: permission',
  'file' => '/Users/zhangbin/php/ludo-php-2/app/helpers/MenuHelper.php',
  'line' => 15,
)</pre>@@@@<br />{"driver":"mysql","charset":"utf8","collation":"utf8_general_ci","prefix":"","name":"mysql","host":"localhost","database":"ludo-php","username":"root","password":"64297881"}<br /><table id="debugtable" width="100%" border="0" cellspacing="1" style="background:#DDDDF0;word-break: break-all;">
	<tr style="background:#A5BDD8;height:30px;Color:White;">
		<th>Query</th>
		<th width=100>Params</th>
		<th width=50>Error</th>
		<th width=100>ProcessTime</th>
	 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  count(*) FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style="background:#EEEEEE;Height:25px;Text-Align:center;">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0007</td>
					 </tr><tr style='background:#EEEEEE;Height:30px;text-align:center'>
					<td colspan=5>
						Total execute queries: 2&nbsp;Total ProcessTime:0.0010</td>
				 </tr>
</table><h2>GET:</h2><pre></pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
  'PHPSESSID' => 'pq8fk01pkjf5lv3b7hrm0kn6c0',
  'ECS_ID' => '06ecf42381c79f21986787816a34829ce3850807',
  'ECS' => 
  array (
    'visit_times' => '1',
  ),
  'ECSCP_ID' => 'aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027',
  'sid' => 's:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC/C/R3GWQO0guDCEY',
  'csrftoken' => 'wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
)</pre><h2>SESSION:</h2><pre>array (
  'user' => 
  array (
    'id' => 1,
    'username' => 'root',
    'nickname' => 'root',
    'isAdmin' => true,
  ),
  'flash.old' => 
  array (
  ),
  'flash.new' => 
  array (
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php-2/index.php/permission/changeRole/8',
  'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.4.9 (Unix) PHP/5.5.14',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'REQUEST_SCHEME' => 'http',
  'CONTEXT_PREFIX' => '',
  'CONTEXT_DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php-2/index.php',
  'REMOTE_PORT' => '57321',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php-2/index.php/permission',
  'SCRIPT_NAME' => '/ludo-php-2/index.php',
  'PATH_INFO' => '/permission',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission',
  'PHP_SELF' => '/ludo-php-2/index.php/permission',
  'REQUEST_TIME_FLOAT' => 1424356163.562,
  'REQUEST_TIME' => 1424356163,
)</pre><h2>ENV:</h2><pre></pre><?php endif; ?>