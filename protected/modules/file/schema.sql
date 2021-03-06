-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 05 月 27 日 08:36
-- 服务器版本: 5.5.8-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `books`
--

-- --------------------------------------------------------

--
-- 表的结构 `file_ext`
--

CREATE TABLE IF NOT EXISTS `file_ext` (
  `width` int(11) NOT NULL AUTO_INCREMENT,
  `height` int(11) NOT NULL,
  `make` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  `datetime` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  PRIMARY KEY (`width`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `file_init`
--

CREATE TABLE IF NOT EXISTS `file_init` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `path` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '默认是管理员',
  `uniqid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
