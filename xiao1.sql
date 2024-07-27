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

SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO `cms_admin` (`id`, `username`, `password`, `lastloginip`, `lastloginipaddr`, `lastlogintime`, `email`, `count`) VALUES (1, 'admin', 'b2608567efc2ec2eecc4b5a3ab20952a', '127.0.0.1', '本机地址', '2024-01-01 08:08:08', '123@qq.com', 0);