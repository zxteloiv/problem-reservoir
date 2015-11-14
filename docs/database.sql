-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2015 at 03:40 PM
-- Server version: 5.6.27
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prs`
--
CREATE DATABASE IF NOT EXISTS `prs` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prs`;

-- --------------------------------------------------------

--
-- Table structure for table `img_attachment`
--

CREATE TABLE `img_attachment` (
  `img_id` int(10) UNSIGNED NOT NULL COMMENT '图片id',
  `img_name` varchar(50) NOT NULL DEFAULT '''''' COMMENT '图片名',
  `img_type` varchar(25) NOT NULL DEFAULT '''''' COMMENT '图片类型(MIME)',
  `img_size` varchar(20) NOT NULL DEFAULT '''''' COMMENT '图片大小',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '文件提交时间',
  `img` mediumblob NOT NULL COMMENT '图片内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `problem_id` int(10) UNSIGNED NOT NULL,
  `course` varchar(50) NOT NULL DEFAULT '''''' COMMENT '所属课程',
  `chapter` varchar(50) NOT NULL DEFAULT '''''' COMMENT '所属章节',
  `points` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分值，单位为0.1分',
  `difficulty` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '题目难度',
  `description` varchar(255) NOT NULL DEFAULT '''''' COMMENT '其它描述',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modify_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  `content` longtext NOT NULL COMMENT '内容，json串，list of primitives',
  `keypoints` varchar(255) NOT NULL DEFAULT '''''' COMMENT '知识点',
  `states` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '问题状态, 0:正常, 1:图片附件失效, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='题库表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `img_attachment`
--
ALTER TABLE `img_attachment`
  ADD PRIMARY KEY (`img_id`);

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`problem_id`),
  ADD KEY `modify_time` (`modify_time`),
  ADD KEY `course` (`course`),
  ADD KEY `chapter` (`chapter`),
  ADD KEY `keypoints` (`keypoints`),
  ADD KEY `course_2` (`course`,`chapter`,`points`),
  ADD KEY `course_3` (`course`,`chapter`,`difficulty`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `img_attachment`
--
ALTER TABLE `img_attachment`
  MODIFY `img_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图片id';
--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `problem_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
