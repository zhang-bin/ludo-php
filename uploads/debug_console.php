<?php include_once("../config.inc.php");if (DEBUG) : if(@$_GET["clear"]) {file_put_contents("/Users/zhangbin/php/ludo-php/uploads/debug_console.php", ""); header("location:http://localhost/ludo-php/uploads/debug_console.php");}	?><h2>Time:2014-09-16 12:46:09:http://localhost/ludo-php/index.php/permission/permissions/8</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/header.php',
  'line' => 54,
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
						<td align=left>SELECT  * FROM `Role` Role  WHERE Role.id=?   LIMIT 1 OFFSET 0</td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0003</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  * FROM `Permission` Permission     </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  permissionId FROM `RolePermission` RolePermission  WHERE roleId = ?   </td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 3&nbsp;Total ProcessTime:0.0007</td>
				 </tr>
</table><h2>GET:</h2><pre>array (
  'id' => '8',
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
    'id' => '207',
    'uname' => 'admin',
    'nickname' => 'admin',
    'vendorId' => '5',
    'stationId' => '96',
    'usergroup' => '1',
    'timezone' => 'Etc/GMT-8',
    'timezoneOffset' => '8',
    'isAdmin' => 1,
    'station' => 'LLC "Inter"',
    'timezoneOffsetVendor' => NULL,
    'isChinese' => true,
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
  'REMOTE_PORT' => '59925',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/permission/permissions/8',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/permission/permissions/8',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/permissions/8',
  'PHP_SELF' => '/ludo-php/index.php/permission/permissions/8',
  'REQUEST_TIME_FLOAT' => 1410842769.974,
  'REQUEST_TIME' => 1410842769,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 12:46:08:http://localhost/ludo-php/index.php/permission/index</h2>@@@@error:<pre>array (
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
						<td>0.0004</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  * FROM `Role` Role  WHERE deleted = 0   </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0004</td>
					 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 2&nbsp;Total ProcessTime:0.0008</td>
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
    'id' => '207',
    'uname' => 'admin',
    'nickname' => 'admin',
    'vendorId' => '5',
    'stationId' => '96',
    'usergroup' => '1',
    'timezone' => 'Etc/GMT-8',
    'timezoneOffset' => '8',
    'isAdmin' => 1,
    'station' => 'LLC "Inter"',
    'timezoneOffsetVendor' => NULL,
    'isChinese' => true,
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
  'REMOTE_PORT' => '59925',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/permission/index',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/permission/index',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/index',
  'PHP_SELF' => '/ludo-php/index.php/permission/index',
  'REQUEST_TIME_FLOAT' => 1410842768.1619999,
  'REQUEST_TIME' => 1410842768,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 12:45:50:http://localhost/ludo-php/index.php/index</h2>@@@@error:<pre>array (
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
    'id' => '207',
    'uname' => 'admin',
    'nickname' => 'admin',
    'vendorId' => '5',
    'stationId' => '96',
    'usergroup' => '1',
    'timezone' => 'Etc/GMT-8',
    'timezoneOffset' => '8',
    'isAdmin' => 1,
    'station' => 'LLC "Inter"',
    'timezoneOffsetVendor' => NULL,
    'isChinese' => true,
  ),
)</pre><h2>FILES:</h2><pre></pre><h2>SERVER:</h2><pre>array (
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36',
  'HTTP_REFERER' => 'http://localhost/ludo-php/index.php/permission/permissions/8',
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
  'REMOTE_PORT' => '59920',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/index',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/index',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/index',
  'PHP_SELF' => '/ludo-php/index.php/index',
  'REQUEST_TIME_FLOAT' => 1410842750.22,
  'REQUEST_TIME' => 1410842750,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 12:45:44:http://localhost/ludo-php/index.php/permission/permissions/8</h2>@@@@error:<pre>array (
  'type' => 8,
  'message' => 'Undefined variable: gToolbox',
  'file' => '/Users/zhangbin/php/ludo-php/app/templates/header.php',
  'line' => 54,
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
						<td align=left>SELECT  * FROM `Role` Role  WHERE Role.id=?   LIMIT 1 OFFSET 0</td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  * FROM `Permission` Permission     </td>
						<td align=left>NULL</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>SELECT  permissionId FROM `RolePermission` RolePermission  WHERE roleId = ?   </td>
						<td align=left>array (
  0 => 8,
)</td>
						<td></td>
						<td>0.0002</td>
					 </tr><tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: 3&nbsp;Total ProcessTime:0.0005</td>
				 </tr>
</table><h2>GET:</h2><pre>array (
  'id' => '8',
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
    'id' => '207',
    'uname' => 'admin',
    'nickname' => 'admin',
    'vendorId' => '5',
    'stationId' => '96',
    'usergroup' => '1',
    'timezone' => 'Etc/GMT-8',
    'timezoneOffset' => '8',
    'isAdmin' => 1,
    'station' => 'LLC "Inter"',
    'timezoneOffsetVendor' => NULL,
    'isChinese' => true,
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
  'REMOTE_PORT' => '59919',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/permission/permissions/8',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/permission/permissions/8',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/permissions/8',
  'PHP_SELF' => '/ludo-php/index.php/permission/permissions/8',
  'REQUEST_TIME_FLOAT' => 1410842744.596,
  'REQUEST_TIME' => 1410842744,
)</pre><h2>ENV:</h2><pre></pre><br><br><br><br><br>=========================================================================================================================<h2>Time:2014-09-16 12:45:43:http://localhost/ludo-php/index.php/permission/index</h2>@@@@error:<pre>array (
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
    'id' => '207',
    'uname' => 'admin',
    'nickname' => 'admin',
    'vendorId' => '5',
    'stationId' => '96',
    'usergroup' => '1',
    'timezone' => 'Etc/GMT-8',
    'timezoneOffset' => '8',
    'isAdmin' => 1,
    'station' => 'LLC "Inter"',
    'timezoneOffsetVendor' => NULL,
    'isChinese' => true,
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
  'REMOTE_PORT' => '59919',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/ludo-php/index.php/permission/index',
  'SCRIPT_NAME' => '/ludo-php/index.php',
  'PATH_INFO' => '/permission/index',
  'PATH_TRANSLATED' => '/Users/zhangbin/php/permission/index',
  'PHP_SELF' => '/ludo-php/index.php/permission/index',
  'REQUEST_TIME_FLOAT' => 1410842743.273,
  'REQUEST_TIME' => 1410842743,
)</pre><h2>ENV:</h2><pre></pre><?php endif; ?>