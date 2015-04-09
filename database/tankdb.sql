-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 07 月 08 日 03:32
-- 服务器版本: 5.5.8
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tankdb`
--

-- --------------------------------------------------------

--
-- 表的结构 `tk_announcement`
--

CREATE TABLE IF NOT EXISTS `tk_announcement` (
  `AID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_anc_title` varchar(80) NOT NULL,
  `tk_anc_text` text NOT NULL,
  `tk_anc_type` smallint(4) NOT NULL DEFAULT '0',
  `tk_anc_create` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_anc_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`AID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_announcement`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_bug`
--

CREATE TABLE IF NOT EXISTS `tk_bug` (
  `bugid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_bug_title` text,
  `tk_bug_description` text,
  `tk_bug_type` text,
  `tk_bug_priority` text,
  `tk_bug_project` text,
  `tk_bug_project_sub` text,
  `tk_bug_attachment` text,
  `tk_bug_log` text,
  `tk_bug_comment` text,
  `tk_bug_status` text,
  `tk_bug_from_team` text,
  `tk_bug_from` text,
  `tk_bug_to_team` text,
  `tk_bug_to` text,
  `tk_bug_url` text,
  `tk_bug_createtime` text,
  `tk_bug_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tk_bug_backup1` text,
  `tk_bug_backup2` text,
  `tk_bug_backup3` text,
  `tk_bug_backup4` text,
  `tk_bug_backup5` text,
  `tk_bug_backup6` text,
  PRIMARY KEY (`bugid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_bug`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_comment`
--

CREATE TABLE IF NOT EXISTS `tk_comment` (
  `coid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_comm_title` text NOT NULL,
  `tk_comm_text` varchar(60) NOT NULL,
  `tk_comm_type` tinyint(2) NOT NULL DEFAULT '0',
  `tk_comm_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_comm_pid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_comm_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`coid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_document`
--

CREATE TABLE IF NOT EXISTS `tk_document` (
  `docid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_doc_title` varchar(80) NOT NULL,
  `tk_doc_description` longtext NOT NULL,
  `tk_doc_attachment` varchar(255) NOT NULL DEFAULT '',
  `tk_doc_class1` bigint(20) NOT NULL DEFAULT '0',
  `tk_doc_class2` bigint(20) NOT NULL DEFAULT '0',
  `tk_doc_type` varchar(20) NOT NULL,
  `tk_doc_create` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_doc_createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tk_doc_edit` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_doc_edittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tk_doc_backup1` tinyint(2) NOT NULL DEFAULT '0',
  `tk_doc_backup2` varchar(60) NOT NULL,
  PRIMARY KEY (`docid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_document`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_item`
--

CREATE TABLE IF NOT EXISTS `tk_item` (
  `item_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `tk_item_key` varchar(60) CHARACTER SET utf8 NOT NULL,
  `tk_item_value` varchar(60) CHARACTER SET utf8 NOT NULL,
  `tk_item_title` varchar(60) CHARACTER SET utf8 NOT NULL,
  `tk_item_description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `tk_item_type` varchar(20) CHARACTER SET utf8 NOT NULL,
  `tk_item_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `tk_item`
--

INSERT INTO `tk_item` (`item_id`, `tk_item_key`, `tk_item_value`, `tk_item_title`, `tk_item_description`, `tk_item_type`, `tk_item_lastupdate`) VALUES
(3, 'outofdate', 'on', '是否显示过期任务提醒', '”on“为开启，”off“为关闭', 'setting', '2012-06-10 14:03:36'),
(7, 'mail_create', 'off', '新任务提醒', '当有新任务到达时提醒任务执行人 "on" 为启用该功能, "off" 为禁用该功能', 'setting_mail', '2012-07-04 22:02:47'),
(8, 'mail_update', 'off', '任务更新提醒', '当任务状态更新时提醒任务负责人(来自谁) "on" 为启用该功能, "off" 为禁用该功能', 'setting_mail', '2012-07-04 22:02:50'),
(9, 'mail_comment', 'off', '新备注提醒', '当任务有新备注时提醒任务执行人 "on" 为启用该功能, "off" 为禁用该功能', 'setting_mail', '2012-07-04 22:02:53'),
(10, 'mail_host', 'smtp.sina.com', 'SMTP邮件服务器', 'SMTP邮件服务器地址,如:smtp.sina.com', 'setting_mail', '2012-06-16 22:42:15'),
(11, 'mail_port', '25', 'SMTP邮件服务器端口', 'SMTP邮件服务器的端口号,默认为25，无需修改', 'setting_mail', '2012-06-16 23:00:04'),
(12, 'mail_username', 'yourname@sina.com', '用户名', '用户名:邮件帐号的用户名,如使用新浪邮箱，请填写完整的邮件地址,如: yourname@sina.com', 'setting_mail', '2012-07-04 22:03:05'),
(13, 'mail_password', 'yourpassword', '密码', '密码:邮件帐号的密码', 'setting_mail', '2012-07-04 22:03:19'),
(14, 'mail_from', 'yourname@sina.com', '发送邮件的邮箱', '发送邮件的邮箱,如: yourname@sina.com', 'setting_mail', '2012-07-04 22:03:10'),
(15, 'mail_fromname', 'WSS', '显示名称', '邮件发送人的显示名称', 'setting_mail', '2012-06-16 22:57:02'),
(16, 'mail_charset', 'UTF-8', '编码格式', '邮件编码格式设置，默认为UTF-8，无需修改', 'setting_mail', '2012-06-16 23:00:11'),
(17, 'mail_auth', 'true', 'SMTP验证', '启用SMTP验证功能，默认为true，无需修改', 'setting_mail', '2012-06-16 23:02:12'),
(18, 'maxrows_task', '20', '每页任务数', '任务列表页，每页显示的任务数量，只支持正整数', 'setting', '2012-06-17 14:57:57'),
(19, 'maxrows_timeout', '5', '每页过期任务数', '任务列表页，每页显示的过期任务数量，只支持正整数', 'setting', '2012-06-17 14:58:04'),
(20, 'maxrows_project', '20', '每页项目数', '项目列表页，每页显示的项目数量，只支持正整数', 'setting', '2012-06-17 15:00:32'),
(21, 'maxrows_user', '20', '每页用户数', '用户列表页，每页显示的用户数量，只支持正整数', 'setting', '2012-06-17 15:09:37'),
(22, 'maxrows_announcement', '20', '每页公告数', '公告列表页，每页显示的公告数量，只支持正整数', 'setting', '2012-06-17 15:25:23');

-- --------------------------------------------------------

--
-- 表的结构 `tk_item01`
--

CREATE TABLE IF NOT EXISTS `tk_item01` (
  `im01id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_im01_field01` text,
  `tk_im01_field02` text,
  `tk_im01_field03` text,
  `tk_im01_field04` text,
  `tk_im01_field05` text,
  `tk_im01_field06` text,
  `tk_im01_field07` text,
  `tk_im01_field08` text,
  `tk_im01_field09` text,
  `tk_im01_field10` text,
  `tk_im01_field11` text,
  `tk_im01_field12` text,
  `tk_im01_field13` text,
  `tk_im01_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`im01id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_item01`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_item02`
--

CREATE TABLE IF NOT EXISTS `tk_item02` (
  `im02id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_im02_field01` text,
  `tk_im02_field02` text,
  `tk_im02_field03` text,
  `tk_im02_field04` text,
  `tk_im02_field05` text,
  `tk_im02_field06` text,
  `tk_im02_field07` text,
  `tk_im02_field08` text,
  `tk_im02_field09` text,
  `tk_im02_field10` text,
  `tk_im02_field11` text,
  `tk_im02_field12` text,
  `tk_im02_field13` text,
  `tk_im02_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`im02id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_item02`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_item03`
--

CREATE TABLE IF NOT EXISTS `tk_item03` (
  `im03id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_im03_field01` text,
  `tk_im03_field02` text,
  `tk_im03_field03` text,
  `tk_im03_field04` text,
  `tk_im03_field05` text,
  `tk_im03_field06` text,
  `tk_im03_field07` text,
  `tk_im03_field08` text,
  `tk_im03_field09` text,
  `tk_im03_field10` text,
  `tk_im03_field11` text,
  `tk_im03_field12` text,
  `tk_im03_field13` text,
  `tk_im03_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`im03id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_item03`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_item04`
--

CREATE TABLE IF NOT EXISTS `tk_item04` (
  `im04id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_im04_field01` text,
  `tk_im04_field02` text,
  `tk_im04_field03` text,
  `tk_im04_field04` text,
  `tk_im04_field05` text,
  `tk_im04_field06` text,
  `tk_im04_field07` text,
  `tk_im04_field08` text,
  `tk_im04_field09` text,
  `tk_im04_field10` text,
  `tk_im04_field11` text,
  `tk_im04_field12` text,
  `tk_im04_field13` text,
  `tk_im04_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`im04id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_item04`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_item05`
--

CREATE TABLE IF NOT EXISTS `tk_item05` (
  `im05id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_im05_field01` text,
  `tk_im05_field02` text,
  `tk_im05_field03` text,
  `tk_im05_field04` text,
  `tk_im05_field05` text,
  `tk_im05_field06` text,
  `tk_im05_field07` text,
  `tk_im05_field08` text,
  `tk_im05_field09` text,
  `tk_im05_field10` text,
  `tk_im05_field11` text,
  `tk_im05_field12` text,
  `tk_im05_field13` text,
  `tk_im05_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`im05id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_item05`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_item06`
--

CREATE TABLE IF NOT EXISTS `tk_item06` (
  `im06id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_im06_field01` text,
  `tk_im06_field02` text,
  `tk_im06_field03` text,
  `tk_im06_field04` text,
  `tk_im06_field05` text,
  `tk_im06_field06` text,
  `tk_im06_field07` text,
  `tk_im06_field08` text,
  `tk_im06_field09` text,
  `tk_im06_field10` text,
  `tk_im06_field11` text,
  `tk_im06_field12` text,
  `tk_im06_field13` text,
  `tk_im06_field14` text,
  `tk_im06_field15` text,
  `tk_im06_field16` text,
  `tk_im06_field17` text,
  `tk_im06_field18` text,
  `tk_im06_field19` text,
  `tk_im06_field20` text,
  `tk_im06_field21` text,
  `tk_im06_field22` text,
  `tk_im06_field23` text,
  `tk_im06_field24` text,
  `tk_im06_field25` text,
  `tk_im06_field26` text,
  `tk_im06_field27` text,
  `tk_im06_field28` text,
  `tk_im06_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`im06id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_item06`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_kpi`
--

CREATE TABLE IF NOT EXISTS `tk_kpi` (
  `kpid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_kpi_user` text,
  `tk_kpi_type` text,
  `tk_kpi_kpi1` text,
  `tk_kpi_kpi2` text,
  `tk_kpi_kpi3` text,
  `tk_kpi_kpi4` text,
  `tk_kpi_kpi5` int(11) DEFAULT NULL,
  `tk_kpi_kpi6` int(11) DEFAULT NULL,
  `tk_kpi_create` text,
  `tk_kpi_lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tk_kpi_year` text,
  `tk_kpi_month` text,
  `tk_kpi_backup1` text,
  `tk_kpi_backup2` text,
  PRIMARY KEY (`kpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_kpi`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_log`
--

CREATE TABLE IF NOT EXISTS `tk_log` (
  `logid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_log_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_log_action` text NOT NULL,
  `tk_log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tk_log_type` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_log_class` tinyint(2) NOT NULL DEFAULT '0',
  `tk_log_description` varchar(60) NOT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_manhour`
--

CREATE TABLE IF NOT EXISTS `tk_manhour` (
  `MHID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `manhour` text,
  `mh_year` text,
  `mh_mouth` text,
  `mh_backup1` text,
  `mh_backup2` text,
  PRIMARY KEY (`MHID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_manhour`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_menu`
--

CREATE TABLE IF NOT EXISTS `tk_menu` (
  `meid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_menu_title_cn` text,
  `tk_menu_title_en` text,
  `tk_menu_text_cn` text,
  `tk_menu_text_en` text,
  `tk_menu_sort` text,
  `tk_menu_status` text,
  `tk_menu_backup1` text,
  `tk_menu_backup2` text,
  PRIMARY KEY (`meid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_menu`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_message`
--

CREATE TABLE IF NOT EXISTS `tk_message` (
  `meid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_mess_touser` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_mess_fromuser` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_mess_title` text CHARACTER SET utf8 NOT NULL,
  `tk_mess_text` text CHARACTER SET utf8,
  `tk_mess_status` tinyint(2) NOT NULL DEFAULT '1',
  `tk_mess_type` tinyint(2) NOT NULL DEFAULT '0',
  `tk_mess_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`meid`),
  KEY `tk_mess_touser` (`tk_mess_touser`),
  KEY `tk_mess_fromuser` (`tk_mess_fromuser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_message`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_mul`
--

CREATE TABLE IF NOT EXISTS `tk_mul` (
  `muid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_mul_title` text,
  `tk_mul_zh_cn` text,
  `tk_mul_en_us` text,
  `tk_mul_other` text,
  `tk_mul_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tk_mul_backup1` text,
  `tk_mul_backup2` text,
  PRIMARY KEY (`muid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_mul`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_project`
--

CREATE TABLE IF NOT EXISTS `tk_project` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_name` varchar(80) NOT NULL,
  `project_code` varchar(60) NOT NULL,
  `project_text` text NOT NULL,
  `project_type` tinyint(2) NOT NULL DEFAULT '0',
  `project_from` varchar(60) NOT NULL,
  `project_from_user` varchar(60) NOT NULL,
  `project_from_contact` text NOT NULL,
  `project_start` date NOT NULL DEFAULT '0000-00-00',
  `project_end` date NOT NULL DEFAULT '0000-00-00',
  `project_to_dept` varchar(60) NOT NULL,
  `project_to_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `project_status` smallint(4) NOT NULL DEFAULT '0',
  `project_remark` varchar(60) NOT NULL,
  `project_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tk_project`
--

INSERT INTO `tk_project` (`id`, `project_name`, `project_code`, `project_text`, `project_type`, `project_from`, `project_from_user`, `project_from_contact`, `project_start`, `project_end`, `project_to_dept`, `project_to_user`, `project_status`, `project_remark`, `project_lastupdate`) VALUES
(1, '非项目任务', 'Other', '非项目任务，如：公司总体会议、请假等。', 0, '', '', '', '0000-00-00', '0000-00-00', '0001', 1, 23, '', '2013-05-02 11:22:29');

-- --------------------------------------------------------

--
-- 表的结构 `tk_project_sub`
--

CREATE TABLE IF NOT EXISTS `tk_project_sub` (
  `id` bigint(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `project_pid` text,
  `project_name` text,
  `project_code` text,
  `project_text` text,
  `project_type` text,
  `project_from` text,
  `project_from_user` text,
  `project_from_contact` text,
  `project_start` text,
  `project_end` text,
  `project_to_dept` text,
  `project_to_user` text,
  `project_status` longtext,
  `project_remark` text,
  `project_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `project_sub_backup1` text,
  `project_sub_backup2` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- 转存表中的数据 `tk_project_sub`
--

INSERT INTO `tk_project_sub` (`id`, `project_pid`, `project_name`, `project_code`, `project_text`, `project_type`, `project_from`, `project_from_user`, `project_from_contact`, `project_start`, `project_end`, `project_to_dept`, `project_to_user`, `project_status`, `project_remark`, `project_lastupdate`, `project_sub_backup1`, `project_sub_backup2`) VALUES
(000045, '00033', '其他', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '', '', '', NULL, '2010-02-28 20:15:36', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `tk_status`
--

CREATE TABLE IF NOT EXISTS `tk_status` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `task_status` varchar(60) NOT NULL,
  `task_status_display` varchar(255) NOT NULL,
  `task_status_backup1` bigint(20) NOT NULL DEFAULT '0',
  `task_status_backup2` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `tk_status`
--

INSERT INTO `tk_status` (`id`, `task_status`, `task_status_display`, `task_status_backup1`, `task_status_backup2`) VALUES
(2, '未开始', '<div style=''background-color: #996699; width:100%; text-align:center;''>未开始</div>', 1, 0),
(4, '计划', '<div style=''background-color: #996699; width:100%; text-align:center;''>计划</div>', 2, 0),
(5, '进行中', '<div style=''background-color: #9F0; width:100%; text-align:center;''>进行中</div>', 3, 0),
(6, '进行中20%', '<div style=''background-color: #9F0; width:100%; text-align:center;''>进行中20%</div>', 4, 0),
(7, '进行中40%', '<div style=''background-color: #9F0; width:100%; text-align:center;''>进行中40%</div>', 5, 0),
(8, '进行中60%', '<div style=''background-color: #9F0; width:100%; text-align:center;''>进行中60%</div>', 6, 0),
(9, '进行中80%', '<div style=''background-color: #9F0; width:100%; text-align:center;''>进行中80%</div>', 7, 0),
(14, '完成100%', '<div style=''background-color: #090; width:100%; text-align:center;''>完成100%</div>', 8, 0),
(22, '中断', '<div style=''background-color: red; width:100%; text-align:center;''>中断</div>', 9, 0),
(23, '推迟', '<div style=''background-color: #FC0; width:100%; text-align:center;''>推迟</div>', 10, 0),
(25, '完成验收', '<div style=''background-color: #336699; width:100%; text-align:center;''>完成验收</div>', 12, 1),
(26, '驳回', '<div style=''background-color: red; width:100%; text-align:center;''>驳回</div>\r\n', 13, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tk_status_project`
--

CREATE TABLE IF NOT EXISTS `tk_status_project` (
  `psid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `task_status` varchar(60) NOT NULL,
  `task_status_display` varchar(255) NOT NULL,
  `task_status_pbackup1` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`psid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `tk_status_project`
--

INSERT INTO `tk_status_project` (`psid`, `task_status`, `task_status_display`, `task_status_pbackup1`) VALUES
(2, '项目争取中', '<div style=''background-color: #996699; width:100%; text-align:center;''>项目争取中</div>', 1),
(4, '需求调研阶段', '<div style=''background-color: #9F0; width:100%; text-align:center;''>需求调研阶段</div>', 2),
(5, '设计阶段', '<div style=''background-color: #9F0; width:100%; text-align:center;''>设计阶段</div>', 3),
(6, '开发阶段', '<div style=''background-color: #9F0; width:100%; text-align:center;''>开发阶段</div>', 4),
(7, '测试阶段', '<div style=''background-color: #9F0; width:100%; text-align:center;''>测试阶段</div>', 4),
(8, '部署阶段', '<div style=''background-color: #090; width:100%; text-align:center;''>部署阶段</div>', 5),
(9, '项目已结束', '<div style=''background-color: #ccc; width:100%; text-align:center;''>项目已结束</div>', 6),
(14, '项目中断', '<div style=''background-color: red; width:100%; text-align:center;''>项目中断</div>', 7),
(22, '推迟', '<div style=''background-color: #FC0; width:100%; text-align:center;''>推迟</div>', 8),
(23, '非项目', '<div style=''background-color: #996699; width:100%; text-align:center;''>非项目</div>', 9);

-- --------------------------------------------------------

--
-- 表的结构 `tk_task`
--

CREATE TABLE IF NOT EXISTS `tk_task` (
  `TID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `csa_from_dept` mediumint(6) NOT NULL DEFAULT '0',
  `csa_from_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_to_dept` mediumint(6) NOT NULL DEFAULT '0',
  `csa_to_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_year` smallint(5) NOT NULL DEFAULT '0',
  `csa_month` tinyint(3) NOT NULL DEFAULT '0',
  `csa_project` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_project_sub` mediumint(7) NOT NULL,
  `csa_type` smallint(4) NOT NULL DEFAULT '0',
  `csa_text` varchar(80) NOT NULL,
  `csa_priority` tinyint(3) NOT NULL,
  `csa_temp` tinyint(3) NOT NULL,
  `csa_plan_st` date NOT NULL DEFAULT '0000-00-00',
  `csa_plan_et` date NOT NULL DEFAULT '0000-00-00',
  `csa_plan_hour` float(20,1) NOT NULL DEFAULT '0.0',
  `csa_remark1` text NOT NULL,
  `csa_remark2` smallint(4) NOT NULL DEFAULT '0',
  `csa_remark3` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `csa_remark4` bigint(20) NOT NULL DEFAULT '-1',
  `csa_remark5` varchar(300) NOT NULL DEFAULT '>>-1',
  `csa_remark6` bigint(20) NOT NULL DEFAULT '-1',
  `csa_remark7` varchar(60) NOT NULL,
  `csa_remark8` text,
  `test01` text,
  `test02` varchar(100) NOT NULL,
  `test03` varchar(60) NOT NULL,
  `test04` varchar(60) NOT NULL,
  `csa_create_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_last_user` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`TID`),
  KEY `touser_st_et` (`csa_to_user`,`csa_plan_st`,`csa_plan_et`),
  KEY `fruser` (`csa_from_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_task`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_task_byday`
--

CREATE TABLE IF NOT EXISTS `tk_task_byday` (
  `tbid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `csa_tb_year` varchar(20) NOT NULL,
  `csa_tb_status` smallint(4) NOT NULL DEFAULT '0',
  `csa_tb_manhour` float(20,1) NOT NULL DEFAULT '0.0',
  `csa_tb_text` text NOT NULL,
  `csa_tb_comment` smallint(5) NOT NULL DEFAULT '0',
  `csa_tb_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `csa_tb_backup1` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_tb_backup2` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_tb_backup3` bigint(20) unsigned NOT NULL DEFAULT '0',
  `csa_tb_backup4` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tbid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_task_byday`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_task_tpye`
--

CREATE TABLE IF NOT EXISTS `tk_task_tpye` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `task_tpye` varchar(60) NOT NULL,
  `tk_task_typerank` varchar(60) NOT NULL,
  `task_tpye_backup1` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `tk_task_tpye`
--

INSERT INTO `tk_task_tpye` (`id`, `task_tpye`, `tk_task_typerank`, `task_tpye_backup1`) VALUES
(1, '项目管理', '', 1),
(2, '产品设计', '', 2),
(3, '开发', '', 3),
(7, 'Bug', '', 4),
(8, '测试', '', 5),
(9, '撰写文档', '', 6),
(10, '需求调研', '', 7),
(12, '会议', '', 8),
(14, '请假', '', 9),
(15, '加班', '', 10),
(16, '其他', '', 11),
(19, '控制账户', '', 0),
(20, '子项目', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tk_team`
--

CREATE TABLE IF NOT EXISTS `tk_team` (
  `pid` bigint(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tk_team_title` text,
  `tk_team_title_en` varchar(200) DEFAULT NULL,
  `tk_team_backup1` text,
  `tk_team_backup2` text,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tk_team`
--


-- --------------------------------------------------------

--
-- 表的结构 `tk_user`
--

CREATE TABLE IF NOT EXISTS `tk_user` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tk_user_login` varchar(60) NOT NULL DEFAULT '',
  `tk_user_pass` varchar(64) NOT NULL DEFAULT '',
  `tk_user_token` varchar(60) NOT NULL DEFAULT '0',
  `tk_display_name` varchar(50) NOT NULL DEFAULT '',
  `pid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tk_user_status` varchar(60) NOT NULL DEFAULT '',
  `tk_user_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tk_user_remark` text NOT NULL,
  `tk_user_rank` tinyint(2) NOT NULL DEFAULT '0',
  `tk_user_contact` varchar(50) NOT NULL DEFAULT '',
  `tk_user_email` varchar(100) NOT NULL DEFAULT '',
  `tk_user_message` bigint(20) NOT NULL DEFAULT '0',
  `tk_user_lastuse` text,
  `tk_user_backup1` varchar(60) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tk_user`
--

INSERT INTO `tk_user` (`uid`, `tk_user_login`, `tk_user_pass`, `tk_user_token`, `tk_display_name`, `pid`, `tk_user_status`, `tk_user_registered`, `tk_user_remark`, `tk_user_rank`, `tk_user_contact`, `tk_user_email`, `tk_user_message`, `tk_user_lastuse`, `tk_user_backup1`) VALUES
(1, 'admin', 'a6ec5a7b854d204b74cd90a8306a957e', '0', 'Admin', 1, '管理员', '2012-11-08 08:12:00', '', 5, '', '', 0, NULL, '');
