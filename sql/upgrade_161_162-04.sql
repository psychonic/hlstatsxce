ALTER TABLE `hlstats_Players` ADD COLUMN `createdate` int(11);
UPDATE `hlstats_Players` SET `createdate` = UNIX_TIMESTAMP();

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'jarate', 1, 0, '', 'Jarated player', '0', '1', '0', '0' FROM hlstats_Games WHERE `realgame` = 'tf');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'shield_blocked', 0, 0, '', 'Blocked with Shield', '0', '1', '0', '0' FROM hlstats_Games WHERE `realgame` = 'tf');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'insect_swarm', 'insect_swarm', 1 FROM hlstats_Games WHERE realgame = 'l4d2');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'bat_aluminum', 'Bat (Aluminum)', 1.5 FROM hlstats_Games WHERE realgame = 'zps');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'bat_wood', 'Bat (Wood)', 1.5 FROM hlstats_Games WHERE realgame = 'zps');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'm4', 'M4', 1 FROM hlstats_Games WHERE realgame = 'zps');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'pipe', 'Pipe', 1 FROM hlstats_Games WHERE realgame = 'zps');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'slam', 'IED', 1 FROM hlstats_Games WHERE realgame = 'zps');

INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
('W', 'zps', 'bat_aluminum','Out of the park!','kills with Bat (Aluminum)'),
('W', 'zps', 'bat_wood','Corked','kills with Bat (Wood)'),
('W', 'zps', 'm4','M4','kills with M4'),
('W', 'zps', 'pipe','Piping hot','kills with Pipe'),
('W', 'zps', 'slam','IEDs','kills with IED');