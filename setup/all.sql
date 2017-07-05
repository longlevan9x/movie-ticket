/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : moza_yii2_framework

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-07-01 17:39:09
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `application`
-- ----------------------------
DROP TABLE IF EXISTS `application`;
CREATE TABLE `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(300) DEFAULT NULL COMMENT 'editor:upload',
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `keywords` varchar(1000) DEFAULT NULL,
  `note` varchar(3000) DEFAULT NULL,
  `storage_max` bigint(20) DEFAULT NULL COMMENT 'group:storage',
  `storage_current` bigint(20) DEFAULT NULL COMMENT 'group:storage',
  `address` varchar(255) DEFAULT NULL COMMENT 'group:contact',
  `map` varchar(255) DEFAULT NULL COMMENT 'group:contact;grid:hidden',
  `website` varchar(255) DEFAULT NULL COMMENT 'group:contact',
  `email` varchar(255) DEFAULT NULL COMMENT 'group:contact',
  `phone` varchar(255) DEFAULT NULL COMMENT 'group:contact',
  `fax` varchar(255) DEFAULT NULL COMMENT 'group:contact',
  `chat` varchar(255) DEFAULT NULL COMMENT 'group:contact',
  `facebook` varchar(255) DEFAULT NULL COMMENT 'grid:hidden;group:social',
  `twitter` varchar(255) DEFAULT NULL COMMENT 'grid:hidden;group:social',
  `google` varchar(255) DEFAULT NULL COMMENT 'grid:hidden;group:social',
  `youtube` varchar(255) DEFAULT NULL COMMENT 'grid:hidden;group:social',
  `copyright` varchar(255) DEFAULT NULL COMMENT 'grid:hidden;',
  `terms_of_service` varchar(300) DEFAULT NULL COMMENT 'editor:file;group:common',
  `profile` varchar(300) DEFAULT NULL COMMENT 'editor:file;group:common',
  `privacy_policy` varchar(300) DEFAULT NULL COMMENT 'editor:file;group:common',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'group:common',
  `type` varchar(100) DEFAULT NULL COMMENT 'data:ONEPAGE,COMPANY,ECOMMERCE,SOCIAL,MUSIC,EDUCATION',
  `status` varchar(100) DEFAULT NULL COMMENT 'data:DEMO,LIVE,CLOSED,SUSPEND',
  `owner_id` varchar(100) DEFAULT NULL COMMENT 'editor:select;lookup:@user,id,username;group:common',
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of application
-- ----------------------------
INSERT INTO `application` VALUES ('1', 'application1_logo.png', 'default', 'MOZA Group', 'Always the best', '', '', '50000', null, '17 Phung CHi Kien, Cau Giay, Ha noi, Vietnam.', '', 'www.mozagroup.com', 'hung.hoxuan@gmail.com', '84912738748', '', '', '', '', '', 'https://www.youtube.com/channel/UCyw4WvIz4CbBBipCJpVTQjQ', 'Copyright by', '', '', '', '1', '', '', '6', '2016-10-03 13:15:39', '6', '2016-10-26 01:41:26', '6');

-- ----------------------------
-- Table structure for `app_user`
-- ----------------------------
DROP TABLE IF EXISTS `app_user`;
CREATE TABLE `app_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avatar` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `content` text,
  `gender` varchar(100) DEFAULT NULL COMMENT 'group:PERSONAL',
  `dob` varchar(255) DEFAULT NULL COMMENT 'group:PERSONAL',
  `phone` varchar(25) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL COMMENT 'group:PERSONAL',
  `height` varchar(255) DEFAULT NULL COMMENT 'group:PERSONAL',
  `address` varchar(255) DEFAULT NULL COMMENT 'group:LOCATION',
  `country` varchar(100) DEFAULT NULL COMMENT 'group:LOCATION',
  `state` varchar(100) DEFAULT NULL COMMENT 'group:LOCATION',
  `city` varchar(100) DEFAULT NULL COMMENT 'group:LOCATION',
  `balance` decimal(10,0) DEFAULT NULL COMMENT 'group:FINANCE',
  `point` int(11) DEFAULT NULL COMMENT 'group:FINANCE',
  `card_number` varchar(255) DEFAULT NULL COMMENT 'group:PAYMENT',
  `card_cvv` varchar(255) DEFAULT NULL COMMENT 'editor:text;group:PAYMENT',
  `card_exp` varchar(255) DEFAULT NULL COMMENT 'group:PAYMENT',
  `lat` varchar(255) DEFAULT NULL COMMENT 'group:LOCATION',
  `long` varchar(255) DEFAULT NULL COMMENT 'group:LOCATION',
  `rate` float DEFAULT NULL COMMENT 'group:RATINGS;',
  `rate_count` int(11) DEFAULT NULL COMMENT 'group:RATINGS;',
  `is_online` tinyint(1) DEFAULT NULL COMMENT 'group:GROUPING',
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'group:GROUPING',
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL COMMENT 'data:PENDING,BANNED,REJECTED,NORMAL,PRO,VIP',
  `role` int(2) DEFAULT NULL COMMENT 'data:10:USER,20:MODERATOR,30:ADMIN;editor:select;group:GROUPING',
  `provider_id` varchar(100) DEFAULT NULL COMMENT 'lookup:@provider',
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of app_user
-- ----------------------------

-- ----------------------------
-- Table structure for `app_user_device`
-- ----------------------------
DROP TABLE IF EXISTS `app_user_device`;
CREATE TABLE `app_user_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'lookup:@app_user',
  `ime` varchar(255) NOT NULL,
  `gcm_id` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of app_user_device
-- ----------------------------

-- ----------------------------
-- Table structure for `app_user_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `app_user_feedback`;
CREATE TABLE `app_user_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL COMMENT 'lookup:@app_user',
  `object_id` varchar(100) DEFAULT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `comment` varchar(4000) NOT NULL,
  `response` text,
  `type` varchar(100) DEFAULT NULL COMMENT 'data:Question,Feedback,Report',
  `status` varchar(100) DEFAULT NULL COMMENT 'data:New,Received,Processing,Pending,Closed',
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of app_user_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for `app_user_token`
-- ----------------------------
DROP TABLE IF EXISTS `app_user_token`;
CREATE TABLE `app_user_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of app_user_token
-- ----------------------------
INSERT INTO `app_user_token` VALUES ('66', '12', '5c804ecda04320124cd26a6ff4be7f1f', '2016-06-30 10:24:38');
INSERT INTO `app_user_token` VALUES ('108', '6', '0380c4baf39d05a2937b1d1f55ebcad8', '2016-07-01 17:23:50');
INSERT INTO `app_user_token` VALUES ('115', '1', '790e8065b42517072891b761c0f9de2d', '2016-07-07 01:16:15');
INSERT INTO `app_user_token` VALUES ('119', '8', 'e552d7af875986ffa6da843bd077d59e', '2016-07-05 11:32:38');
INSERT INTO `app_user_token` VALUES ('131', '14', '7b57883f8d0f12fc0463c425dd8f09bf', '2016-07-06 10:17:20');
INSERT INTO `app_user_token` VALUES ('135', '2', '628565b445f604ede847942c7af3d3f4', '2016-07-07 11:05:21');
INSERT INTO `app_user_token` VALUES ('136', '10', '492de1fc848a6cf9fffb02d971dcf5bf', '2016-07-07 11:07:16');
INSERT INTO `app_user_token` VALUES ('137', '15', 'aacdee4a314980d048fb22bc2b107543', '2016-07-07 11:20:01');

-- ----------------------------
-- Table structure for `app_user_transaction`
-- ----------------------------
DROP TABLE IF EXISTS `app_user_transaction`;
CREATE TABLE `app_user_transaction` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `receiver_user_id` varchar(100) NOT NULL COMMENT 'lookup:@app_user',
  `object_id` varchar(100) DEFAULT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `amount` decimal(20,2) NOT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `payment_method` varchar(100) NOT NULL COMMENT 'data:POINT,CREDIT,CASH,BANK,PAYPAL,WU',
  `note` varchar(2000) DEFAULT NULL,
  `time` varchar(20) NOT NULL,
  `action` varchar(255) DEFAULT NULL COMMENT 'data:SYSTEM_ADJUST,CANCELLATION_ORDER_FEE,EXCHANGE_POINT,REDEEM_POINT,TRANSFER_POINT,TRIP_PAYMENT,PASSENGER_SHARE_BONUS,DRIVER_SHARE_BONUS',
  `type` varchar(100) DEFAULT NULL COMMENT 'data:PLUS,MINUS',
  `status` varchar(100) NOT NULL COMMENT 'data:PENDING=0,APPROVED=1,REJECTED=-1',
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of app_user_transaction
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_group
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_permission`
-- ----------------------------
DROP TABLE IF EXISTS `auth_permission`;
CREATE TABLE `auth_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `object2_id` bigint(20) NOT NULL,
  `object2_type` varchar(100) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` int(5) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_permission
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_role`
-- ----------------------------
DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE `auth_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_role
-- ----------------------------

-- ----------------------------
-- Table structure for `migration`
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migration
-- ----------------------------

-- ----------------------------
-- Table structure for `object_actions`
-- ----------------------------
DROP TABLE IF EXISTS `object_actions`;
CREATE TABLE `object_actions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` varchar(100) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `name` varchar(2000) DEFAULT NULL,
  `old_content` text,
  `content` text NOT NULL,
  `action` varchar(100) NOT NULL COMMENT 'data:comment,create,update,delete,approve,reject,feedback',
  `is_active` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_actions
-- ----------------------------

-- ----------------------------
-- Table structure for `object_attributes`
-- ----------------------------
DROP TABLE IF EXISTS `object_attributes`;
CREATE TABLE `object_attributes` (
  `object_id` int(11) NOT NULL,
  `object_type` varchar(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `application_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`object_id`,`object_type`,`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_attributes
-- ----------------------------

-- ----------------------------
-- Table structure for `object_category`
-- ----------------------------
DROP TABLE IF EXISTS `object_category`;
CREATE TABLE `object_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `sort_order` int(5) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_top` tinyint(1) DEFAULT NULL,
  `is_hot` tinyint(1) DEFAULT NULL,
  `object_type` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_category
-- ----------------------------

-- ----------------------------
-- Table structure for `object_comment`
-- ----------------------------
DROP TABLE IF EXISTS `object_comment`;
CREATE TABLE `object_comment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` varchar(255) NOT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `comment` varchar(4000) DEFAULT NULL,
  `app_user_id` varchar(100) DEFAULT NULL COMMENT 'lookup:@app_user',
  `user_id` varchar(100) DEFAULT NULL COMMENT 'lookup:@user',
  `user_type` varchar(100) DEFAULT NULL COMMENT 'data:app_user,user',
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of object_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `object_file`
-- ----------------------------
DROP TABLE IF EXISTS `object_file`;
CREATE TABLE `object_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `file` varchar(555) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file_duration` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` tinyint(5) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_file
-- ----------------------------

-- ----------------------------
-- Table structure for `object_message`
-- ----------------------------
DROP TABLE IF EXISTS `object_message`;
CREATE TABLE `object_message` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `object_id` varchar(100) NOT NULL COMMENT 'lookup:@app_user',
  `object_type` varchar(100) DEFAULT NULL,
  `message` varchar(4000) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL COMMENT 'data:Plan,Sent,Received,Read',
  `type` varchar(100) DEFAULT NULL COMMENT 'data:Warning,Birthday,Remind,Promotion',
  `method` varchar(100) DEFAULT NULL COMMENT 'data:Push,Email,SMS',
  `sent_date` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of object_message
-- ----------------------------

-- ----------------------------
-- Table structure for `object_relation`
-- ----------------------------
DROP TABLE IF EXISTS `object_relation`;
CREATE TABLE `object_relation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `object2_id` bigint(20) NOT NULL,
  `object2_type` varchar(100) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `sort_order` int(5) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_relation
-- ----------------------------

-- ----------------------------
-- Table structure for `object_reviews`
-- ----------------------------
DROP TABLE IF EXISTS `object_reviews`;
CREATE TABLE `object_reviews` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `rate` float DEFAULT NULL,
  `comment` varchar(2000) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'lookup:@app_user',
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of object_reviews
-- ----------------------------

-- ----------------------------
-- Table structure for `object_setting`
-- ----------------------------
DROP TABLE IF EXISTS `object_setting`;
CREATE TABLE `object_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(255) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` text,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `sort_order` int(5) NOT NULL,
  `application_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `object_translation`
-- ----------------------------
DROP TABLE IF EXISTS `object_translation`;
CREATE TABLE `object_translation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) DEFAULT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `lang` varchar(100) DEFAULT NULL,
  `content` text,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_translation
-- ----------------------------

-- ----------------------------
-- Table structure for `object_type`
-- ----------------------------
DROP TABLE IF EXISTS `object_type`;
CREATE TABLE `object_type` (
  `object_type` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sort_order` int(5) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`object_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of object_type
-- ----------------------------

-- ----------------------------
-- Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metaKey` varchar(255) DEFAULT NULL,
  `metaValue` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metaKey` varchar(255) NOT NULL,
  `metaValue` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `editor` varchar(255) DEFAULT NULL COMMENT 'editor:select;lookup:editor',
  `lookup` varchar(255) DEFAULT NULL COMMENT 'editor:select;lookup:object_type',
  `is_active` tinyint(1) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2396 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------

-- ----------------------------
-- Table structure for `settings_menu`
-- ----------------------------
DROP TABLE IF EXISTS `settings_menu`;
CREATE TABLE `settings_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(300) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL COMMENT 'lookup:module',
  `group` varchar(100) DEFAULT NULL COMMENT 'data:FRONTEND,BACKEND',
  `role` varchar(100) DEFAULT NULL,
  `menu_type` varchar(100) DEFAULT NULL COMMENT 'data:CATEGORY,TYPE,STATUS,MIXED',
  `display_type` varchar(100) DEFAULT NULL COMMENT 'data:DEFAULT,TREE,MEGA',
  `sort_order` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=385 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of settings_menu
-- ----------------------------

-- ----------------------------
-- Table structure for `settings_schema`
-- ----------------------------
DROP TABLE IF EXISTS `settings_schema`;
CREATE TABLE `settings_schema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `dbType` varchar(100) DEFAULT NULL COMMENT 'data:numeric,bool,float,varchar,text,date,time,datetime',
  `editor` varchar(100) DEFAULT NULL COMMENT 'data:text,textarea,select,numeric,currency,boolean,date,time,datetime,range,file,image',
  `lookup` varchar(255) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL,
  `algorithm` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `roles` varchar(500) DEFAULT NULL COMMENT 'lookup:role',
  `sort_order` int(5) DEFAULT NULL,
  `is_group` tinyint(1) DEFAULT NULL,
  `is_column` tinyint(1) DEFAULT NULL,
  `is_readonly` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2452 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings_schema
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `overview` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identity_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organization` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `department` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `role` int(2) DEFAULT NULL COMMENT 'data:10:USER,20:MODERATOR,30:ADMIN',
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT 'data:DISABLED=0,ACTIVE=10',
  `is_online` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `application_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('6', null, 'Admin', 'admin', 'user6_image.png', null, null, 'WmzV9waECMlzP_EhXKd4PLw-_sGeMz12', '$2y$13$s5yLryk16awaMfDWpiQy7OZbs/ueqFKNE7DG5UA6yDbmrGwfL8I7i', 'Nph5RP9UXI9F0I0jITJqUnzxnhobKs2S_1473239211', null, null, null, null, 'hung.hoxuan@gmail.com', null, null, null, null, null, null, null, null, null, null, '30', null, '10', '1', '2017-06-12 00:15:58', '2017-06-12 00:15:36', '1473239211', '1477291259', 'trayolo');
