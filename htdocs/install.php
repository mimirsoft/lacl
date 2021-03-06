<?php


/*
 *
This file is part of Mimirsoft Lightweight ACL System
Copyright (C) 2011, Kevin Milhoan, Mimir Software Corporation

Mimirsoft Lightweight ACL System is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Mimirsoft Lightweight ACL System is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Mimirsoft Lightweight ACL System.  If not, see <http://www.gnu.org/licenses/>.

Contact MimirSoft at mimirsoft@gmail.com or www.mimirsoft.com

*
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * 
 * 
 * Below is the basic set of tables to function,
 * The Lightweight ACL only implements a set of priviges (actions)
 * It does not define a set of objects those actions can be taken upon
 * I reccomend you look at the OpenMobas RBAC for a complete ACL implmentation
 * 
 * This file should be run once, to setup the system, then deleted from the installation
 *
 * The following commands should be run in the mysql terminal to create the user and database first
 * replace the username and password with the values you wish to use.
 *
 *  create database lacl;
 *  create user 'lacluser'@'localhost' identified by 'password123';
 *  grant all on lacl.* to 'lacluser'@'localhost';
 *  FLUSH PRIVILEGES;
 */

require_once("../globalconstants.php");



$mysqli = new mysqli($DB_SETTINGS['dbhost'], $DB_SETTINGS['user'], $DB_SETTINGS['pass'], $DB_SETTINGS['dbname']);

$setup = "
		

CREATE TABLE `users_main` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `join_app` varchar(60) NOT NULL,
  `displayname` varchar(130) NOT NULL,
  `firstname` varchar(65) NOT NULL,
  `lastname` varchar(65) NOT NULL,
  `created_at` datetime NOT NULL,
  `hashed_password` varchar(240) NOT NULL,
  `external_id` varchar(30) NOT NULL,
  `external_link` varchar(100) NOT NULL,
  `external_source` varchar(80) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;


-- 
-- Table structure for table `temp_members_db`
-- 

DROP TABLE IF EXISTS `temp_members`;
CREATE TABLE `temp_members` (
  `confirm_code` varchar(65) NOT NULL default '',
  `username` varchar(60) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `hashed_password` varchar(240) NOT NULL,
  `lastname` varchar(65) NOT NULL default '',
  `firstname` varchar(65) NOT NULL default '',
  `join_app` varchar(60) NOT NULL default '',
  `displayname` varchar(130) NOT NULL,
  `external_id` varchar(30) NOT NULL,
  `external_link` varchar(100) NOT NULL,
  `external_source` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `temp_members_db`
-- 



CREATE TABLE `actions_main` (
  `action_id` int(11) NOT NULL auto_increment,
  `action_name` varchar(40) NOT NULL,
  PRIMARY KEY  (`action_id`),
  UNIQUE KEY `action_name` (`action_name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;




-- --------------------------------------------------------

-- 
-- Table structure for table `geocoder`
-- 

CREATE TABLE `geocoder` (
  `address` varchar(120) NOT NULL,
  `latitude` decimal(8,5) NOT NULL default '0.00000',
  `longitude` decimal(8,5) NOT NULL default '0.00000',
  `geo_precision` int(11) NOT NULL default '0',
  UNIQUE KEY `address` (`address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

-- 
-- Table structure for table `roles_has_actions`
-- 

CREATE TABLE `roles_has_actions` (
  `role_mtm_action_id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY  (`role_mtm_action_id`),
  UNIQUE KEY `privilege` (`role_id`,`action_id`),
  KEY `action_id` (`action_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;



-- --------------------------------------------------------

-- 
-- Table structure for table `roles_main`
-- 

CREATE TABLE `roles_main` (
  `role_id` int(11) NOT NULL auto_increment,
  `role_name` varchar(40) NOT NULL,
  PRIMARY KEY  (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;




-- --------------------------------------------------------

-- 
-- Table structure for table `session_variable`
-- 

CREATE TABLE `session_variable` (
  `id` int(11) NOT NULL auto_increment,
  `session_id` int(11) default NULL,
  `variable_name` varchar(64) default NULL,
  `variable_value` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `session_variable`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user_session`
-- 

CREATE TABLE `user_session` (
  `session_id` int(11) NOT NULL auto_increment,
  `ascii_session_id` varchar(32) default NULL,
  `logged_in` enum('Y','N') NOT NULL default 'N',
  `user_id` int(11) default NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_impression` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `user_agent` varchar(255) default NULL,
  `session_data` mediumtext,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8763 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8763 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `users_has_roles`
-- 

CREATE TABLE `users_has_roles` (
  `user_mtm_role_id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_mtm_role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;


		
";



'INSERT INTO `users_main` (`user_id`, `username`, `email`, `firstname`, `lastname`, `created_at`, `hashed_password`) VALUES
(1, \'Caoster\', \'caos7er@yahoo.com\', \'\', \'\', \'0000-00-00 00:00:00\', \'$2y$11$nZWXpmJ35Tf28GUla0Fl9ujuDVqBYPokPrKTvRQDjtCw4ZARJ5d9W\')'
;

'SETUP INITIAL ACL permissions';
		

$stmt = $mysqli->multi_query($setup);

echo "DATABASE SETUP! NOW DELETE THIS FILE FROM SERVER";

?>