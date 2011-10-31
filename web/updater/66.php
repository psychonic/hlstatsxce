<?php
		if ( !defined('IN_UPDATER') )
		{
				die('Do not access this file directly.');
		}				

		$dbversion = 66;
		$version = "1.6.15";

		// Tracker #1439/1447 - New TF2 weapons - Victory Pack and Manno-Technology pack
		$tfgames = array();
		$result = $db->query("SELECT code FROM hlstats_Games WHERE realgame = 'tf'");
		while ($rowdata = $db->fetch_row($result))
		{ 
				array_push($tfgames, $db->escape($rowdata[0]));
		}

		$weapons = array(
				array(
					"code" => "cow_mangler",
					"name" => "Cow Mangler 5000",
					"verb" => "Cow Mangler 5000 kills",
					"modifier" => "1.00",
					"award_name" => "Cows Mangled"),
				array(
					"code" => "righteous_bison",
					"name" => "Righteous Bison",
					"verb" => "Righteous Bison kills",
					"modifier" => "2.00",
					"award_name" => "Bisons Blasted"),
				array(
					"code" => "tf_projectile_energy_ball",
					"name" => "Deflected Cow Mangler Shot",
					"verb" => "Deflected Cow Mangler Shot kills",
					"modifier" => "5.00",
					"award_name" => "Save A Cow, Eat mor Chikin"),
				array(
					"code" => "machina",
					"name" => "Machina",
					"verb" => "Machina kills",
					"modifier" => "2.00",
					"award_name" => "Problems Solved"),
				array(
					"code" => "diamondback",
					"name" => "The Diamondback",
					"verb" => "Diamondback kills",
					"modifier" => "2.00",
					"award_name" => "Souls Rattled"),
				array(
					"code" => "widowmaker",
					"name" => "The Widowmaker",
					"verb" => "Widowmaker kills",
					"modifier" => "2.00",
					"award_name" => "Widows Made"),
				array(
					"code" => "short_circuit",
					"name" => "The Short Circuit",
					"verb" => "Short Circuit kills",
					"modifier" => "2.00",
					"award_name" => "Circuits Shorted")
		);
		
		foreach ($tfgames as $game)
		{
		
				// Insert new awards
				$query = "INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES ";
				foreach ($weapons as $key => $weapon)
				{
					$code = $db->escape($weapon['code']);
					$award_name = $db->escape($weapon['award_name']);
					$verb = $db->escape($weapon['verb']);
					$query .= "('W', '$game', '$code', '$award_name', '$verb')" .
					// Finish query line -- Check if this is the last index.  If so, add a semi-colon.  Otherwise, colon.
					($key == count($weapons)-1 ? ";" : ",");
				}
				$db->query($query);
				unset($weapon);
				unset($query);
				
				// Insert new weapons
				$query = "INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES ";
				foreach ($weapons as $key => $weapon)
				{
					$code = $db->escape($weapon['code']);
					$name = $db->escape($weapon['name']);
					$modifier = $db->escape($weapon['modifier']);
					$query .= "('$game', '$code', '$name', $modifier)" .
					// Finish query line -- Check if this is the last index.  If so, add a semi-colon.  Otherwise, colon.
					($key == count($weapons)-1 ? ";" : ",");
				}
				$db->query($query);
				unset($weapon);
				unset($query);
				
				// Insert new ribbons
				$query = "INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES ";
				foreach ($weapons as $key => $weapon)
				{
					$code = $db->escape($weapon['code']);
					$name = $db->escape($weapon['name']);
					for ($ribbon_count = 1; $ribbon_count <= 3; $ribbon_count++)
					{
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
							$query .= "('$code', $award_count, 0, '$game', '" . $ribbon_count . "_" . $code . ".png', '$color $name')" .
									// Finish query line -- Check if this is the last index.  If so, add a semi-colon.  Otherwise, colon.
									($key == count($weapons)-1 && $ribbon_count == 3 ? ";" : ",");
					}
				}
				$db->query($query);
				unset($weapon);
				unset($query);
				
				// Update kill count for new weapons
				$tfservers = array();
				
				$result = $db->query("SELECT serverId FROM hlstats_Servers WHERE game = '$game'");
				while ($rowdata = $db->fetch_row($result))
				{ 
					array_push($tfservers, $db->escape($rowdata[0]));
				}
				if (count($tfservers) > 0)
				{
					$serverstring = implode (',', $tfservers);
							// Change all player_penetration weapon kill lines in event Frags to machina before doing our calculation.
							$db->query("UPDATE hlstats_Events_Frags SET `weapon` = 'machina' WHERE `weapon` = 'player_penetration' AND `serverId` in ($serverstring)");

					foreach ($weapons as $weapon) {
							$code = $db->escape($weapon['code']);
							$db->query("UPDATE hlstats_Weapons SET `kills` = `kills` + (IFNULL((SELECT count(weapon) FROM hlstats_Events_Frags WHERE `weapon` = '$code' AND `serverId` IN ($serverstring)),0)) WHERE `code` = '$code' AND `game` = '$game'");
					}
					unset($weapon);
				}
				
				// Handle the new player_penetration HLXCE action -- player_penetration weapon is captured and provided as an action.		Kill line occurs for machina instead.
				$db->query("INSERT INTO hlstats_Actions (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES
					('$game', 'player_penetration', 4, 0, '', 'Player Penetration', '1', '', '', '');
				");
				
		}
		
		// Tracker #1439/1447 - End
		
		
		
		$db->query("UPDATE hlstats_Options SET `value` = '$version' WHERE `keyname` = 'version'");
		$db->query("UPDATE hlstats_Options SET `value` = '$dbversion' WHERE `keyname` = 'dbversion'");
?>
