<?php

	if ( !defined('IN_UPDATER') )
	{
		die('Do not access this file directly.');
	}		

	$tf2games = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'tf'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($tf2games, $db->escape($rowdata[0]));
	}
	
	foreach($tf2games as $game)
	{			
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('$game','paintrain', 'The Pain Train', 'kills with The Pain Train', '2.00'),
				('$game','sledgehammer', 'The Homewrecker', 'kills with The Homewrecker', '2.00');
		");
	}
	
	$l4d2games = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'l4d2'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($l4d2games, $db->escape($rowdata[0]));
	}

	foreach($l4d2games as $game)
	{			
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('$game','golfclub', 'Golf Club', 'kills with the Golf Club', '1.50'),
				('$game','rifle_m60', 'M60', 'kills with M60', '1.00');
		");
	}
	
	$db->query("UPDATE hlstats_Options SET `value` = '1.6.8' WHERE `keyname` = 'version'");	
	$db->query("UPDATE hlstats_Options SET `value` = '33' WHERE `keyname` = 'dbversion'");	
?>