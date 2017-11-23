/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yii2_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-11-09 17:15:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('超级管理员', '1', '1502423920');

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`) USING BTREE,
  KEY `idx-auth_item-type` (`type`) USING BTREE,
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/admin/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1502421807', '1502421807');
INSERT INTO `auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1502421808', '1502421808');
INSERT INTO `auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/admin/user/activate', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/admin/user/change-password', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/admin/user/delete', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/index', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/login', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/logout', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/request-password-reset', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/reset-password', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/admin/user/signup', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/admin/user/view', '2', null, null, null, '1502421809', '1502421809');
INSERT INTO `auth_item` VALUES ('/debug/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/default/db-explain', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/default/toolbar', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/debug/user/*', '2', null, null, null, '1509177756', '1509177756');
INSERT INTO `auth_item` VALUES ('/debug/user/reset-identity', '2', null, null, null, '1509177756', '1509177756');
INSERT INTO `auth_item` VALUES ('/debug/user/set-identity', '2', null, null, null, '1509177756', '1509177756');
INSERT INTO `auth_item` VALUES ('/gii/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/gii/default/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/gii/default/action', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/gii/default/diff', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/gii/default/index', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/gii/default/preview', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/gii/default/view', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/site/*', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/site/error', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/site/index', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/site/login', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/site/logout', '2', null, null, null, '1502421810', '1502421810');
INSERT INTO `auth_item` VALUES ('/upload/*', '2', null, null, null, '1503649625', '1503649625');
INSERT INTO `auth_item` VALUES ('超级管理员', '1', 'admin', null, null, '1502423881', '1502423881');

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`) USING BTREE,
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/assignment/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/assignment/assign');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/assignment/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/assignment/revoke');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/assignment/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/default/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/default/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/create');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/delete');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/update');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/menu/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/assign');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/create');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/delete');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/remove');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/update');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/permission/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/assign');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/create');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/delete');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/remove');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/update');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/role/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/route/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/route/assign');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/route/create');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/route/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/route/refresh');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/route/remove');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/rule/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/rule/create');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/rule/delete');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/rule/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/rule/update');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/rule/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/activate');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/change-password');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/delete');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/login');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/logout');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/request-password-reset');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/reset-password');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/signup');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/admin/user/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/default/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/default/db-explain');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/default/download-mail');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/default/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/default/toolbar');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/default/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/user/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/user/reset-identity');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/debug/user/set-identity');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/default/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/default/action');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/default/diff');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/default/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/default/preview');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/gii/default/view');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/site/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/site/error');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/site/index');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/site/login');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/site/logout');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/upload/*');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`) USING BTREE,
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '权限管理', null, null, null, 0x7B2269636F6E223A2022636F67222C202276697369626C65223A20747275657D);
INSERT INTO `menu` VALUES ('3', '用户列表', '1', '/admin/user/index', null, null);
INSERT INTO `menu` VALUES ('4', '分配', '1', '/admin/assignment/index', null, null);
INSERT INTO `menu` VALUES ('5', '角色列表', '1', '/admin/role/index', null, null);
INSERT INTO `menu` VALUES ('6', '权限列表', '1', '/admin/permission/index', null, 0x7B2269636F6E223A2022636F67222C202276697369626C65223A2066616C73657D);
INSERT INTO `menu` VALUES ('7', '路由列表', '1', '/admin/route/index', null, null);
INSERT INTO `menu` VALUES ('8', '规则列表', '1', '/admin/rule/index', null, 0x7B2269636F6E223A2022636F67222C202276697369626C65223A2066616C73657D);
INSERT INTO `menu` VALUES ('9', '菜单列表', '1', '/admin/menu/index', null, null);
INSERT INTO `menu` VALUES ('10', '首页', null, null, null, null);
INSERT INTO `menu` VALUES ('11', '系统工具', null, null, null, 0x7B2269636F6E223A20226C6966652D72696E67222C202276697369626C65223A20747275657D);
INSERT INTO `menu` VALUES ('12', 'Gii', '11', '/gii/default/index', null, null);
INSERT INTO `menu` VALUES ('13', 'Debug', '11', '/debug/default/index', null, null);

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', '1502421111');
INSERT INTO `migration` VALUES ('m140602_111327_create_menu_table', '1502421114');
INSERT INTO `migration` VALUES ('m160312_050000_create_user', '1502421115');
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init', '1502421206');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '1xx5O5kezhKgQCnHv8GhIoxToukl7nnC', '$2y$13$gnt.myw2fNsryvuwuzpoluWoGLyqcDAGtZRkGHYnPHXfiWM5Ag8KC', null, '1042080686@qq.com', '10', '1502422637', '1502422637');
