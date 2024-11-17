/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1-
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : 127.0.0.1:3306
 Source Schema         : test

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 27/07/2024 09:20:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cms_admin
-- ----------------------------
DROP TABLE IF EXISTS `cms_admin`;
CREATE TABLE `cms_admin`  (
  `id` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lastloginip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `lastloginipaddr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lastlogintime` datetime NOT NULL,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `count` int(6) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cms_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `cms_admin_log`;
CREATE TABLE `cms_admin_log`  (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `loginip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `loginipaddr` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `logintime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `useragent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for cms_node
-- ----------------------------
DROP TABLE IF EXISTS `cms_node`;
CREATE TABLE `cms_node`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cms_node
-- ----------------------------
INSERT INTO `cms_node` VALUES (1, 'SQL注入');
INSERT INTO `cms_node` VALUES (2, '流协议攻击');
INSERT INTO `cms_node` VALUES (3, '目录遍历');
INSERT INTO `cms_node` VALUES (4, '命令执行');
INSERT INTO `cms_node` VALUES (5, 'XSS攻击');
INSERT INTO `cms_node` VALUES (6, '扫描器探测');
INSERT INTO `cms_node` VALUES (7, '敏感文件获取');
INSERT INTO `cms_node` VALUES (8, '收藏');
INSERT INTO `cms_node` VALUES (9, '误报');
INSERT INTO `cms_node` VALUES (10, '测试样例');


-- ----------------------------
-- Table structure for cms_re
-- ----------------------------
DROP TABLE IF EXISTS `cms_re`;
CREATE TABLE `cms_re`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` int(11) NULL DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `test`(`node`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 19 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cms_re
-- ----------------------------
INSERT INTO `cms_re` VALUES (1, 1, '/(?:(union(.*?)select))/i', 1, NULL);
INSERT INTO `cms_re` VALUES (2, 1, '/\\s+(or|xor|and)\\s+.*(?:=|<|>|\\\'|\")/', 1, NULL);
INSERT INTO `cms_re` VALUES (3, 1, '/sleep\\((\\s*)(\\d*)(\\s*)\\)/', 1, NULL);
INSERT INTO `cms_re` VALUES (4, 1, '/(?:from\\W+information_schema\\W)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (5, 1, '/(database|schema|connection_id|group_|substr|updatexml|or\\\'|\\\'or|extractvalue|order|waitfor|column_name|concat)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (6, 2, '/(gopher|doc|php|glob|file|phar|zlib|ftp|ldap|dict|ogg|data):\\/\\//i', 1, NULL);
INSERT INTO `cms_re` VALUES (7, 3, '/\\.\\.\\/\\.\\.\\//', 1, NULL);
INSERT INTO `cms_re` VALUES (8, 3, '/(?:etc\\/\\W*passwd)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (9, 3, '/(win.ini)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (10, 4, '/(cmd|base64|shell|eval|whoami|system|\\$_|proc_|socket_|posix_|stream_|assert|phpinfo|exec|preg_|file_|passt|preg_r|call_user)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (11, 4, '/(print_r|include|passthru|var_dump|call_user_func_array|ipconfig|ifconfig|runtime|invokefunction|construct)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (12, 5, '/<(iframe|script|body|img|layer|div|meta|style|base|object|input)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (13, 5, '/(onmouseover|onerror|onload)=/i', 1, NULL);
INSERT INTO `cms_re` VALUES (14, 6, '/(HTTrack|Apache-HttpClient|harvest|audit|dirbuster|pangolin|nmap|sqln|hydra|Parser|libwww|BBBike|sqlmap|w3af|owasp|Nikto|fimap|havij|zmeu|BabyKrokodil|netsparker|httperf| SF)/i', 1, '');
INSERT INTO `cms_re` VALUES (15, 6, '/(acunetix-wvs-test-for-some-inexistent-file|acunetix_wvs_security_test|AppScan|XSS@HERE|Acunetix-Aspect|Acunetix-Aspect-Password|Acunetix-Aspect-Queries|X-WIPP|X-RequestManager-Memo|X-Request-Memo)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (16, 7, '/(vhost|bbs|host|wwwroot|www|site|root|backup|data|ftp|db|admin|website|web).*\\.(rar|sql|zip|tar\\.gz|tar)/', 1, NULL);
INSERT INTO `cms_re` VALUES (17, 7, '/\\.(htaccess|mysql_history|bash_history|DS_Store|idea|user\\.ini)/i', 1, NULL);
INSERT INTO `cms_re` VALUES (18, 10, '~\\d+~', 0, '匹配请求报文中的所有数字');
INSERT INTO `cms_re` VALUES (19, 10, '~添加说明~', 0, '正则添加后先使用无痕浏览器访问下首页查看是否有误,由正则导致系统崩溃只能在数据库中该字段了');
INSERT INTO `cms_re` VALUES (20, 10, '~webshell~i', 0, '匹配含有webshell的字段,i为不区分大小写');



-- ----------------------------
-- Table structure for cms_key
-- ----------------------------
DROP TABLE IF EXISTS `cms_key`;
CREATE TABLE `cms_key`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `favicon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `key1` varchar(666) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `key2` varchar(666) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cms_key
-- ----------------------------
INSERT INTO `cms_key` VALUES (3, '2024-08-07 00:54:17', 'grafana', 'Grafana', 'https://grafana.com/static/assets/img/fav32.png', 'grafana-app', '\'window.grafanabootdata = \'', '1');
INSERT INTO `cms_key` VALUES (2, '2024-08-07 00:54:22', 'gitlab', '登录 · GitLab', 'https://about.gitlab.com/nuxt-images/ico/favicon-96x96.png', 'content=\"gitlab community edition\"\ngon.default_issues_tracker', '<a href=\"https://about.gitlab.com/\">about gitlab\nclass=\"col-sm-7 brand-holder pull-left\"\n\'content=\"gitlab \'', '1');
INSERT INTO `cms_key` VALUES (1, '2024-08-05 14:10:54', '默认值', 'ZHIYUN', '/favicon.ico', '注释', '注释', '1');
INSERT INTO `cms_key` VALUES (4, '2024-08-07 00:40:12', 'Nacos', 'Nacos', 'https://nacos.io/favicon.ico', '	<link rel=\"shortcut icon\" href=\"console-ui/public/img/nacos-logo.png\" type=\"image/x-icon\">\n', '	<script src=\"console-ui/public/js/codemirror.addone.json-lint.js\"></script>\n', '1');
INSERT INTO `cms_key` VALUES (5, '2024-08-07 00:48:03', 'Jenkins', 'Jenkins', 'https://www.jenkins.io/favicon.ico', 'img id=\"jenkins-head-icon\" alt=\"title\" src=\"/static/63bf1834/images/jenkins-redbg.png\" ', '<body id=\"jenkins\" data-version=\"jenkins-1.573\" class=\"yui-skin-sam jenkins-1.573\">', '1');


-- ----------------------------
-- Table structure for cms_vuln
-- ----------------------------
DROP TABLE IF EXISTS `cms_vuln`;
CREATE TABLE `cms_vuln`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `range` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rerequest` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `response` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `remark` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cms_vuln
-- ----------------------------
INSERT INTO `cms_vuln` VALUES (1, '2024-11-15 13:03:39', 'requestUri', '~systemLog/downFile.php\\?fileName=[./]*/etc/passwd$~', 'root:x:0:0:root:/root:/bin/bash\r\ndaemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin\r\nbin:x:2:2:bin:/bin:/usr/sbin/nologin\r\nsys:x:3:3:sys:/dev:/usr/sbin/nologin\r\nsync:x:4:65534:sync:/bin:/bin/sync\r\ngames:x:5:60:games:/usr/games:/usr/sbin/nologin\r\nman:x:6:12:man:/var/cache/man:/usr/sbin/nologin\r\nlp:x:7:7:lp:/var/spool/lpd:/usr/sbin/nologin\r\nmail:x:8:8:mail:/var/mail:/usr/sbin/nologin\r\nnews:x:9:9:news:/var/spool/news:/usr/sbin/nologin\r\nuucp:x:10:10:uucp:/var/spool/uucp:/usr/sbin/nologin\r\nproxy:x:13:13:proxy:/bin:/usr/sbin/nologin\r\nwww-data:x:33:33:www-data:/var/www:/usr/sbin/nologin\r\nbackup:x:34:34:backup:/var/backups:/usr/sbin/nologin\r\nmysql:x:1:1:mysql:/usr/sbin:/usr/sbin/nologin', 1, '海康威视流媒体管理服务器后台任意文件读取漏洞');
INSERT INTO `cms_vuln` VALUES (2, '2024-11-17 16:25:51', 'requestUri', '~systemLog/downFile.php\\?fileName=[./]*/etc/hostname$~', 'iZ2zrhywnj8wlb49jjpimhZ', 1, '海康威视流媒体管理服务器后台任意文件读取漏洞');
INSERT INTO `cms_vuln` VALUES (3, '2024-11-17 16:25:59', 'requestUri', '~systemLog/downFile.php\\?fileName=[./]*/root/.bash_history~', 'exit\nls\ncd /usr/lib64/\nls\nls -al libcrypto*\nmake\nsudo su\nls -al libcrypto*\nls -al libssl*\nln -s libssl.so.10 libssl3.so\nln -s  libssl3.so libssl.so.10\nls -al libssl*\nchmod -R 777  libssl.so.1.0.2k\nsudo su\ncd /usr/lib64/\nls\ndmesg\nmake run\nyum search autofs\nsudo yum install libsss_autofs autofs -y\nmake run\ndmesg\nmake run\ndmesg\nls\ncd ../\nls\nrm openssl-1.0.2k*\nls\nsudo rm -rf openssl-1.0.2k*\nls\nrz\nls\nmkdir tes\nls\ncd /home/liyonglei/.ssh\n', 1, '海康威视流媒体管理服务器后台任意文件读取漏洞');
INSERT INTO `cms_vuln` VALUES (4, '2024-11-17 16:24:24', 'requestUri', '~/.git/config$~', '[core]\n	repositoryformatversion = 0\n	filemode = true\n	bare = false\n	logallrefupdates = true\n[remote \"origin\"]\n	url = https://github.com/\n	fetch = +refs/heads/*:refs/remotes/origin/*\n[branch \"master\"]\n	remote = origin\n	merge = refs/heads/master', 1, 'git信息泄露');
INSERT INTO `cms_vuln` VALUES (6, '2024-11-17 16:31:17', 'requestUri', '~/jmreport/testConnection~', '{\"success\":false,\"message\":\"数据库连接失败：Communications link failure\\n\\nThe last packet sent successfully to the server was 0 milliseconds ago. The driver has not received any packets from the server.\",\"code\":500,\"result\":null,\"timestamp\":1730913441446}', 1, 'JeecgBoot 积木报表 testConnection');
INSERT INTO `cms_vuln` VALUES (9, '2024-11-17 16:41:47', 'requestUri', '~/v1/cs/ops/data/removal~', '{\"code\":500,\"message\":\"File \'/tmp/file676477537739304485.tmp\' does not exist\",\"data\":null}', 1, 'Nacos /v1/cs/ops/data/removal 远程代码执行漏洞');
INSERT INTO `cms_vuln` VALUES (8, '2024-11-17 16:46:04', 'requestContent', '~username=nacos.pass~', '{\"accessToken\":\"eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJuYWNvcyIsImV4cCI6MTczMDkzODAzMn0.-H4FQ65nNe2x3ooMxitBLogQKOh_gPkONZ8V_Oe1a9wYZiYV9yzbHyeTKZ90mB7XLJ5vZucc0wkwlJQ16PjlOg\",\"tokenTtl\":18000,\"globalAdmin\":true,\"username\":\"nacos\"}', 1, 'nacos弱口令');
INSERT INTO `cms_vuln` VALUES (7, '2024-11-17 16:53:18', 'requestUri', '~/swagger-resources/configuration/ui~', '{\"deepLinking\":true,\"displayOperationId\":false,\"defaultModelsExpandDepth\":1,\"defaultModelExpandDepth\":1,\"defaultModelRendering\":\"example\",\"displayRequestDuration\":false,\"docExpansion\":\"none\",\"filter\":false,\"operationsSorter\":\"alpha\",\"showExtensions\":false,\"showCommonExtensions\":false,\"tagsSorter\":\"alpha\",\"validatorUrl\":\"\",\"supportedSubmitMethods\":[\"get\",\"put\",\"post\",\"delete\",\"options\",\"head\",\"patch\",\"trace\"],\"swaggerBaseUiUrl\":\"\"}', 1, 'swagger ui 未授权访问漏洞');
INSERT INTO `cms_vuln` VALUES (10, '2024-11-17 16:54:44', 'requestUri', '~/nacos/v1/console/server/state~', '{\"version\":\"2.0.3\",\"standalone_mode\":\"standalone\",\"function_mode\":null}', 1, 'nacos版本');
INSERT INTO `cms_vuln` VALUES (11, '2024-11-17 17:03:02', 'requestUri', '~/actuator/env~', 'null', 1, 'SpringBoot Actuator');
INSERT INTO `cms_vuln` VALUES (5, '2024-11-17 17:08:15', 'requestUri', '~/www.tar.gz~', 'xxxx', 1, '备份文件');

-- ----------------------------
-- Table structure for pot_dnslog
-- ----------------------------
DROP TABLE IF EXISTS `pot_dnslog`;
CREATE TABLE `pot_dnslog`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NULL DEFAULT NULL,
  `domains` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ipaddr` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `payload` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pot_logte
-- ----------------------------
DROP TABLE IF EXISTS `pot_logte`;
CREATE TABLE `pot_logte`  (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '序号\r\n',
  `userip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '用户ip',
  `useraddrip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'ip归属地址',
  `date` datetime NOT NULL COMMENT '日期',
  `statuscode` int(9) NULL DEFAULT NULL COMMENT '状态码',
  `requests` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '请求报文',
  `response` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '响应报文',
  `wafstatus` tinyint(2) NULL DEFAULT NULL COMMENT '可疑状态',
  `waf` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '可疑分类',
  `wafmatches` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '可疑命中内容',
  `confusion` int(1) NULL DEFAULT NULL COMMENT '是否为混淆流量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for pot_mail
-- ----------------------------
DROP TABLE IF EXISTS `pot_mail`;
CREATE TABLE `pot_mail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `code` int(6) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for pot_ntlm
-- ----------------------------
DROP TABLE IF EXISTS `pot_ntlm`;
CREATE TABLE `pot_ntlm`  (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '序号\r\n',
  `date` datetime NOT NULL COMMENT '日期',
  `userip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '用户ip',
  `useraddrip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'ip归属地址',
  `flags` int(9) NULL DEFAULT NULL,
  `domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `user` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `host` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `lmResponse` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ntlmResponse` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `msg` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '原始数据',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for pot_users
-- ----------------------------
DROP TABLE IF EXISTS `pot_users`;
CREATE TABLE `pot_users`  (
  `id` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `lastloginip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `lastloginipaddr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `lastlogintime` datetime NOT NULL,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `count` int(6) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

INSERT INTO `pot_users` (`id`, `username`, `password`, `lastloginip`, `lastloginipaddr`, `lastlogintime`, `email`, `count`) VALUES (1, 'admin', '123456', '127.0.0.1', '本机地址', '2024-07-01 15:42:05', '123@qq.com', 0);

-- ----------------------------
-- Table structure for pot_users_log
-- ----------------------------
DROP TABLE IF EXISTS `pot_users_log`;
CREATE TABLE `pot_users_log`  (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `loginip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
  `loginipaddr` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `logintime` datetime NOT NULL,
  `status` int(1) NULL DEFAULT NULL,
  `useragent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for pot_users_phone
-- ----------------------------
DROP TABLE IF EXISTS `pot_users_phone`;
CREATE TABLE `pot_users_phone`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NULL DEFAULT NULL,
  `phone` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `userid` int(6) NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for cms_sysinfo
-- ----------------------------
DROP TABLE IF EXISTS `cms_sysinfo`;
CREATE TABLE `cms_sysinfo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cms_sysinfo
-- ----------------------------
INSERT INTO `cms_sysinfo` VALUES (1, '2024-08-09 19:26:09', 'title1', '欢迎来到ZHIYUN系统');
INSERT INTO `cms_sysinfo` VALUES (2, '2024-08-09 13:29:05', 'title2', '全面适配Win11、Win10、Win8、Win7等操作系统');
INSERT INTO `cms_sysinfo` VALUES (3, '2024-08-09 19:26:45', 'License', 'Copyright © 2024 ZHIYUN MIT Licensed\n');
INSERT INTO `cms_sysinfo` VALUES (4, '2024-08-09 20:07:27', 'syspath', '/xlogin/login');
INSERT INTO `cms_sysinfo` VALUES (5, '2024-08-09 19:18:59', 'mailhost', 'smtp.qq.com');
INSERT INTO `cms_sysinfo` VALUES (6, '2024-08-09 20:06:41', 'username', 'xxxxxx@qq.com');
INSERT INTO `cms_sysinfo` VALUES (7, '2024-08-09 20:06:41', 'password', 'xxxxxxxxxxxxxxx');
INSERT INTO `cms_sysinfo` VALUES (8, '2024-08-09 16:04:14', 'port', '465');

-- ----------------------------
-- View structure for agclasse
-- ----------------------------
DROP VIEW IF EXISTS `agclasse`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `agclasse` AS select `pot_logte`.`waf` AS `waf`,count(0) AS `count` from `pot_logte` where (`pot_logte`.`date` >= (now() - interval 1 month)) group by `pot_logte`.`waf` order by `count` desc;

-- ----------------------------
-- View structure for agcount
-- ----------------------------
DROP VIEW IF EXISTS `agcount`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `agcount` AS select '邮箱记录' AS `table_name`,count(0) AS `总数` from `pot_mail` union all select 'NTLM' AS `table_name`,count(0) AS `总数` from `pot_ntlm` union all select '首页注册' AS `table_name`,count(0) AS `总数` from `pot_users` union all select '首页登录' AS `table_name`,count(0) AS `总数` from `pot_users_log` union all select '手机记录' AS `table_name`,count(0) AS `总数` from `pot_users_phone`;

-- ----------------------------
-- View structure for agday
-- ----------------------------
DROP VIEW IF EXISTS `agday`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `agday` AS select cast(`pot_logte`.`date` as date) AS `day`,count(0) AS `count`,sum((case when (`pot_logte`.`wafstatus` = 1) then 1 else 0 end)) AS `wafstatus_count` from `pot_logte` where (`pot_logte`.`date` >= (now() - interval 31 day)) group by cast(`pot_logte`.`date` as date) order by cast(`pot_logte`.`date` as date);

-- ----------------------------
-- View structure for agmothe
-- ----------------------------
DROP VIEW IF EXISTS `agmothe`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `agmothe` AS select `pot_logte`.`useraddrip` AS `useraddrip`,`pot_logte`.`userip` AS `userip`,count(0) AS `count`,group_concat(distinct `pot_logte`.`waf` separator ', ') AS `waf_combined` from `pot_logte` where ((`pot_logte`.`wafstatus` = 1) and (`pot_logte`.`date` >= (now() - interval 1 month))) group by `pot_logte`.`useraddrip`,`pot_logte`.`userip` order by `count` desc;

-- ----------------------------
-- View structure for agpot
-- ----------------------------
DROP VIEW IF EXISTS `agpot`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `agpot` AS with `recent_data` as (select `pot_mail`.`date` AS `date`,(concat(`pot_mail`.`email`,'成功发送验证码') collate utf8_unicode_ci) AS `field_value` from `pot_mail` union all select `pot_ntlm`.`date` AS `date`,concat('NTLM抓取主机名: ',`pot_ntlm`.`host`) AS `field_value` from `pot_ntlm` union all select `pot_users_log`.`logintime` AS `date`,concat('尝试登录账号: ',`pot_users_log`.`username`,'/',`pot_users_log`.`password`) AS `field_value` from `pot_users_log` union all select `pot_users`.`lastlogintime` AS `date`,concat('成功登录账号: ',`pot_users`.`username`,'/',`pot_users`.`password`) AS `field_value` from `pot_users` union all select `pot_users_phone`.`date` AS `date`,concat('抓取手机号: ',`pot_users_phone`.`phone`) AS `field_value` from `pot_users_phone`) select `recent_data`.`date` AS `date`,`recent_data`.`field_value` AS `field_value` from `recent_data` where (`recent_data`.`date` >= (curdate() - interval 1 month)) order by `recent_data`.`date` desc;

-- ----------------------------
-- View structure for agstatuscode
-- ----------------------------
DROP VIEW IF EXISTS `agstatuscode`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `agstatuscode` AS select `pot_logte`.`statuscode` AS `name`,count(0) AS `value` from `pot_logte` where (`pot_logte`.`date` >= (now() - interval 1 month)) group by `pot_logte`.`statuscode` order by `name` desc;

-- ----------------------------
-- View structure for wafmatch
-- ----------------------------
DROP VIEW IF EXISTS `wafmatch`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `wafmatch` AS select `cms_node`.`id` AS `id`,`cms_node`.`name` AS `name`,`cms_re`.`id` AS `re_id`,`cms_re`.`key` AS `key`,`cms_re`.`status` AS `status`,`cms_re`.`remark` AS `remark` from (`cms_node` join `cms_re` on((`cms_node`.`id` = `cms_re`.`node`)));

SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO `cms_admin` (`id`, `username`, `password`, `lastloginip`, `lastloginipaddr`, `lastlogintime`, `email`, `count`) VALUES (1, 'admin', 'b2608567efc2ec2eecc4b5a3ab20952a', '127.0.0.1', '本机地址', '2024-01-01 08:08:08', '123@qq.com', 0);