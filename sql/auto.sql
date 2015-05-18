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
CREATE TABLE `tbl_user` (
	`email` VARCHAR(128) NOT NULL,
	`type` VARCHAR(64) NOT NULL DEFAULT 'Shared',
	`server1` VARCHAR(64) NOT NULL,
	`server2` VARCHAR(64) NOT NULL,
	`vip1` VARCHAR(64) NOT NULL,
	`vip2` VARCHAR(64) NOT NULL,
	`db_type` VARCHAR(64) NOT NULL DEFAULT 'ORACLE',
	`db` VARCHAR(64) NOT NULL DEFAULT 'SYSTEM a localhost:1521',
	`app` VARCHAR(64) NOT NULL,
	`disk1` VARCHAR(64) NOT NULL,
	`disk2` VARCHAR(64) NOT NULL
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
