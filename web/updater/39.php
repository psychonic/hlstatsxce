<?php

	if ( !defined('IN_UPDATER') )
	{
		die('Do not access this file directly.');
	}		

	$tfgames = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'tf'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($tfgames, $db->escape($rowdata[0]));
	}
	
	foreach($tfgames as $game)
	{
		$db->query("
			INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES
				('$game', 'killedobject_obj_teleporter', 2, 0, '', 'Destroyed a teleporter', '1', '', '', ''),
				('$game', 'builtobject_obj_teleporter', 2, 0, '', 'Built a teleporter', '1', '', '', ''),
				('$game', 'owner_killedobject_obj_teleporter', -2, 0, '', 'Disassembled a teleporter', '1', '', '', '');
		");
	}
	
	$db->query("UPDATE hlstats_Options SET `value` = '1.6.9' WHERE `keyname` = 'version'");
	$db->query("UPDATE hlstats_Options SET `value` = '39' WHERE `keyname` = 'dbversion'");	
?>
