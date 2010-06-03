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
		// Create new actions
		$db->query("INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES ('$game', 'killedobject_obj_teleporter', 2, 0, '', 'Destroyed a teleporter', '1', '', '', '');");
		$tfBuiltTeleportID = $db->insert_id();	
		$db->query("INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES ('$game', 'builtobject_obj_teleporter', 2, 0, '', 'Built a teleporter', '1', '', '', '');");
		$tfDestroyedTeleportID = $db->insert_id();	
		$db->query("INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES ('$game', 'owner_killedobject_obj_teleporter', -2, 0, '', 'Disassembled a teleporter', '1', '', '', '');");
		$tfDisassembledTeleportID = $db->insert_id();	

		// Find old actions and their counts
		$db->query("SELECT id,count FROM hlstats_Actions WHERE game='$game' and code='builtobject_obj_teleporter_entrance'");
		list($tfBuiltEntranceID,$tfBuiltEntranceCount) = $db->fetch_row();
		$db->query("SELECT id,count FROM hlstats_Actions WHERE game='$game' and code='builtobject_obj_teleporter_exit'");
		list($tfBuiltExitID,$tfBuiltExitCount) = $db->fetch_row();
		$db->query("SELECT id,count FROM hlstats_Actions WHERE game='$game' and code='killedobject_obj_teleporter_exit'");
		list($tfDestroyedEntranceID,$tfDestroyedEntranceCount) = $db->fetch_row();
		$db->query("SELECT id,count FROM hlstats_Actions WHERE game='$game' and code='killedobject_obj_teleporter_entrance'");
		list($tfDestroyedExitID,$tfDestroyedExitCount) = $db->fetch_row();
		$db->query("SELECT id,count FROM hlstats_Actions WHERE game='$game' and code='owner_killedobject_obj_teleporter_entrance'");
		list($tfDisassembledEntranceID,$tfDisassembledEntranceCount) = $db->fetch_row();
		$db->query("SELECT id,count FROM hlstats_Actions WHERE game='$game' and code='owner_killedobject_obj_teleporter_exit'");
		list($tfDisassembledExitID,$tfDisassembledExitCount) = $db->fetch_row();
		
		// Take counts of old actions and add them to new action.
		$db->query("UPDATE hlstats_Actions SET `count`=$tfBuiltEntranceCount+$tfBuiltExitCount WHERE `id`='$tfBuiltTeleportID'");
		$db->query("UPDATE hlstats_Actions SET `count`=$tfDestroyedEntranceCount+$tfDestroyedExitCount WHERE `id`='$tfDestroyedTeleportID'");
		$db->query("UPDATE hlstats_Actions SET `count`=$tfDisassembledEntranceCount+$tfDisassembledExitCount WHERE `id`='$tfDisassembledTeleportID'");

		// Update Events_PlayerActions
		$db->query("UPDATE hlstats_Events_PlayerActions SET `actionId`=$tfBuiltTeleportID WHERE `actionId` IN ($tfBuiltEntranceID,$tfBuiltExitID)");
		$db->query("UPDATE hlstats_Events_PlayerActions SET `actionId`=$tfDestroyedTeleportID WHERE `actionId` IN ($tfDestroyedEntranceID,$tfDestroyedExitID)");
		$db->query("UPDATE hlstats_Events_PlayerActions SET `actionId`=$tfDisassembledTeleportID WHERE `actionId` IN ($tfDisassembledEntranceID,$tfDisassembledExitID)");
		
		// Remove old actions
		$db->query("DELETE FROM hlstats_Actions WHERE `id` IN ($tfBuiltEntranceID,$tfBuiltExitID,$tfDestroyedEntranceID,$tfDestroyedExitID,$tfDisassembledEntranceID,$tfDisassembledExitID)");
			
	}
	
	$db->query("UPDATE hlstats_Options SET `value` = '1.7.0-dev' WHERE `keyname` = 'version'");
	$db->query("UPDATE hlstats_Options SET `value` = '39' WHERE `keyname` = 'dbversion'");	
?>
