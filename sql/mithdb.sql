-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 23, 2009 at 07:21 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `mith`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL COMMENT 'Per game unique comment id',
  `game_id` bigint(20) unsigned NOT NULL COMMENT 'Belongs to what game',
  `round` int(10) unsigned NOT NULL COMMENT 'Belongs to what round in what game',
  `uid` bigint(20) unsigned NOT NULL COMMENT 'Author, 0 if by us',
  `type` tinyint(1) unsigned NOT NULL COMMENT '0 -> city, 1 -> mafia',
  `text` text NOT NULL COMMENT 'The comment text',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time created',
  PRIMARY KEY (`comment_id`,`game_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='List of all comments';

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `game_id`, `round`, `uid`, `type`, `text`, `timestamp`) VALUES
(1, 5, 1, 101, 1, 'abc', '2009-12-23 18:16:09'),
(1, 5, 1, 102, 0, 'def', '2009-12-23 18:16:09'),
(2, 5, 1, 103, 0, 'xyz', '2009-12-23 18:16:10'),
(2, 5, 1, 101, 1, 'abc', '2009-12-23 18:18:11'),
(3, 5, 1, 102, 0, 'def', '2009-12-23 18:18:11'),
(4, 5, 1, 103, 0, 'xyz', '2009-12-23 18:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `game_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto generated game_id',
  `admin_id` bigint(20) unsigned NOT NULL COMMENT 'Game administrator',
  `game_state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 ->  created, 1 -> started, 2 -> over',
  `curr_round` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Current round number of the game',
  `comment_num_city` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last City comment_id',
  `comment_num_mafia` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Last mafia comment_id',
  `game_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'For future use',
  PRIMARY KEY (`game_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Database of all current and past games' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`game_id`, `admin_id`, `game_state`, `curr_round`, `comment_num_city`, `comment_num_mafia`, `game_type`) VALUES
(5, 100, 1, 1, 4, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `uid` bigint(20) unsigned NOT NULL COMMENT 'Player in a game',
  `game_id` bigint(20) unsigned NOT NULL COMMENT 'What game',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 -> invited, 1 -> alive, 2 -> dead',
  `role` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 -> none, 1 -> god, 2 -> mafia, 3 -> civilian, 4 -> doctor, 5 -> inspector',
  PRIMARY KEY (`uid`,`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Database of the all the past and present players';

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`uid`, `game_id`, `state`, `role`) VALUES
(104, 5, 1, 5),
(103, 5, 1, 4),
(102, 5, 1, 3),
(101, 5, 1, 2),
(100, 5, 1, 1);
