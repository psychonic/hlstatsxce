<?php
	if ( !defined('IN_UPDATER') )
	{
		die('Do not access this file directly.');
	}		

	$dbversion = 60;
	$version = "1.6.12";

	$tfgames = array();
	$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'tf'");
	while ($rowdata = $db->fetch_row($result))
	{ 
		array_push($tfgames, $db->escape($rowdata[0]));
	}
	
	foreach ($tfgames as $game)
	{
	
		$db->query("
			INSERT INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES
				('$game', 'steak', 0, 0, '', 'Ate a Buffalo Steak Sandvich', '1', '0', '0', '0');
		")
		
		$db->query("
			INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
				('O', '$game', 'steak', 'Buffalo Steak Sandvich', 'Buffalo Steak Sandviches eaten'),
				('W', '$game', 'claidheamohmor', 'Claidheamohmor', 'Claidheamohmor kills'),
				('W', '$game', 'back_scratcher', 'Back Scratcher', 'Back Scratcher kills'),
				('W', '$game', 'boston_basher', 'Boston Basher', 'Boston Basher kills'),
				('W', '$game', 'steel_fists', 'Fists of Steel', 'kills with the Fists of Steel'),
				('W', '$game', 'amputator', 'Amputator', 'Amputator kills'),
				('W', '$game', 'tf_projectile_healing_bolt', 'Crusader''s Crossbow', 'Crusader''s Crossbow kills'),
				('W', '$game', 'ullapool_caber', 'Ullapool Caber', 'Ullapool Caber kills'),
				('W', '$game', 'lochnload', 'Loch-n-Load', 'Loch-n-Load kills'),
				('W', '$game', 'brassbeast', 'Brass Beast', 'Brass Beast kills'),
				('W', '$game', 'bear_claws', 'Warrior''s Spirit', 'Warrior''s Spirit kills'),
				('W', '$game', 'candy_cane', 'Candy Cane', 'Candy Cane kills'),
				('W', '$game', 'wrench_jag', 'Jag', 'Jag kills');
			;
		");
		
		$db->query("
			INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
				('$game', 'claidheamohmor', 'The Claidheamohmor', 2.0),
				('$game', 'back_scratcher', 'The Back Scratcher', 2.0),
				('$game', 'boston_basher', 'The Boston Basher', 2.0),
				('$game', 'steel_fists', 'The Fists of Steel', 2.0),
				('$game', 'amputator', 'The Amputator', 1.0),
				('$game', 'tf_projectile_healing_bolt', 'The Crusader''s Crossbow', 1.0),
				('$game', 'ullapool_caber', 'The Ullapool Caber', 2.0),
				('$game', 'lochnload', 'The Loch-n-Load', 1.0),
				('$game', 'brassbeast', 'The Brass Beast', 1.0),
				('$game', 'bear_claws', 'The Warrior''s Spirit', 2.0),
				('$game', 'candy_cane', 'The Candy Cane', 2.0),
				('$game', 'wrench_jag', 'The Jag', 2.0);
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
					('steak', $awardCount, 0, '$game', '" . $ribbon_count . "_steak.png', '$color Buffalo Steak Sandvich'),
					('claidheamohmor', $awardCount, 0, '$game', '" . $ribbon_count . "_claidheamohmor.png', '$color Claidheamohmor'),
					('back_scratcher', $awardCount, 0, '$game', '" . $ribbon_count . "_back_scratcher.png', '$color Back Scratcher'),
					('boston_basher', $awardCount, 0, '$game', '" . $ribbon_count . "_boston_basher.png', '$color Boston Basher'),
					('steel_fists', $awardCount, 0, '$game', '" . $ribbon_count . "_steel_fists.png', '$color Steel Fists'),
					('amputator', $awardCount, 0, '$game', '" . $ribbon_count . "_amputator.png', '$color Amputator'),
					('tf_projectile_healing_bolt', $awardCount, 0, '$game', '" . $ribbon_count . "_tf_projectile_healing_bolt.png', '$color Crusader''s Crossbow'),
					('ullapool_caber', $awardCount, 0, '$game', '" . $ribbon_count . "_ullapool_caber.png', '$color Ullapool Caber'),
					('lochnload', $awardCount, 0, '$game', '" . $ribbon_count . "_lochnload.png', '$color Loch-n-Load'),
					('brassbeast', $awardCount, 0, '$game', '" . $ribbon_count . "_brassbeast.png', '$color Brass Beast'),
					('bear_claws', $awardCount, 0, '$game', '" . $ribbon_count . "_bear_claws.png', '$color Warrior''s Spirit'),
					('candy_cane', $awardCount, 0, '$game', '" . $ribbon_count . "_candy_cane.png', '$color Candy Cane'),
					('wrench_jag', $awardCount, 0, '$game', '" . $ribbon_count . "_wrench_jag.png', '$color Jag');
			");		
		}
	}
	
	$db->query("UPDATE hlstats_Options SET `value` = '$version' WHERE `keyname` = 'version'");
	$db->query("UPDATE hlstats_Options SET `value` = '$dbversion' WHERE `keyname` = 'dbversion'");
?>
