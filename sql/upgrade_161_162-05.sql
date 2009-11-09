INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'scavenge_win', 0, 5, '', 'Scavenge Team Win', '', '', '1', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'versus_win', 0, 5, '', 'Versus Team Win', '', '', '1', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'defibrillated_teammate', 5, 0, '', 'Defibrillated Teammate', '1', '', '', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'used_adrenaline', 0, 0, '', 'Used Adrenaline', '1', '', '', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'jockey_ride', 5, 0, '', 'Jockey Ride', '1', '', '', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'charger_pummel', 5, 0, '', 'Charger Pummeling', '1', '', '', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'bilebomb_tank', 5, 0, '', 'Tank Bilebombed', '1', '', '', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'spitter_acidbath', 5, 0, '', 'Spitter Acid', '1', '', '', '' FROM hlstats_Games WHERE `realgame` = 'l4d2');

INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`)
(SELECT code, 'melee', 'Melee', 1.5 FROM hlstats_Games WHERE realgame = 'l4d2');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'headshot', 5, 0, '', 'Headshot Kill', '1', '0', '0', '0', 0 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_10', 9, 0, '', 'Monster Kill (10 kills)', '1', '0', '0', '0', 1 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_11', 10, 0, '', 'Unstoppable (11 kills)', '1', '0', '0', '0', 0 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_12', 15, 0, '', 'God Like (12+ kills)', '1', '0', '0', '0', 3 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_2', 1, 0, '', 'Double Kill (2 kills)', '1', '0', '0', '0', 26 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_3', 2, 0, '', 'Triple Kill (3 kills)', '1', '0', '0', '0', 7 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_4', 3, 0, '', 'Domination (4 kills)', '1', '0', '0', '0', 11 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_5', 4, 0, '', 'Rampage (5 kills)', '1', '0', '0', '0', 1 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_6', 5, 0, '', 'Mega Kill (6 kills)', '1', '0', '0', '0', 5 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_7', 6, 0, '', 'Ownage (7 kills)', '1', '0', '0', '0', 3 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_8', 7, 0, '', 'Ultra Kill (8 kills)', '1', '0', '0', '0', 0 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'kill_streak_9', 8, 0, '', 'Killing Spree (9 kills)', '1', '0', '0', '0', 0 FROM hlstats_Games WHERE `realgame` = 'nts');

INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`)
(SELECT code, 'Round_Win', 0, 20, '', 'Team Round Win', '0', '0', '1', '0', 0 FROM hlstats_Games WHERE `realgame` = 'nts');


INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
('W', 'zps', 'bat_aluminum','Out of the park!','kills with Bat (Aluminum)'),
('W', 'zps', 'bat_wood','Corked','kills with Bat (Wood)'),
('W', 'zps', 'm4','M4','kills with M4'),
('W', 'zps', 'pipe','Piping hot','kills with Pipe'),
('W', 'zps', 'slam','IEDs','kills with IED'),
('O', 'l4d2', 'defibrillated_teammate', 'Dr. Shocker', 'teammates defibrillated'),
('O', 'l4d2', 'used_adrenaline', 'Adrenaline Junkie', 'adrenaline shots used'),
('O', 'l4d2', 'jockey_ride', 'Going for a ride!', 'jockey rides'),
('O', 'l4d2', 'charger_pummel', 'Hulk Smash!', 'pummelings as a charger'),
('O', 'l4d2', 'bilebomb_tank', 'Green can''t be healthy..', 'tank bilebombs'),
('O', 'l4d2', 'spitter_acidbath', 'Spit shine', 'spitter acid attacks'),
('W', 'l4d2', 'jockey_claw', 'Little Man Claws', 'kills with Jockey''s Claws'),
('W', 'l4d2', 'spitter_claw', 'Those nails could kill', 'kills with Spitter''s Claws'),
('W', 'l4d2', 'charger_claw', 'TAAN... What is this?!', 'kills with Charger''s Claws'),
('W', 'l4d2', 'first_aid_kit', 'Hi Dr. Nick!', 'kills with a First Aid Kit'),
('W', 'l4d2', 'gascan', 'Why is my tank empty?', 'kills with a Gas Can'),
('W', 'l4d2', 'molotov', 'FIRE!!!', 'kills with a Molotov'),
('W', 'l4d2', 'pain_pills', 'I kill zombies with small bottles!', 'kills with Pain Pill bottle'),
('W', 'l4d2', 'propanetank', 'BBQ at Psychonic''s', 'kills with a Propane Tank'),
('W', 'l4d2', 'oxygentank', 'WHERE IS THE AIR SUPPORT?', 'kills with a Oxygen Tank'),
('W', 'l4d2', 'defibrillator', 'Talk about a buzzkill.', 'kills with a Defibrillator'),
('W', 'l4d2', 'grenade_launcher', 'Black Scottish Psyclops', 'kills with the Grenade Launcher'),
('W', 'l4d2', 'pistol_magnum', 'Magnum', 'kills with the Magnum'),
('W', 'l4d2', 'rifle_ak47', 'AK-47', 'kills with the AK-47'),
('W', 'l4d2', 'rifle_desert', 'Combat Rifle', 'kills with the Combat Rifle'),
('W', 'l4d2', 'shotgun_chrome', 'Chrome Shotgun', 'kills with the Chrome Shotgun'),
('W', 'l4d2', 'shotgun_spas', 'Combat Shotgun', 'kills with the Combat Shotgun'),
('W', 'l4d2', 'smg_silenced', 'Uzi (Silenced)', 'kills with the Uzi (silenced)'),
('W', 'l4d2', 'sniper_military', 'Sniper Rifle', 'kills with the Sniper Rifle'),
('W', 'l4d2', 'vomitjar', 'Swine Flu! Now shipping!', 'kills with the Vomit Jar'),
('W', 'l4d2', 'baseball_bat', 'Batter Up!', 'kills with the Baseball Bat'),
('W', 'l4d2', 'cricket_bat', 'Cheerio.', 'kills with the Cricket Bat'),
('W', 'l4d2', 'crowbar', 'Crowbar', 'kills with the Crowbar'),
('W', 'l4d2', 'electric_guitar', 'Wayne''s world party on!', 'kills with the Electric Guitar'),
('W', 'l4d2', 'fireaxe', 'Fight fire with an axe', 'kills with the Fireaxe'),
('W', 'l4d2', 'frying_pan', 'BANG Headshot.', 'kills with the Frying Pan'),
('W', 'l4d2', 'katana', 'Katana', 'kills with the Katana'),
('W', 'l4d2', 'knife', 'Knife', 'kills with the Knife'),
('W', 'l4d2', 'machete', 'Machete', 'kills with the Machete'),
('W', 'l4d2', 'tonfa', 'Tonfa', 'kills with the Tonfa'),
('W', 'l4d2', 'melee', 'Fists of RAGGEE', 'melee kills');

INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES
('aa13', 1, 0, 'nts', '1_aa13.png', 'Bronze AA13'),
('aa13', 5, 0, 'nts', '2_aa13.png', 'Silver AA13'),
('aa13', 10, 0, 'nts', '3_aa13.png', 'Gold AA13'),
('grenade_projectile', 1, 0, 'nts', '1_grenade.png', 'Bronze Frag Grenade'),
('grenade_projectile', 5, 0, 'nts', '2_grenade.png', 'Silver Frag Grenade'),
('grenade_projectile', 10, 0, 'nts', '3_grenade.png', 'Gold Frag Grenade'),
('headshot', 1, 0, 'nts', '1_headshot.png', 'Bronze Headshot'),
('headshot', 5, 0, 'nts', '2_headshot.png', 'Silver Headshot'),
('headshot', 10, 0, 'nts', '3_headshot.png', 'Gold Headshot'),
('knife', 1, 0, 'nts', '1_knife.png', 'Bronze Knife'),
('knife', 5, 0, 'nts', '2_knife.png', 'Silver Knife '),
('knife', 10, 0, 'nts', '3_knife.png', 'Gold Knife '),
('kyla', 1, 0, 'nts', '1_kyla9.png', 'Bronze Kyla-9'),
('kyla', 5, 0, 'nts', '2_kyla9.png', 'Silver Kyla-9'),
('kyla', 10, 0, 'nts', '3_kyla9.png', 'Gold Kyla-9'),
('latency', 1, 0, 'nts', '1_latency.png', 'Bronze Best Latency'),
('latency', 5, 0, 'nts', '2_latency.png', 'Silver Best Latency'),
('latency', 10, 0, 'nts', '3_latency.png', 'Gold Best Latency'),
('m41', 1, 0, 'nts', '1_m41.png', 'Bronze M41'),
('m41', 5, 0, 'nts', '2_m41.png', 'Silver M41'),
('m41', 10, 0, 'nts', '3_m41.png', 'Gold M41'),
('m41l', 1, 0, 'nts', '1_m41l.png', 'Bronze M41L'),
('m41l', 5, 0, 'nts', '2_m41l.png', 'Silver M41L'),
('m41l', 10, 0, 'nts', '3_m41l.png', 'Gold M41L'),
('milso', 1, 0, 'nts', '1_milso.png', 'Bronze MilSO'),
('milso', 5, 0, 'nts', '2_milso.png', 'Silver MilSO'),
('milso', 10, 0, 'nts', '3_milso.png', 'Gold MilSO'),
('mostkills', 1, 0, 'nts', '1_mostkills.png', 'Bronze Most Kills'),
('mostkills', 5, 0, 'nts', '2_mostkills.png', 'Silver Most Kills'),
('mostkills', 10, 0, 'nts', '3_mostkills.png', 'Gold Most Kills'),
('mpn', 1, 0, 'nts', '1_mpn45.png', 'Bronze MPN45'),
('mpn', 5, 0, 'nts', '2_mpn45.png', 'Silver MPN45'),
('mpn', 10, 0, 'nts', '3_mpn45.png', 'Gold MPN45'),
('mx', 1, 0, 'nts', '1_mx-5.png', 'Bronze MX'),
('mx', 5, 0, 'nts', '2_mx-5.png', 'Silver MX'),
('mx', 10, 0, 'nts', '3_mx-5.png', 'Gold MX'),
('mx_silenced', 1, 0, 'nts', '1_mxs-5.png', 'Bronze MX Silenced'),
('mx_silenced', 5, 0, 'nts', '2_mxs-5.png', 'Silver MX Silenced'),
('mx_silenced', 10, 0, 'nts', '3_mxs-5.png', 'Gold MX Silenced'),
('pz', 1, 0, 'nts', '1_supa7.png', 'Bronze MURATA SUPA 7'),
('pz', 5, 0, 'nts', '2_supa7.png', 'Silver MURATA SUPA 7'),
('supa7', 10, 0, 'nts', '3_supa7.png', 'Gold MURATA SUPA 7'),
('tachi', 1, 0, 'nts', '1_tachi.png', 'Bronze TACHI'),
('tachi', 5, 0, 'nts', '2_tachi.png', 'Silver TACHI'),
('tachi', 10, 0, 'nts', '3_tachi.png', 'Gold TACHI'),
('zr68c', 1, 0, 'nts', '1_zr68c.png', 'Bronze ZR68C'),
('zr68c', 5, 0, 'nts', '2_zr68c.png', 'Silver ZR68C'),
('zr68c', 10, 0, 'nts', '3_zr68c.png', 'Gold ZR68C'),
('zr68l', 1, 0, 'nts', '1_zr68l.png', 'Bronze ZR68L'),
('zr68l', 5, 0, 'nts', '2_zr68l.png', 'Silver ZR68L'),
('zr68l', 10, 0, 'nts', '3_zr68l.png', 'Gold ZR68L'),
('zr68s', 1, 0, 'nts', '1_zr68s.png', 'Bronze ZR68S'),
('zr68s', 5, 0, 'nts', '2_zr68s.png', 'Silver ZR68S'),
('zr68s', 10, 0, 'nts', '3_zr68s.png', 'Gold ZR68S');