<?php include_once("../config.inc.php");if (DEBUG) : if(@$_GET["clear"]) {file_put_contents("/Users/zhangbin/php/ludo-php/uploads/debug_console.php", ""); header("location:http://localhost/ludo-php/uploads/debug_console.php");}	?><h2>Time:2014-09-16 11:46:55:http://localhost/ludo-php/index.php/permission/index</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/role/index.php',
  'line' => 3,
)</pre>@@@@<br />mysql:host=localhost;port=3306;dbname=ludo-php<br>
		<table id=debugtable width=100% border=0 cellspacing=1 style='background:#DDDDF0;word-break: break-all'><tr style="background:#A5BDD8;height:30;Color:White">
					<th>Query</th>
					<th width=100>Params</th>
					<th width=50>Error</th>
					<th width=100>ProcessTime</th>
				 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 0&nbsp;Total ProcessTime:0.0000</td>
				 </tr>
</table>mysql:host=localhost;port=3306;dbname=ludo-php<br>
		<table id=debugtable width=100% border=0 cellspacing=1 style='background:#DDDDF0;word-break: break-all'><tr style="background:#A5BDD8;height:30;Color:White">
					<th>Query</th>
					<th width=100>Params</th>
					<th width=50>Error</th>
					<th width=100>ProcessTime</th>
				 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  count(*) FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0001</td>
					 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 2&nbsp;Total ProcessTime:0.0004</td>
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
    'id' => '1',
    'uname' => 'admin',
    'nickname' => '张斌',
    'isAdmin' => true,
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php/index.php/index',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.2.26 (Unix) DAV/2 PHP/5.4.24 mod_ssl/2.2.26 OpenSSL/0.9.8y',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php/index.php',
  'REMOTE_PORT' => '58249',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/permission/index',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/permission/index',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/index',
  'PHP_SELF' => '/ludo-php/index.php/permission/index',
  'REQUEST_TIME_FLOAT' => 1410839215.0480001,
  'REQUEST_TIME' => 1410839215,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 11:46:53:http://localhost/ludo-php/index.php/index</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/header.php',
  'line' => 54,
)</pre>@@@@<br /><h2>GET:</h2><pre></pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
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
    'id' => '1',
    'uname' => 'admin',
    'nickname' => '张斌',
    'isAdmin' => true,
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php/index.php/permission/index',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.2.26 (Unix) DAV/2 PHP/5.4.24 mod_ssl/2.2.26 OpenSSL/0.9.8y',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php/index.php',
  'REMOTE_PORT' => '58249',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/index',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/index',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/index',
  'PHP_SELF' => '/ludo-php/index.php/index',
  'REQUEST_TIME_FLOAT' => 1410839213.892,
  'REQUEST_TIME' => 1410839213,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 11:46:51:http://localhost/ludo-php/index.php/permission/index</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/role/index.php',
  'line' => 3,
)</pre>@@@@<br />mysql:host=localhost;port=3306;dbname=ludo-php<br>
		<table id=debugtable width=100% border=0 cellspacing=1 style='background:#DDDDF0;word-break: break-all'><tr style="background:#A5BDD8;height:30;Color:White">
					<th>Query</th>
					<th width=100>Params</th>
					<th width=50>Error</th>
					<th width=100>ProcessTime</th>
				 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 0&nbsp;Total ProcessTime:0.0000</td>
				 </tr>
</table>mysql:host=localhost;port=3306;dbname=ludo-php<br>
		<table id=debugtable width=100% border=0 cellspacing=1 style='background:#DDDDF0;word-break: break-all'><tr style="background:#A5BDD8;height:30;Color:White">
					<th>Query</th>
					<th width=100>Params</th>
					<th width=50>Error</th>
					<th width=100>ProcessTime</th>
				 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  count(*) FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0001</td>
					 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 2&nbsp;Total ProcessTime:0.0004</td>
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
    'id' => '1',
    'uname' => 'admin',
    'nickname' => '张斌',
    'isAdmin' => true,
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php/index.php/',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.2.26 (Unix) DAV/2 PHP/5.4.24 mod_ssl/2.2.26 OpenSSL/0.9.8y',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php/index.php',
  'REMOTE_PORT' => '58249',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/permission/index',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/permission/index',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/index',
  'PHP_SELF' => '/ludo-php/index.php/permission/index',
  'REQUEST_TIME_FLOAT' => 1410839211.733,
  'REQUEST_TIME' => 1410839211,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 11:46:50:http://localhost/ludo-php/index.php/</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/header.php',
  'line' => 54,
)</pre>@@@@<br /><h2>GET:</h2><pre></pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
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
    'id' => '1',
    'uname' => 'admin',
    'nickname' => '张斌',
    'isAdmin' => true,
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_CACHE_CONTROL' => 'max-age=0',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php/index.php/user/?jurl=http%3A%2F%2Flocalhost%2Fludo-php%2Findex.php%2F',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.2.26 (Unix) DAV/2 PHP/5.4.24 mod_ssl/2.2.26 OpenSSL/0.9.8y',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php/index.php',
  'REMOTE_PORT' => '58249',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/index.php',
  'PHP_SELF' => '/ludo-php/index.php/',
  'REQUEST_TIME_FLOAT' => 1410839210.154,
  'REQUEST_TIME' => 1410839210,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 11:45:31:http://localhost/ludo-php/index.php/</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/header.php',
  'line' => 54,
)</pre>@@@@<br /><h2>GET:</h2><pre></pre><h2>POST:</h2><pre></pre><h2>COOKIE:</h2><pre>array (
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
    'id' => '1',
    'uname' => 'admin',
    'nickname' => '张斌',
    'isAdmin' => true,
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_CACHE_CONTROL' => 'max-age=0',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php/index.php/user/?jurl=http%3A%2F%2Flocalhost%2Fludo-php%2Findex.php%2F',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'zh-CN,zh;q=0.8,en;q=0.6',
  'HTTP_COOKIE' => 'PHPSESSID=pq8fk01pkjf5lv3b7hrm0kn6c0; ECS_ID=06ecf42381c79f21986787816a34829ce3850807; ECS[visit_times]=1; ECSCP_ID=aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027; sid=s%3Am0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC%2FC%2FR3GWQO0guDCEY; csrftoken=wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' => '',
  'SERVER_SOFTWARE' => 'Apache/2.2.26 (Unix) DAV/2 PHP/5.4.24 mod_ssl/2.2.26 OpenSSL/0.9.8y',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Users/zhangbin/php',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Users/zhangbin/php/ludo-php/index.php',
  'REMOTE_PORT' => '58172',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/index.php',
  'PHP_SELF' => '/ludo-php/index.php/',
  'REQUEST_TIME_FLOAT' => 1410839131.9419999,
  'REQUEST_TIME' => 1410839131,
)</pre><h2>ENV:</h2><pre></pre><?php endif; ?>