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
  `generation` int(11) default NULL,
  `parent_id` int(11) default NULL,
  PRIMARY KEY  (`character_id`),
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
  `current_hp` int(11) default NULL,
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

CREATE TABLE `fight` (
  `fight_id` int(11) NOT NULL auto_increment,
  `character_id` int(11) default NULL,
  `enemy_id` int(11) default NULL,
  `grid_id` int(11) default NULL,
  `rounds` int(11) default NULL,
  `winner` int(11) default NULL,
  `journey_id` int(11) default NULL,
  PRIMARY KEY  (`fight_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL auto_increment,
  `store_name` varchar(45) default NULL,
  `grid_id` int(11) default NULL,
  `journey_id` int(11) default NULL,
  `character_id` int(11) default NULL,
  PRIMARY KEY  (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `store_contents` (
  `contents_id` int(11) NOT NULL auto_increment,
  `store_id` int(11) default NULL,
  `item_name` varchar(45) default NULL,
  `item_ac_boost` int(11) default NULL,
  `item_attack_boost` int(11) default NULL,
  `item_weight` int(11) default NULL,
  `item_slot` varchar(45) default NULL,
  `item_cost` int(11) default NULL,
  PRIMARY KEY  (`contents_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE INDEX idx_gridid ON grid (grid_id);
CREATE INDEX idx_gridx ON grid (grid_x);
CREATE INDEX idx_gridy ON grid (grid_y);
CREATE INDEX idx_journeyid ON grid (journey_id);
CREATE INDEX idx_characterid ON `character` (character_id);
CREATE INDEX idx_playerid ON `character` (player_id);
CREATE INDEX idx_status ON `character` (status);
CREATE INDEX idx_journalid ON journal (journal_id);
CREATE INDEX idx_characterid ON journal (character_id);
CREATE INDEX idx_featureid ON features (feature_id);
CREATE INDEX idx_gridid ON enemy (grid_id);
CREATE INDEX idx_characterid ON enemy (character_id);
CREATE INDEX idx_status ON enemy (`status`);
CREATE INDEX idx_enemyid ON enemy (enemy_id);
CREATE INDEX idx_userid ON user (user_id);
CREATE INDEX idx_characterid ON character_details (character_id);
CREATE INDEX idx_characterid ON journey (character_id);
CREATE INDEX idx_playerid ON journey (player_id);
CREATE INDEX idx_characterid ON character_equipment (character_id);