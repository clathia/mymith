/* 
SQLyog v3.11
Host - localhost : Database - comments
**************************************************************
Server version 4.0.14-nt
*/

create database if not exists `comments`;

use `comments`;

/*
Table struture for comments
*/

drop table if exists `comments`;
CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL auto_increment,
  `FullName` varchar(255) default NULL,
  `Email` varchar(255) default NULL,
  `Website` varchar(255) default NULL,
  `CommentText` text,
  `ParentCommentID` int(11) default '0',
  `CommentSubject` varchar(255) default NULL,
  `PublishDate` int(11) default NULL,
  `ForeignID` varchar(255) default NULL,
  PRIMARY KEY  (`CommentID`)
) TYPE=MyISAM;

