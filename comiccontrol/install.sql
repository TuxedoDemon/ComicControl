SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `cc_temp_blogs` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `blog` int(12) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `publishtime` int(16) NOT NULL,
  `commentid` varchar(256) DEFAULT NULL,
  `slug` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_blogs_tags` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `blog` varchar(256) NOT NULL,
  `blogid` int(12) NOT NULL,
  `tag` varchar(256) NOT NULL,
  `publishtime` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_comics` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `comic` int(12) NOT NULL,
  `comichighres` varchar(256) NOT NULL,
  `comicthumb` varchar(256) NOT NULL,
  `imgname` varchar(256) NOT NULL,
  `publishtime` int(16) NOT NULL,
  `title` varchar(256) NOT NULL,
  `newstitle` varchar(256) DEFAULT NULL,
  `newscontent` text,
  `transcript` text,
  `storyline` int(8) NOT NULL,
  `commentid` varchar(256) DEFAULT NULL,
  `hovertext` varchar(512) DEFAULT NULL,
  `slug` varchar(256) NOT NULL,
  `width` int(8) NOT NULL,
  `height` int(8) NOT NULL,
  `mime` varchar(128) NOT NULL,
  `contentwarning` text,
  `altnext` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_comics_storyline` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `sorder` int(8) NOT NULL,
  `comic` int(12) NOT NULL,
  `parent` int(12) NOT NULL,
  `level` int(8) NOT NULL,
  `caption` varchar(5120) DEFAULT NULL,
  `thumbnail` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_comics_tags` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `comic` varchar(256) NOT NULL,
  `comicid` int(12) NOT NULL,
  `tag` varchar(256) NOT NULL,
  `publishtime` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_galleries` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `gallery` int(12) NOT NULL,
  `imgname` varchar(256) NOT NULL,
  `thumbname` varchar(256) NOT NULL,
  `caption` text NOT NULL,
  `porder` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_htaccess` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_images` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `imgname` varchar(256) NOT NULL,
  `thumbname` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_languages` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(16) NOT NULL,
  `language` varchar(32) NOT NULL,
  `scope` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

INSERT INTO `cc_temp_languages` (`id`, `shortname`, `language`, `scope`) VALUES
(1, 'en', 'English', 'admin'),
(2, 'en', 'English', 'user');

CREATE TABLE IF NOT EXISTS `cc_temp_modules` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `moduletype` varchar(256) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `language` varchar(128) NOT NULL,
  `template` varchar(128) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_modules_options` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `moduleid` int(8) NOT NULL,
  `optionname` varchar(128) NOT NULL,
  `value` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_options` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `optionname` varchar(64) NOT NULL,
  `optionvalue` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_plugins` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `filepath` varchar(256) NOT NULL,
  `slug` varchar(256) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_sessions` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL,
  `loginhash` varchar(64) NOT NULL,
  `loginexpire` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_text` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `salt` varchar(16) NOT NULL,
  `resethash` varchar(32) DEFAULT NULL,
  `resetsalt` varchar(16) DEFAULT NULL,
  `authlevel` int(2) NOT NULL,
  `avatar` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cc_temp_users_permissions` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) DEFAULT NULL,
  `moduleid` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

COMMIT;

INSERT INTO `cc_temp_htaccess` (`id`, `content`) VALUES
(1, ''),
(2, ''),
(3, '# disable directory browsing\r\nOptions -Indexes\r\n\r\n# Begin ComicControl mod rewrite\r\n<IfModule mod_rewrite.c>\r\nRewriteEngine On\r\nRewriteRule ^index\\.php$ - [L]\r\nRewriteCond %{REQUEST_FILENAME} !-f\r\nRewriteCond %{REQUEST_FILENAME} !-d\r\n</IfModule>\r\nRewriteRule . index.php [L]');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
