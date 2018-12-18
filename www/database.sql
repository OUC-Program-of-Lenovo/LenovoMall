-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-12-18 01:48:05
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lenovo`
--

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `time` varchar(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `price` float NOT NULL,
  `model` varchar(32) NOT NULL,
  `amount` int(11) NOT NULL,
  `surplus` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `avatar` varchar(32) NOT NULL,
  `album` varchar(64) NOT NULL,
  `size` varchar(16) NOT NULL,
  `description` text NOT NULL,
  `add_time` varchar(10) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL,
  `keywords` varchar(64) NOT NULL,
  `brief_des` varchar(256) NOT NULL,
  `remark` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `rcv_address` varchar(64) NOT NULL,
  `rcv_name` varchar(16) NOT NULL,
  `rcv_phone` varchar(11) NOT NULL,
  `snd_address` varchar(64) NOT NULL,
  `snd_name` varchar(16) NOT NULL,
  `snd_phone` varchar(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `time` varchar(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(16) NOT NULL,
  `passsword` varchar(32) NOT NULL,
  `salt` varchar(5) NOT NULL,
  `user_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `phone` varchar(11) NOT NULL,
  `avatar` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `regist_time` varchar(10) NOT NULL,
  `regist_ip` varchar(15) NOT NULL,
  `actived` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `active_code` varchar(32) NOT NULL,
  `active_time` varchar(10) NOT NULL,
  `login_time` varchar(10) NOT NULL,
  `login_ip` varchar(15) NOT NULL,
  `shopping_cart` varchar(40) DEFAULT NULL,
  `collection` varchar(100) DEFAULT NULL,
  `rcv_address` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`,`number`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
