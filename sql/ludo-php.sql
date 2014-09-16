/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50617
 Source Host           : localhost
 Source Database       : ludo-php

 Target Server Type    : MySQL
 Target Server Version : 50617
 File Encoding         : utf-8

 Date: 09/16/2014 12:47:29 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `Log`
-- ----------------------------
DROP TABLE IF EXISTS `Log`;
CREATE TABLE `Log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户Id',
  `uname` varchar(255) DEFAULT NULL COMMENT '用户名, 特别是未登陆用户',
  `name` varchar(255) DEFAULT NULL COMMENT '操作名称',
  `old` varchar(2048) DEFAULT NULL COMMENT '操作前数据',
  `new` varchar(2048) DEFAULT NULL COMMENT '操作后数据',
  `success` tinyint(1) unsigned NOT NULL COMMENT '1:成功,0:失败',
  `createTime` datetime DEFAULT NULL COMMENT '记录时间',
  `ctrl` varchar(255) NOT NULL COMMENT '当前的controller',
  `act` varchar(255) NOT NULL COMMENT '当前的action',
  `url` varchar(1024) NOT NULL COMMENT '操作的url地址',
  `httpReferer` varchar(255) DEFAULT NULL COMMENT '来源网址',
  `userAgent` varchar(255) DEFAULT NULL COMMENT '用户浏览器和操作系统信息',
  `ip` varchar(20) DEFAULT NULL COMMENT '用户的Ip',
  `proxyIp` varchar(47) DEFAULT NULL,
  `post` varchar(2048) DEFAULT NULL,
  `get` varchar(2048) DEFAULT NULL,
  `session` varchar(2048) DEFAULT NULL,
  `cookie` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='彩云系统日志';

-- ----------------------------
--  Records of `Log`
-- ----------------------------
BEGIN;
INSERT INTO `Log` VALUES ('1', '207', 'admin', 'add role', null, '{\"role\":{\"role\":\"1\",\"descr\":\"2\",\"createTime\":\"2014-09-15 17:59:14\",\"id\":\"8\"},\"permission\":[{\"roleId\":\"8\",\"permissionId\":1},{\"roleId\":\"8\",\"permissionId\":2},{\"roleId\":\"8\",\"permissionId\":3},{\"roleId\":\"8\",\"permissionId\":4}]}', '0', '2014-09-15 17:59:14', 'Permission', 'addRole', 'http://localhost/ludo-php/index.php/permission/addRole', 'http://localhost/ludo-php/index.php/permission/addRole', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"2\",\"permission\":{\"1\":\"on\",\"2\":\"on\",\"3\":\"on\",\"4\":\"on\"},\"id\":\"\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"4\",\"stationId\":\"96\",\"usergroup\":\"1\",\"needChangePwd\":\"0\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true,\"formId\":\"14107737465416b2f2790cc\",\"page\":1}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('2', '207', 'admin', 'add role', null, '{\"role\":{\"role\":\"1\",\"descr\":\"2\",\"createTime\":\"2014-09-15 18:00:07\",\"id\":\"9\"},\"permission\":[{\"roleId\":\"9\",\"permissionId\":1},{\"roleId\":\"9\",\"permissionId\":2},{\"roleId\":\"9\",\"permissionId\":3},{\"roleId\":\"9\",\"permissionId\":4}]}', '0', '2014-09-15 18:00:07', 'Permission', 'addRole', 'http://localhost/ludo-php/index.php/permission/addRole', 'http://localhost/ludo-php/index.php/permission/addRole', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"2\",\"permission\":{\"1\":\"on\",\"2\":\"on\",\"3\":\"on\",\"4\":\"on\"},\"id\":\"\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"4\",\"stationId\":\"96\",\"usergroup\":\"1\",\"needChangePwd\":\"0\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true,\"formId\":\"14107737465416b2f2790cc\",\"page\":1}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('3', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"2\",\"4\":\"2\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"16\",\"0\":\"16\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"17\",\"0\":\"17\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"2\",\"2\":\"2\"},{\"id\":\"18\",\"0\":\"18\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"19\",\"0\":\"19\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":2},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4}]}', '0', '2014-09-15 18:01:16', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"2\":\"on\",\"3\":\"on\",\"4\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"4\",\"stationId\":\"96\",\"usergroup\":\"1\",\"needChangePwd\":\"0\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true,\"formId\":\"14107752335416b8c1d282a\",\"page\":1}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('4', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"3\",\"4\":\"3\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"24\",\"0\":\"24\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"25\",\"0\":\"25\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"2\",\"2\":\"2\"},{\"id\":\"26\",\"0\":\"26\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"27\",\"0\":\"27\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4},{\"roleId\":8,\"permissionId\":6}]}', '0', '2014-09-16 11:02:54', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"3\":\"on\",\"4\":\"on\",\"6\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"5\",\"stationId\":\"96\",\"usergroup\":\"1\",\"needChangePwd\":\"0\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true,\"formId\":\"2075417a54522d15\"}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('5', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"3\",\"4\":\"3\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"28\",\"0\":\"28\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"29\",\"0\":\"29\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"30\",\"0\":\"30\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"},{\"id\":\"31\",\"0\":\"31\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"6\",\"2\":\"6\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4},{\"roleId\":8,\"permissionId\":5},{\"roleId\":8,\"permissionId\":6}]}', '0', '2014-09-16 11:05:52', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"3\":\"on\",\"4\":\"on\",\"5\":\"on\",\"6\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"5\",\"stationId\":\"96\",\"usergroup\":\"1\",\"needChangePwd\":\"0\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('6', '1', 'admin', 'User Login', null, null, '0', '2014-09-16 11:39:14', 'User', 'login', 'http://localhost/ludo-php/index.php/user/login', 'http://localhost/ludo-php/index.php/user/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"uname\":\"admin\",\"password\":\"bi11g0!@#\",\"timezoneOffset\":\"\"}', '[]', '{\"user\":{\"id\":\"1\",\"uname\":\"admin\",\"nickname\":\"\\u7537\"}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('7', '1', 'admin', 'User Login', null, null, '0', '2014-09-16 11:41:06', 'User', 'login', 'http://localhost/ludo-php/index.php/user/login', 'http://localhost/ludo-php/index.php/user/?jurl=http%3A%2F%2Flocalhost%2Fludo-php%2Findex.php%2F', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"uname\":\"admin\",\"password\":\"bi11g0!@#\",\"timezoneOffset\":\"\"}', '[]', '{\"user\":{\"id\":\"1\",\"uname\":\"admin\",\"nickname\":\"\\u7537\"}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('8', '1', 'admin', 'User Login', null, null, '0', '2014-09-16 11:41:21', 'User', 'login', 'http://localhost/ludo-php/index.php/user/login', 'http://localhost/ludo-php/index.php/user/?jurl=http%3A%2F%2Flocalhost%2Fludo-php%2Findex.php%2F', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"uname\":\"admin\",\"password\":\"bi11g0!@#\",\"timezoneOffset\":\"\"}', '[]', '{\"user\":{\"id\":\"1\",\"uname\":\"admin\",\"nickname\":\"\\u5f20\\u658c\"}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('9', '1', 'admin', 'User Login', null, null, '0', '2014-09-16 11:41:38', 'User', 'login', 'http://localhost/ludo-php/index.php/user/login', 'http://localhost/ludo-php/index.php/user/?jurl=http%3A%2F%2Flocalhost%2Fludo-php%2Findex.php%2F', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"uname\":\"admin\",\"password\":\"bi11g0!@#\",\"timezoneOffset\":\"\"}', '[]', '{\"user\":{\"id\":\"1\",\"uname\":\"admin\",\"nickname\":\"\\u5f20\\u658c\"}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('10', '1', 'admin', 'User Login', null, null, '0', '2014-09-16 11:43:41', 'User', 'login', 'http://localhost/ludo-php/index.php/user/login', 'http://localhost/ludo-php/index.php/user/?jurl=http%3A%2F%2Flocalhost%2Fludo-php%2Findex.php%2F', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"uname\":\"admin\",\"password\":\"bi11g0!@#\",\"timezoneOffset\":\"\"}', '[]', '{\"user\":{\"id\":\"1\",\"uname\":\"admin\",\"nickname\":\"\\u5f20\\u658c\",\"isAdmin\":true}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('11', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"3\",\"4\":\"3\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"32\",\"0\":\"32\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"33\",\"0\":\"33\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"34\",\"0\":\"34\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"},{\"id\":\"35\",\"0\":\"35\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"5\",\"2\":\"5\"},{\"id\":\"36\",\"0\":\"36\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"6\",\"2\":\"6\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4},{\"roleId\":8,\"permissionId\":5},{\"roleId\":8,\"permissionId\":6}]}', '0', '2014-09-16 12:44:15', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"3\":\"on\",\"4\":\"on\",\"5\":\"on\",\"6\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"5\",\"stationId\":\"96\",\"usergroup\":\"1\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('12', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"3\",\"4\":\"3\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"37\",\"0\":\"37\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"38\",\"0\":\"38\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"39\",\"0\":\"39\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"},{\"id\":\"40\",\"0\":\"40\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"5\",\"2\":\"5\"},{\"id\":\"41\",\"0\":\"41\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"6\",\"2\":\"6\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4},{\"roleId\":8,\"permissionId\":5},{\"roleId\":8,\"permissionId\":6}]}', '0', '2014-09-16 12:44:26', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"3\":\"on\",\"4\":\"on\",\"5\":\"on\",\"6\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"5\",\"stationId\":\"96\",\"usergroup\":\"1\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('13', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"3\",\"4\":\"3\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"42\",\"0\":\"42\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"43\",\"0\":\"43\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"44\",\"0\":\"44\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"},{\"id\":\"45\",\"0\":\"45\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"5\",\"2\":\"5\"},{\"id\":\"46\",\"0\":\"46\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"6\",\"2\":\"6\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":2},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4},{\"roleId\":8,\"permissionId\":5},{\"roleId\":8,\"permissionId\":6}]}', '0', '2014-09-16 12:44:48', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"2\":\"on\",\"3\":\"on\",\"4\":\"on\",\"5\":\"on\",\"6\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"5\",\"stationId\":\"96\",\"usergroup\":\"1\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}'), ('14', '207', 'admin', 'change role', '{\"role\":{\"id\":\"8\",\"0\":\"8\",\"role\":\"1\",\"1\":\"1\",\"deleted\":\"0\",\"2\":\"0\",\"createTime\":\"2014-09-15 17:59:14\",\"3\":\"2014-09-15 17:59:14\",\"descr\":\"3\",\"4\":\"3\",\"reserved\":\"0\",\"5\":\"0\"},\"permission\":[{\"id\":\"47\",\"0\":\"47\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"1\",\"2\":\"1\"},{\"id\":\"48\",\"0\":\"48\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"2\",\"2\":\"2\"},{\"id\":\"49\",\"0\":\"49\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"3\",\"2\":\"3\"},{\"id\":\"50\",\"0\":\"50\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"4\",\"2\":\"4\"},{\"id\":\"51\",\"0\":\"51\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"5\",\"2\":\"5\"},{\"id\":\"52\",\"0\":\"52\",\"roleId\":\"8\",\"1\":\"8\",\"permissionId\":\"6\",\"2\":\"6\"}]}', '{\"role\":{\"role\":\"1\",\"descr\":\"3\"},\"permission\":[{\"roleId\":8,\"permissionId\":1},{\"roleId\":8,\"permissionId\":2},{\"roleId\":8,\"permissionId\":3},{\"roleId\":8,\"permissionId\":4},{\"roleId\":8,\"permissionId\":5},{\"roleId\":8,\"permissionId\":6}]}', '0', '2014-09-16 12:45:03', 'Permission', 'changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole', 'http://localhost/ludo-php/index.php/permission/changeRole/8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.94 Safari/537.36', '0.0.0.0', ', getenv(REMOTE_ADDR):::1, SERVER[REMOTE_ADDR]:', '{\"role\":\"1\",\"descr\":\"3\",\"permission\":{\"1\":\"on\",\"2\":\"on\",\"3\":\"on\",\"4\":\"on\",\"5\":\"on\",\"6\":\"on\"},\"id\":\"8\"}', '[]', '{\"user\":{\"id\":\"207\",\"uname\":\"admin\",\"nickname\":\"admin\",\"vendorId\":\"5\",\"stationId\":\"96\",\"usergroup\":\"1\",\"timezone\":\"Etc\\/GMT-8\",\"timezoneOffset\":\"8\",\"isAdmin\":1,\"station\":\"LLC \\\"Inter\\\"\",\"timezoneOffsetVendor\":null,\"isChinese\":true}}', '{\"PHPSESSID\":\"pq8fk01pkjf5lv3b7hrm0kn6c0\",\"ECS_ID\":\"06ecf42381c79f21986787816a34829ce3850807\",\"ECS\":{\"visit_times\":\"1\"},\"ECSCP_ID\":\"aeea94dcbca5bd6ec7cfaa5f4121afe35ce74027\",\"sid\":\"s:m0v0584sNFIFuALuXmC1qaPS.nktAycrG0fioEDbaeRHdUVMf9hC\\/C\\/R3GWQO0guDCEY\",\"csrftoken\":\"wEb8zyFaJQBYqGySeAtN9cwYQkBgSwBb\"}');
COMMIT;

-- ----------------------------
--  Table structure for `Permission`
-- ----------------------------
DROP TABLE IF EXISTS `Permission`;
CREATE TABLE `Permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource` varchar(50) DEFAULT NULL COMMENT '资源,见Permission.conf.php',
  `operation` varchar(255) DEFAULT NULL COMMENT '对资源的操作, 见Permission.conf.php',
  `type` tinyint(1) DEFAULT NULL COMMENT '1:功能模块权限 2:菜单权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限表, 见Permission.conf.php';

-- ----------------------------
--  Records of `Permission`
-- ----------------------------
BEGIN;
INSERT INTO `Permission` VALUES ('1', 'news', 'read', '1'), ('2', 'news', 'create', '1'), ('3', 'news', 'update', '1'), ('4', 'news', 'delete', '1'), ('5', 'news', null, '2'), ('6', 'news', 'news/index', '2');
COMMIT;

-- ----------------------------
--  Table structure for `Role`
-- ----------------------------
DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `role` varchar(50) DEFAULT NULL COMMENT '角色名称',
  `deleted` tinyint(1) unsigned DEFAULT '0' COMMENT '1:删除,0:未删除',
  `createTime` datetime DEFAULT NULL COMMENT '创建时间',
  `descr` varchar(255) DEFAULT NULL COMMENT '描述',
  `reserved` tinyint(1) DEFAULT '0' COMMENT '1:系统保留 否则其他',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='角色定义表';

-- ----------------------------
--  Records of `Role`
-- ----------------------------
BEGIN;
INSERT INTO `Role` VALUES ('8', '1', '0', '2014-09-15 17:59:14', '3', '0'), ('9', '1', '0', '2014-09-15 18:00:07', '2', '0');
COMMIT;

-- ----------------------------
--  Table structure for `RolePermission`
-- ----------------------------
DROP TABLE IF EXISTS `RolePermission`;
CREATE TABLE `RolePermission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `roleId` int(10) unsigned DEFAULT NULL COMMENT '角色编号',
  `permissionId` int(10) unsigned DEFAULT NULL COMMENT '权限编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户角色关系表';

-- ----------------------------
--  Records of `RolePermission`
-- ----------------------------
BEGIN;
INSERT INTO `RolePermission` VALUES ('20', '9', '1'), ('21', '9', '2'), ('22', '9', '3'), ('23', '9', '4'), ('53', '8', '1'), ('54', '8', '2'), ('55', '8', '3'), ('56', '8', '4'), ('57', '8', '5'), ('58', '8', '6');
COMMIT;

-- ----------------------------
--  Table structure for `User`
-- ----------------------------
DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(50) DEFAULT NULL COMMENT '姓名',
  `password` varchar(100) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL COMMENT '性别',
  `createTime` datetime DEFAULT NULL,
  `createUserId` int(10) unsigned DEFAULT NULL,
  `deleted` tinyint(1) unsigned DEFAULT '0' COMMENT '是否删除 1:已删除 0:未删除',
  `isAdmin` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) unsigned DEFAULT '1' COMMENT '1:启用 0:停用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='员工信息表';

-- ----------------------------
--  Records of `User`
-- ----------------------------
BEGIN;
INSERT INTO `User` VALUES ('1', 'admin', 'f4f6f3b9c4589ba2f60a6cae0af6affb', '张斌', null, null, '0', '1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `UserRole`
-- ----------------------------
DROP TABLE IF EXISTS `UserRole`;
CREATE TABLE `UserRole` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `userId` int(10) unsigned DEFAULT NULL COMMENT '用户编号',
  `roleId` int(10) unsigned DEFAULT NULL COMMENT '角色编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户角色关系表';

SET FOREIGN_KEY_CHECKS = 1;
