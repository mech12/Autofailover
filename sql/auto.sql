-- phpMyAdmin SQL Dump
-- version 4.3.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- 생성 시간: 15-05-17 08:45
-- 서버 버전: 5.5.42
-- PHP 버전: 5.4.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 데이터베이스: `auto`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `uid` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `pwd` varchar(128) NOT NULL,
  `type` varchar(64) NOT NULL,
  `server1` varchar(64) NOT NULL,
  `server2` varchar(64) NOT NULL,
  `vip1` varchar(64) NOT NULL,
  `vip2` varchar(64) NOT NULL,
  `db` varchar(64) NOT NULL,
  `app` varchar(64) NOT NULL,
  `disk1` varchar(64) NOT NULL,
  `disk2` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
