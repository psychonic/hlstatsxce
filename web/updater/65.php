<?php
	if ( !defined('IN_UPDATER') )
	{
		die('Do not access this file directly.');
	}		

	$dbversion = 65;
	$version = "1.6.13";

	$tfgames = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'tf'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($tfgames, $db->escape($rowdata[0]));
	}
	
	foreach ($tfgames as $game)
	{	
		$db->query("
			INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
				('W', '$game', 'lava_axe', 'Immolator', 'Sharpened Volcano Fragment kills'),
				('W', '$game', 'lava_bat', 'Flamer Hater', 'Sun-On-A-Stick kills)
		");
		
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('$game', 'lava_axe', 'Sharpened Volcano Fragment', 2.0),
				('$game', 'lava_bat', 'Sun-On-A-Stick', 2.0)
		");
		
		for ($ribbon_count = 1; $ribbon_count <= 3; $ribbon_count++) {
			switch ($ribbon_count) {
				case 1:
					$color = "Bronze";
					$award_count = 1;
					break;
				case 2:
					$color = "Silver";
					$award_count = 5;
					break;
				case 3:
					$color = "Gold";
					$award_count = 10;
					break;
			}
			
			$db->query("
				INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES
					('lava_axe', $award_count, 0, '$game', '" . $ribbon_count . "_lava_axe.png', '$color Sharpened Volcano Fragment'),
					('lava_bat', $award_count, 0, '$game', '" . $ribbon_count . "_lava_bat.png', '$color Sun-On-A-Stick')
			");	
		}
		
		$weapons = array(
			'lava_axe',
			'lava_bat'
		);
		$tfservers = array();
		
		$result = $db->query("SELECT serverId FROM hlstats_Servers WHERE game = '$game'");
		while ($rowdata = $db->fetch_row($result))
		{ 
			array_push($tfservers, $db->escape($rowdata[0]));
		}
		if (count($tfservers) > 0)
		{
			$serverstring = implode (',', $tfservers);
			foreach ($weapons as $weapon) {
				$db->query("UPDATE hlstats_Weapons SET `kills` = `kills` + (IFNULL((SELECT count(weapon) FROM hlstats_Events_Frags WHERE `weapon` = '$weapon' AND `serverId` IN ($serverstring)),0)) WHERE `code` = '$weapon' AND `game` = '$game'");
			}
		}

	}
	
	$db->query("UPDATE hlstats_Options SET `value` = '$version' WHERE `keyname` = 'version'");
	$db->query("UPDATE hlstats_Options SET `value` = '$dbversion' WHERE `keyname` = 'dbversion'");
?>
