CREATE DATABASE `hackcess` /*!40100 DEFAULT CHARACTER SET latin1 */;

CREATE TABLE `journey` (
  `journey_id` int(11) NOT NULL auto_increment,
  `player_id` varchar(45) default NULL,
  PRIMARY KEY  (`journey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `grid` (
  `grid_id` int(11) NOT NULL auto_increment,
  `grid_x` int(11) default NULL,
  `grid_y` int(11) default NULL,
  `directions` varchar(4) default NULL,
  `journey_id` int(11) default NULL,
  PRIMARY KEY  (`grid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `features` (
  `feature_id` int(11) NOT NULL auto_increment,
  `grid_id` int(11) default NULL,
  `feature_details` varchar(300) default NULL,
  PRIMARY KEY  (`feature_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `journal` (
  `journal_id` int(11) NOT NULL auto_increment,
  `character_id` int(11) default NULL,
  `journey_id` int(11) default NULL,
  `grid_id` int(11) default NULL,
  `journal_details` varchar(300) default NULL,
  PRIMARY KEY  (`journal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `character` (
  `character_id` int(11) NOT NULL auto_increment,
  `player_id` int(11) default NULL,
  `character_name` varchar(45) default NULL,
  `character_role` varchar(45) default NULL,
  `character_xp` int(11) default NULL,
  `character_grid_id` int(11) default NULL,
  `current_journey_id` int(11) default NULL,
  PRIMARY KEY  (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` varchar(45) default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
