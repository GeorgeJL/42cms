<?php
$sql=array();

$sql[]=array('desc'=>"addons table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."addons` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `addeddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `addedbyid` smallint(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `addonid` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");

$sql[]=array('desc'=>"addons data", 'data'=>"INSERT INTO `".$dbprefix."addons` (`id`, `name`, `description`, `addeddate`, `addedbyid`) VALUES
(1, 'demo1', 'Just demo. This addon will overwrite in page text localhost with <b>localhost</b>', '2013-02-28 09:42:32', 1);");

$sql[]=array('desc'=>"chunks table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."chunks` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `creator` text COLLATE utf8_unicode_ci NOT NULL,
  `lasteditor` text COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$sql[]=array('desc'=>"chunks data", 'data'=>"INSERT INTO `".$dbprefix."chunks` (`name`, `description`, `body`, `added`, `edited`, `creator`, `lasteditor`, `active`) VALUES
('weburl', 'Url of this web site (of the homepage)', '".$weburl."', '0000-00-00 00:00:00', '2013-02-25 17:36:43', 'system', '', '1'),
('footer', 'This is custom inserted chunk. This will be used as [{footer}] but it needs to be activated before', '<div id=\"footer\" style=\"position: relative; border: 1px solid blue\">Footer lorem ipsum dolor sit ammet</div>', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'system', '', '0');");

$sql[]=array('desc'=>"galleries table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."galleries` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `url_part` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `addedby` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `addedbyid` smallint(5) unsigned NOT NULL,
  `addeddate` datetime NOT NULL,
  `active` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'Yes',
  `done` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;");

$sql[]=array('desc'=>"galleries data", 'data'=>"INSERT INTO `".$dbprefix."galleries` (`id`, `name`, `url_part`, `description`, `addedby`, `addedbyid`, `addeddate`, `active`, `done`) VALUES
(1, 'Test gallery', '', 'This is a test gallery.', 'system', 1, '2013-02-22 12:43:23', 'Yes', 0);");

$sql[]=array('desc'=>"images table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."images` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `galleryid` smallint(5) unsigned NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `addedby` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `addedbyid` smallint(5) NOT NULL COMMENT 'Id of person who added picture. In case he will change username, he can be still found by user id',
  `addeddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;");

$sql[]=array('desc'=>"images data", 'data'=>"INSERT INTO `".$dbprefix."images` (`id`, `galleryid`, `file`, `author`, `addedby`, `addedbyid`, `addeddate`, `title`, `description`) VALUES
(1, 1, '2b.jpg', '', 'system', 1, '2013-02-25 17:28:46', '2b', 'Two bees'),
(2, 1, 'blue-tape.jpeg', '', 'system', 1, '2013-02-25 17:28:46', 'blue-tape', 'Blue tape'),
(3, 1, 'bold-italic.jpg', 'Usain Bold', 'system', 1, '2013-02-25 17:28:46', 'Bold vs. Italic', ''),
(4, 1, 'ff-addon.jpg', 'Mozila', 'system', 1, '2013-02-25 17:28:46', 'Firefox add-on', 'New Firefox add-on');");

$sql[]=array('desc'=>"invitations table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."invitations` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `groups` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '{\"1\":\"0\"}' COMMENT 'Default usergroup is 1-newly registered users',
  `pages` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(100) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `addedby` smallint(5) NOT NULL,
  `addedtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('Active','Inactive','Used') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$sql[]=array('desc'=>"login_attempts table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."login_attempts` (
  `ip` int(10) unsigned NOT NULL,
  `attempt` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$sql[]=array('desc'=>"pages table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."pages` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `subdomain` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `url_part` text COLLATE utf8_unicode_ci NOT NULL,
  `level` tinyint(3) NOT NULL DEFAULT '0',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `inmenu` enum('logged','nologged','both','non') CHARACTER SET utf8 NOT NULL DEFAULT 'both',
  `menutitle` text COLLATE utf8_unicode_ci NOT NULL,
  `membersonly` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  `loadlang` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  `h1` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `template` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `addons` text COLLATE utf8_unicode_ci NOT NULL,
  `menuorder` int(3) NOT NULL DEFAULT '0',
  `active` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  `lastupdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=102 ;");

$sql[]=array('desc'=>"pages data", 'data'=>"INSERT INTO `".$dbprefix."pages` (`id`, `subdomain`, `url`, `url_part`, `level`, `title`, `inmenu`, `menutitle`, `membersonly`, `loadlang`, `h1`, `text`, `template`, `addons`, `menuorder`, `active`, `lastupdate`) VALUES
(1, '', '404', '404', 1, 'Error 404', 'non', '', 'No', 'No', 'Error 404', '<p>This is ERROR 404</p>', 10, '', 0, 'Yes', '2013-02-22 12:18:36'),
(2, '', 'members/templatedemo', 'templatedemo', 2, 'Template demo', 'logged', 'Template demo', 'Yes', 'No', 'Template demo', 'Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<h2>This is H2</h2>\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<h3>This is H3</h3>\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<h4>This is H4</h4>\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<h5>This is H5</h5>\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<h6>This is H6</h6>\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<br><b>This is bold text</b><br>\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\n\r\n<br>\r\n<form>\r\n<fieldset>\r\n<legend>This is legend</legend> \r\n<label for=\"text\">Some text</label><input type=\"text\" id=\"text\"><br>\r\n<label for=\"password\">This is password</label><input type=\"password\" id=\"password\"><br>\r\n<label for=\"radio\">Radio:</label><input type=\"radio\" id=\"radio\"><br>\r\n<label for=\"checkbox\">Checkbox</label><input type=\"checkbox\" id=\"checkbox\"><br>\r\n<label for=\"select\"></label><select id=\"select\"><option value=\"Option 1\"><option value=\"Option 1\"><option value=\"Option 1\"></select><br>\r\n<label for=\"submit\"></label><input type=\"submit\" id=\"submit\"><br>\r\n</fieldset>\r\n</form><br>\r\n\r\n\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.\r\nLorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua. Lorem ipsum dolor sit , consectetur adipisicing elit, sed do eiusmod tempor ud labore et dolore magna aliqua.', 10, '', 10000, 'Yes', '2013-02-22 12:16:47'),
(3, '', 'members/update', 'update', 2, 'Update 42cms', 'logged', 'Update 42cms', 'Yes', 'Yes', 'Update 42cms', '[(update/update)]', 3, '', 990, 'Yes', '2013-10-20 18:03:42'),
(11, '', 'members', 'members', 1, 'Members panel', 'both', 'Members', 'Yes', 'Yes', 'Members panel', '<p>[(menu?showpart=members)]</p>', 3, '', 3, 'Yes', '2013-02-22 12:30:00'),
(12, '', 'members/invitations', 'invitations', 2, 'Invite new members', 'logged', 'Invitations', 'Yes', 'Yes', 'Invitations', '<p>[(members/invitations)]</p>', 4, '', 50, 'Yes', '2013-02-22 13:47:08'),
(13, '', 'register', 'register', 1, 'Register', 'nologged', 'Register', 'No', 'Yes', 'Register please', '<p>[(members/register)]</p>', 3, '', 0, 'Yes', '2013-02-22 13:15:11'),
(14, '', 'members/activate', 'activate', 2, 'Account activation', 'non', 'Account activation', 'No', 'Yes', 'Account activation', '[(members/activation)]', 3, '', 0, 'Yes', '2013-02-22 12:16:47'),
(15, '', 'members/lostpass', 'lostpass', 2, 'Lost password', 'non', 'Lost Pass', 'No', 'Yes', 'Lost password', '[(members/lostpass)]', 3, '', 0, 'Yes', '2013-02-22 12:16:47'),
(16, '', 'members/changepass', 'changepass', 2, 'Change password', 'logged', 'Change password', 'Yes', 'Yes', 'Change password', '<p>[(members/changepass)]</p>', 3, '', 900, 'Yes', '2013-02-28 15:01:15'),
(19, '', 'members/logout', 'logout', 2, 'LogOut', 'logged', 'LogOut', 'No', 'Yes', 'LogOut', '<p>[(members/logout)]</p>', 3, '', 999, 'Yes', '2013-02-22 13:47:39'),
(20, '', 'members/addpage', 'addpage', 2, 'Add new page', 'logged', 'Add new page', 'Yes', 'Yes', 'Add new page', '<p>[(add_page)]</p>', 4, '', 10, 'Yes', '2013-02-22 13:46:05'),
(21, '', 'members/addpage/load', 'load', 3, 'This page will be loaded just by system', 'non', 'Not in menu', 'Yes', 'Yes', '', '[(add_page?load=1)]', 1, '', 0, 'Yes', '2012-12-19 03:08:27'),
(22, '', 'members/edit', 'edit', 2, 'Edit page', 'logged', 'Edit page', 'Yes', 'Yes', 'Edit page', '<p>[(edit_article)]</p>', 4, '', 20, 'Yes', '2013-02-22 13:46:13'),
(23, '', 'members/edit/load', 'load', 3, 'This page will be loaded just by system', 'non', 'Not in menu', 'Yes', 'Yes', '', '<p>[(edit_article)]</p>', 1, '', 0, 'Yes', '2012-12-09 01:49:16'),
(24, '', 'members/edit/upload', 'upload', 3, '', 'non', '', 'Yes', 'No', '', '[(plupload/serverside?caller=edit_article)]', 1, '', 0, 'Yes', '2013-01-03 03:18:06'),
(25, '', 'members/addimages', 'addimages', 2, 'Add new images into gallery', 'logged', 'Add images', 'Yes', 'No', 'Add new images into gallery', '<p>[(add_images)]</p>', 3, '', 40, 'Yes', '2013-02-25 17:25:13'),
(26, '', 'members/addimages/upload', 'upload', 3, '', 'non', '', 'Yes', 'No', '', '<p>[(plupload/serverside?caller=add_images)]</p>', 1, '', 999, 'Yes', '2013-02-09 00:19:41'),
(27, '', 'members/addgallery', 'addgallery', 2, 'Add new gallery', 'logged', 'Add gallery', 'Yes', 'No', 'Add new gallery', '<p>[(add_gallery)]</p>', 3, '', 30, 'Yes', '2013-02-22 13:46:28'),
(28, '', 'members/dbinfo', 'dbinfo', 2, 'Show database', 'logged', 'Show database', 'Yes', 'No', 'Show database', '<p>[(view_database)]</p>', 3, '', 900, 'Yes', '2013-02-22 12:31:28'),
(29, '', 'members/info', 'info', 2, 'Debugging info', 'logged', 'Debugging info', 'Yes', 'No', 'Debugging info', '<p>[(members/info)]</p>', 3, '', 910, 'Yes', '2013-02-22 12:32:07'),
(40, '', 'members/permissions', 'permissions', 2, 'Permissions', 'logged', 'Permissions', 'Yes', 'No', 'Permissions', '<p>[(members/permissions)]</p>', 4, '', 0, 'Yes', '2013-06-03 15:04:15'),
(41, '', 'members/permissions/load', 'load', 3, '', 'non', '', 'Yes', 'Yes', '', '<p>[(members/permissions?load=true)]</p>', 1, '', 0, 'Yes', '2013-06-03 15:13:47'),
(100, '', '', '', 0, 'HomePage', 'both', 'Homepage', 'No', 'No', 'Homepage', '<p>This is default home page of 42cms</p>\r\n<p> </p>\r\n<p>Visit <a href=\"http://www.42cms.com\" target=\"_blank\">www.42cms.com</a> for more info.</p>', 10, '', 0, 'Yes', '2013-02-25 17:33:50'),
(101, '', 'gallery', 'gallery', 1, 'Gallery', 'both', 'Gallery', 'No', 'Yes', 'Gallery', '<p>[(gallery?id=1)]</p>', 10, '', 1, 'Yes', '2013-02-25 17:45:30');");

$sql[]=array('desc'=>"passresset table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."passreset` (
  `userid` smallint(5) unsigned NOT NULL,
  `token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `successful` enum('New','Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'New',
  `uniqueid` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `uniqueid` (`uniqueid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$sql[]=array('desc'=>"permissions table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."permissions` (
  `userid` smallint(5) unsigned DEFAULT '0',
  `groupid` smallint(8) unsigned NOT NULL DEFAULT '0',
  `permission` tinyint(4) NOT NULL DEFAULT '0',
  `parameters` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

$sql[]=array('desc'=>"permissions data", 'data'=>"INSERT INTO `".$dbprefix."permissions` (`userid`, `groupid`, `permission`, `parameters`) VALUES
(0, 500, 3, '0'),
(0, 0, 11, '0'),
(0, 300, 12, '200'),
(0, 400, 12, '65530'),
(0, 500, 12, '65535'),
(0, 100, 16, '0'),
(0, 0, 19, '0'),
(0, 300, 20, '0'),
(0, 300, 21, '0'),
(0, 200, 22, '0'),
(0, 200, 23, '0'),
(0, 200, 24, '0'),
(0, 200, 25, '0'),
(0, 200, 26, '0'),
(0, 300, 27, '0'),
(0, 400, 28, '0'),
(0, 500, 29, '0'),
(0, 300, 40, '299'),
(0, 400, 40, '65530'),
(0, 500, 40, '65535'),
(0, 300, 41, '0'),
(0, 400, 41, '0'),
(0, 500, 41, '0');");

$sql[]=array('desc'=>"templates table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."templates` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;");

$sql[]=array('desc'=>"templates data", 'data'=>"INSERT INTO `".$dbprefix."templates` (`id`, `name`) VALUES
(1, 'insert just page text - not even doctype or <html> tags just [[body]]'),
(2, 'No template (just default doctype and placeholders)'),
(3, 'Members area'),
(4, 'Members area with tree'),
(10, 'Default template');");

$sql[]=array('desc'=>"usergroups table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."usergroups` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `displayname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `rank` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Admins can not grant higher priviledges to invited users as their own priviledges',
  `active` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=501 ;");

$sql[]=array('desc'=>"usergroups data", 'data'=>"INSERT INTO `".$dbprefix."usergroups` (`id`, `name`, `displayname`, `description`, `rank`, `active`) VALUES
(100, 'user', '', 'Registered user', 30, 'Yes'),
(200, 'publisher', '(Publisher)', 'Access to some publishing functions', 150, 'Yes'),
(300, 'admin', '(Admin)', 'One of the admins. Access to most of the functions', 200, 'Yes'),
(400, 'superadmin', '(Superadmin)', 'Access to everything except highly technical functions created for developers.', 65530, 'Yes'),
(500, 'owner', '(Owner)', 'Access to everything. The highest possible rank.', 65535, 'Yes');");

$sql[]=array('desc'=>"users table", 'data'=>"CREATE TABLE IF NOT EXISTS `".$dbprefix."users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(22) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `usergroups` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '100' COMMENT 'Default registred usergroup is set to 1 to be able to add some privileges all newly registered users',
  `lang` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `cookieid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Random ID for current cookie and session. Can be used to remotely log out user-just change it and user must re-log in.',
  `registred` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `activated` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;");

$sql[]=array('desc'=>"users data", 'data'=>"INSERT INTO `".$dbprefix."users` (`id`, `username`, `pass`, `salt`, `mail`, `usergroups`, `lang`, `cookieid`, `registred`, `lastupdate`, `activated`) VALUES
(1, 'system', '0', '0', '0', '0', 'en', 0, '0000-00-00 00:00:00', '2013-02-22 12:51:58', 'No'),
(2, 'admin', '$2y$09$6lCq9h/7i0h39.620W0I/Odv2NA4jxjlcxNnK6E9IU.gRa8MubavS', 'HcgvJb.OmO4pJ/2v/.je96', 'admin@example.com', '100,200,300,400,500', 'en', 8174, '0000-00-00 00:00:00', '2013-02-28 14:13:42', 'Yes');");




?>