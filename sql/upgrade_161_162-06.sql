INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES
('l4d2', 'rescued_survivor', 2, 0, '', 'Rescued Teammate', '1', '0', '0', '0'),
('l4d2', 'healed_teammate', 5, 0, '', 'Healed Teammate', '1', '0', '0', '0'),
('l4d2', 'revived_teammate', 3, 0, '', 'Revived Teammate', '1', '0', '0', '0'),
('l4d2', 'startled_witch', -5, 0, '', 'Startled the Witch', '1', '0', '0', '0'),
('l4d2', 'pounce', 6, 0, '', '(Hunter) Pounced on Survivor', '0', '1', '0', '0'),
('l4d2', 'vomit', 6, 0, '', '(Boomer) Vomited on Survivor', '0', '1', '0', '0'),
('l4d2', 'friendly_fire', -10, 0, '', 'Friendly Fire', '1', '0', '0', '0'),
('l4d2', 'cr0wned', 0, 0, '', 'Cr0wned (killed witch with single headshot)', '1', '', '', ''),
('l4d2', 'hunter_punter', 0, 0, '', 'Hunter Punter (melee a Hunter mid-jump)', '1', '', '', ''),
('l4d2', 'tounge_twister', 0, 0, '', 'Tounge Twister (kill a Smoker while he is dragging you)', '1', '', '', ''),
('l4d2', 'protect_teammate', 0, 0, '', 'Protected Teammate', '1', '', '', ''),
('l4d2', 'no_death_on_tank', 0, 0, '', 'No survivors died/incapped from tank', '1', '', '', ''),
('l4d2', 'killed_all_survivors', 0, 0, '', 'Killed all survivors', '1', '', '', '');
