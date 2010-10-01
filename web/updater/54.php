<?php
	if ( !defined('IN_UPDATER') )
	{
		die('Do not access this file directly.');
	}		

	$dbversion = 54;
	$version = "1.6.11-beta2";

	$hl2mpserverids = array();
	$result = $db->query("SELECT hlstats_Servers.serverId FROM hlstats_Servers JOIN hlstats_Games ON hlstats_Servers.game = hlstats_Games.code WHERE hlstats_Games.realgame = 'hl2mp' AND hlstats_Games.code != 'hl2ctf'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($hl2mpserverids, $db->escape($rowdata[0]));
	}
	$serverids = implode(",", $hl2mpserverids);
	$db->query("UPDATE IGNORE `hlstats_Servers_Config` SET `value` = '3' WHERE `parameter` = 'GameEngine' AND `serverId` in ($serverids);");
	$db->query("UPDATE IGNORE `hlstats_Games_Defaults` SET `value` = '3' WHERE `code` = 'hl2mp' AND `parameter` = 'GameEngine';");
	
	$tfgames = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'tf'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($tfgames, $db->escape($rowdata[0]));
	}
	
	foreach ($tfgames as $game)
	{
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('$game', 'blackbox', 'The Black Box', 1.00),
				('$game', 'sydney_sleeper', 'The Sydney Sleeper', 1.00);
		");
		
		$db->query("
			INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES
				('blackbox', 1, 0, '$game', '1_blackbox.png', 'Bronze Black Box'),
				('blackbox', 5, 0, '$game', '2_blackbox.png', 'Silver Black Box'),
				('blackbox', 10, 0, '$game', '3_blackbox.png', 'Gold Black Box'),
				('sydney_sleeper', 1, 0, '$game', '1_sydney_sleeper.png', 'Bronze Sydney Sleeper'),
				('sydney_sleeper', 5, 0, '$game', '2_sydney_sleeper.png', 'Silver Sydney Sleeper'),
				('sydney_sleeper', 10, 0, '$game', '3_sydney_sleeper.png', 'Gold Sydney Sleeper');
		");
		
		$db->query("
			INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
				('W','$game','blackbox', 'What''s in the box?', 'kills with The Black Box'),
				('W','$game','sydney_sleeper', 'Down Under', 'kills with The Sydney Sleeper');
		");
	}
	$db->query("UPDATE hlstats_Options SET `value` = '$version' WHERE `keyname` = 'version'");
	$db->query("UPDATE hlstats_Options SET `value` = '$dbversion' WHERE `keyname` = 'dbversion'");
?>
