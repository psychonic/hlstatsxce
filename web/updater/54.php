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

	$l4d2games = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'l4d' AND (code LIKE 'l4d2%' OR code LIKE 'l4dii%'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($l4d2games, $db->escape($rowdata[0]));
	}

	foreach($l4d2games as $game)
	{			
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('$game','golfclub', 'Golf Club', 1.5),
				('$game','rifle_m60', 'M60', 1);
		");
		$db->query("
			INSERT INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
				('W', '$game','golfclub', 'Golf Club', 'kills with the Golf Club'),
				('W', '$game','rifle_m60', 'M60', 'kills with M60');
		");
	}

	
	$db->query("UPDATE hlstats_Options SET `value` = '$version' WHERE `keyname` = 'version'");
	$db->query("UPDATE hlstats_Options SET `value` = '$dbversion' WHERE `keyname` = 'dbversion'");
?>
