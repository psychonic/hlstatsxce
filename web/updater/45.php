<?php
	if ( !defined('IN_UPDATER') )
	{
		die('Do not access this file directly.');
	}		

	$cssgames = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'css'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($cssgames, $db->escape($rowdata[0]));
	}
	
	foreach($cssgames as $game)
	{

		$db->query("
			INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
				('$game','round_mvp',0,0,'','Round MVP','1','','','')
		");
		
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('O','$game','round_mvp','Most Valuable Player','times earning Round MVP')
		");
	}
	
	$db->query("UPDATE hlstats_Options SET `value` = '45' WHERE `keyname` = 'dbversion'");
?>
