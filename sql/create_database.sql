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
  `character_level` int(11) default NULL,
  `character_grid_id` int(11) default NULL,
  `current_journey_id` int(11) default NULL,
  `status` varchar(45) default NULL COMMENT 'Alive | Dead | Retired',
  PRIMARY KEY  (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` varchar(45) default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `character_details` (
  `details_id` int(11) NOT NULL auto_increment,
  `character_id` int(11) default NULL,
  `hp` int(11) default NULL,
  `attack` int(11) default NULL,
  `armor_class` int(11) default NULL,
  `gold` int(11) default NULL,
  `xp` int(11) default NULL,
  `strength` int(11) default NULL,
  `head_slot` int(11) default NULL,
  `chest_slot` int(11) default NULL,
  `legs_slot` int(11) default NULL,
  `shield_slot` int(11) default NULL,
  `weapon_slot` int(11) default NULL,
  PRIMARY KEY  (`details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `character_equipment` (
  `equipment_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `ac_boost` int(11) default NULL,
  `attack_boost` int(11) default NULL,
  `weight` int(11) default NULL,
  `slot` varchar(45) default NULL,
  `character_id` int(11) default NULL,
  PRIMARY KEY  (`equipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `enemy` (
  `enemy_id` int(11) NOT NULL auto_increment,
  `enemy_name` varchar(100) default NULL,
  `player_id` int(11) default NULL,
  `character_id` int(11) default NULL,
  `grid_id` int(11) default NULL,
  `atk` int(11) default NULL,
  `ac` int(11) default NULL,
  `hp` int(11) default NULL,
  `status` varchar(45) default NULL,
  `killed_by` int(11) default NULL,
  PRIMARY KEY  (`enemy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
