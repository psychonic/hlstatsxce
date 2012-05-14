<?php
    if ( !defined('IN_UPDATER') )
    {
        die('Do not access this file directly.');
    }

    $dbversion = 68;
    $version = "1.6.16";

    // Tracker #1487 - Add Nuclear Dawn support
    print "#1487 - Updating Nuclear Dawn game support.<br />";
    $db->query("DELETE FROM `hlstats_Ranks` WHERE `game` = 'nd';");
    $db->query("OPTIMIZE TABLE `hlstats_Ranks`;");
    $db->query("
        INSERT INTO `hlstats_Ranks` (`game`, `image`, `minKills`, `maxKills`, `rankName`) VALUES
            ('nd', 'nd_01', 0, 40, 'Survivor'),
            ('nd', 'nd_02', 41, 131, 'Private I'),
            ('nd', 'nd_03', 132, 272, 'Private II'),
            ('nd', 'nd_04', 273, 483, 'Private III'),
            ('nd', 'nd_05', 484, 764, 'Private First Class I'),
            ('nd', 'nd_06', 765, 1125, 'Private First Class II'),
            ('nd', 'nd_07', 1126, 1576, 'Private First Class III'),
            ('nd', 'nd_08', 1577, 2117, 'Lance Corporal I'),
            ('nd', 'nd_09', 2118, 2768, 'Lance Corporal II'),
            ('nd', 'nd_10', 2769, 3529, 'Lance Corporal III'),
            ('nd', 'nd_11', 3530, 4410, 'Corporal I'),
            ('nd', 'nd_12', 4411, 5421, 'Corporal II'),
            ('nd', 'nd_13', 5422, 6562, 'Corporal III'),
            ('nd', 'nd_14', 6563, 7853, 'Sergeant I'),
            ('nd', 'nd_15', 7854, 9294, 'Sergeant II'),
            ('nd', 'nd_16', 9295, 10895, 'Sergeant III'),
            ('nd', 'nd_17', 10896, 12666, 'Staff Sergeant I'),
            ('nd', 'nd_18', 12667, 14607, 'Staff Sergeant II'),
            ('nd', 'nd_19', 14608, 16738, 'Staff Sergeant III'),
            ('nd', 'nd_20', 16739, 19059, 'Gunnery Sergeant I'),
            ('nd', 'nd_21', 19060, 21580, 'Gunnery Sergeant II'),
            ('nd', 'nd_22', 21581, 24311, 'Gunnery Sergeant III'),
            ('nd', 'nd_23', 24312, 27252, 'Master Sergeant I'),
            ('nd', 'nd_24', 27253, 30423, 'Master Sergeant II'),
            ('nd', 'nd_25', 30424, 33824, 'Master Sergeant III'),
            ('nd', 'nd_26', 33825, 37465, 'First Sergeant I'),
            ('nd', 'nd_27', 37466, 41356, 'First Sergeant II'),
            ('nd', 'nd_28', 41357, 45497, 'First Sergeant III'),
            ('nd', 'nd_29', 45498, 49928, 'Master Gunnery Sergeant I'),
            ('nd', 'nd_30', 49929, 54669, 'Master Gunnery Sergeant II'),
            ('nd', 'nd_31', 54670, 59750, 'Master Gunnery Sergeant III'),
            ('nd', 'nd_32', 59751, 65201, 'Sergeant Major I'),
            ('nd', 'nd_33', 65202, 71062, 'Sergeant Major II'),
            ('nd', 'nd_34', 71063, 77343, 'Sergeant Major III'),
            ('nd', 'nd_35', 77344, 84054, 'Elite Sergeant Major'),
            ('nd', 'nd_36', 84055, 91215, 'Field Lieutenant'),
            ('nd', 'nd_37', 91216, 98826, 'Second Lieutenant'),
            ('nd', 'nd_38', 98827, 106907, 'First Lieutenant'),
            ('nd', 'nd_39', 106908, 115478, 'Field Captain'),
            ('nd', 'nd_40', 115479, 124549, 'Captain'),
            ('nd', 'nd_41', 124550, 134130, 'Vanguard Captain'),
            ('nd', 'nd_42', 134131, 144231, 'Field Major'),
            ('nd', 'nd_43', 144232, 154862, 'Major'),
            ('nd', 'nd_44', 154863, 166043, 'Lieutenant Colonel'),
            ('nd', 'nd_45', 166044, 177794, 'Colonel'),
            ('nd', 'nd_46', 177795, 190115, 'Vanguard Colonel'),
            ('nd', 'nd_47', 190116, 203026, 'Commander'),
            ('nd', 'nd_48', 203027, 216537, 'Vanguard Commander'),
            ('nd', 'nd_49', 216538, 230658, 'Elite Commander'),
            ('nd', 'nd_50', 230659, 245409, 'Brigadier General Third Class'),
            ('nd', 'nd_51', 245410, 260800, 'Brigadier General Second Class'),
            ('nd', 'nd_52', 260801, 276841, 'Brigadier General First Class'),
            ('nd', 'nd_53', 276842, 293552, 'Major General Third Class'),
            ('nd', 'nd_54', 293553, 310943, 'Major General Second Class'),
            ('nd', 'nd_55', 310944, 329024, 'Major General First Class'),
            ('nd', 'nd_56', 329025, 347815, 'Lieutenant General Third Class'),
            ('nd', 'nd_57', 347816, 367316, 'Lieutenant General Second Class'),
            ('nd', 'nd_58', 367317, 387547, 'Lieutenant General First Class'),
            ('nd', 'nd_59', 387548, 408528, 'General'),
            ('nd', 'nd_60', 408529, 9999999, 'Vanguard General');
    ");
  
  $db->query("UPDATE `hlstats_Awards` SET `name` = 'M-95 L.A.W.S.', `verb` = 'kills with M-95 L.A.W.S.' WHERE `name` = 'M-95 L.A.W.S'");
  $db->query("
        INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES
            ('W', 'nd', 'm95', 'M-95 L.A.W.S.', 'kills with M-95 L.A.W.S.'),
            ('W', 'nd', 'sonic turret', 'SONIC TURRET', 'kills with Sonic Turret');
    ");
  
  $db->query("UPDATE `hlstats_Ribbons` SET `ribbonName` = 'Young: M-95 L.A.W.S.' WHERE ribbonName = 'Young: M-95 L.A.W.S'");
  $db->query("UPDATE `hlstats_Ribbons` SET `ribbonName` = 'Bronze: M-95 L.A.W.S.' WHERE ribbonName = 'Bronze: M-95 L.A.W.S'");
  $db->query("UPDATE `hlstats_Ribbons` SET `ribbonName` = 'Silver: M-95 L.A.W.S.' WHERE ribbonName = 'Silver: M-95 L.A.W.S'");
  $db->query("UPDATE `hlstats_Ribbons` SET `ribbonName` = 'Golden: M-95 L.A.W.S.' WHERE ribbonName = 'Golden: M-95 L.A.W.S'");
  $db->query("UPDATE `hlstats_Ribbons` SET `ribbonName` = 'Platinum: M-95 L.A.W.S.' WHERE ribbonName = 'Platinum: M-95 L.A.W.S'");
  $db->query("UPDATE `hlstats_Ribbons` SET `ribbonName` = 'Bloody: M-95 L.A.W.S.' WHERE ribbonName = 'Bloody: M-95 L.A.W.S'");
  $db->query("
        INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES
            ('sonic turret', 1, 0, 'nd', '1_sonic turret.png', 'Young: Sonic Turret'),
            ('sonic turret', 5, 0, 'nd', '2_sonic turret.png', 'Bronze: Sonic Turret'),
            ('sonic turret', 15, 0, 'nd', '3_sonic turret.png', 'Silver: Sonic Turret'),
            ('sonic turret', 30, 0, 'nd', '4_sonic turret.png', 'Golden: Sonic Turret'),
            ('sonic turret', 50, 0, 'nd', '5_sonic turret.png', 'Platinum: Sonic Turret'),
            ('sonic turret', 75, 0, 'nd', '6_sonic turret.png', 'Bloody: Sonic Turret');
    ");
  
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1 WHERE `game`='nd' AND `code`='armblade'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1 WHERE `game`='nd' AND `code`='armknives'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=0.7 WHERE `game`='nd' AND `code`='artillery'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=0.6 WHERE `game`='nd' AND `code`='commander damage'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1.5 WHERE `game`='nd' AND `code`='env_explosion'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=0.4 WHERE `game`='nd' AND `code`='flamethrower turret'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1.5 WHERE `game`='nd' AND `code`='grenade launcher'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1 WHERE `game`='nd' AND `code`='m95'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=0.4 WHERE `game`='nd' AND `code`='mg turret'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1 WHERE `game`='nd' AND `code`='paladin'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1 WHERE `game`='nd' AND `code`='psg'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=1.5 WHERE `game`='nd' AND `code`='R.E.D.'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=5 WHERE `game`='nd' AND `code`='repair tool'");
    $db->query("UPDATE `hlstats_Weapons` SET `modifier`=0.4 WHERE `game`='nd' AND `code`='rocket turret'");
    $db->query("
        INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES
            ('nd', 'sonic turret', 'Sonic Turrent', 0.40),
            ('nd', 'world', 'World', 1.00);
    ");
    
    // Tracker #1456 - Change all instances of server_id to INT(10) to standardize across database.
    print "#1456 - Updating server_id columns<br />";
    $db->query("ALTER IGNORE TABLE `hlstats_server_load` MODIFY `server_id` INTEGER(10);");
    $db->query("ALTER IGNORE TABLE `hlstats_Livestats` MODIFY `server_id` INTEGER(10);");

    // Tracker #1546 - Add additional Team Fortress 2 weapons to database.

    // Weapons
    // Name is the name of the weapon
    // Verb is the "action" described on the award
    // Modifier is used to adjust points given for a kill with the weapon
    // Award name sets the name of the award
    $weapons = array(
        array(
            "weapon_code" => "eureka_effect",
            "weapon_name" => "Eureka Effect",
            "award_verb" => "Eureka Effect kills",
            "modifier" => "2.00",
            "award_name" => "Eureka!"),
        array(
            "weapon_code" => "holiday_punch",
            "weapon_name" => "Holiday Punch",
            "award_verb" => "Holiday Punch kills",
            "modifier" => "2.00",
            "award_name" => "The Gift of Punch"),
        array(
            "weapon_code" => "manmelter",
            "weapon_name" => "Manmelter",
            "award_verb" => "Manmelter kills",
            "modifier" => "2.00",
            "award_name" => "Melted Men"),
        array(
            "weapon_code" => "phlogistinator",
            "weapon_name" => "Phlogistinator",
            "award_verb" => "Phlogistinator kills",
            "modifier" => "2.00",
            "award_name" => "Phlogged"),
        array(
            "weapon_code" => "pomson",
            "weapon_name" => "Pomson 6000",
            "award_verb" => "Pomson 6000 kills",
            "modifier" => "2.00",
            "award_name" => "Convenient Radiation"),
        array(
            "weapon_code" => "spy_cicle",
            "weapon_name" => "Spy-cicle",
            "award_verb" => "Spy-cicle kills",
            "modifier" => "2.00",
            "award_name" => "Cold as ice"),
        array(
            "weapon_code" => "thirddegree",
            "weapon_name" => "Third Degree",
            "award_verb" => "Third Degree kills",
            "modifier" => "2.00",
            "award_name" => "Ooooh burn!"),
        array(
            "weapon_code" => "wrap_assassin",
            "weapon_name" => "Wrap Assassin",
            "award_verb" => "Wrap Assassin kills",
            "modifier" => "2.00",
            "award_name" => "Wrapping Machine")
    );

    foreach ($tfgames as $game)
    {
        // Get list of all Team Fortress 2 servers so we can update weapon counts later.
        $tfservers = array();
        $result = $db->query("SELECT serverId FROM hlstats_Servers WHERE game = '$game'");
        while ($rowdata = $db->fetch_row($result))
        {
            array_push($tfservers, $db->escape($rowdata[0]));
        }
        if (count($tfservers) > 0)
        {
            $serverstring = implode (',', $tfservers);
        }

        // Insert actions
        print "Adding new actions for game $game.<br />";
        if (isset($actions) && count($actions) > 0)
        {
            $action_query = "INSERT IGNORE INTO `hlstats_Actions` (`game`, `code`, `reward_player`, `reward_team`, `team`, `description`, `for_PlayerActions`, `for_PlayerPlayerActions`, `for_TeamActions`, `for_WorldActions`) VALUES ";
            $award_query = "INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES ";
            $ribbon_query = "INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES ";
            // Insert actions
            foreach ($actions as $key => $action)
            {
                // Insert actions into Actions table
                $action_query .= "(
                    '$game',
                    '".$db->escape($action['code'])."',
                    '".$db->escape($action['reward_player'])."',
                    '".$db->escape($action['reward_team'])."',
                    '".$db->escape($action['team'])."',
                    '".$db->escape($action['description'])."',
                    '".$db->escape($action['for_PlayerActions'])."',
                    '".$db->escape($action['for_PlayerPlayerActions'])."',
                    '".$db->escape($action['for_TeamActions'])."',
                    '".$db->escape($action['for_WorldActions'])."')" .
                    // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                    ($key == count($actions)-1 ? ";" : ",");
                
                if ($action['award_name'] != "")
                {
                    $award_query .= "(
                        '".$db->escape($action['award_type'])."',
                        '$game',
                        '".$db->escape($action['code'])."',
                        '".$db->escape($action['award_name'])."',
                        '".$db->escape($action['award_verb'])."')" .
                        // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                        ($key == count($actions)-1 ? ";" : ",");
                    
                        // Insert actions into Ribbons table
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
                        $ribbon_query .= "(
                        '".$db->escape($action['code'])."',
                        $award_count,
                        0,
                        '$game',
                        '".$ribbon_count."_".$db->escape($action['code']).".png',
                        '$color " .$db->escape($action['description']) . "')" .
                        // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                        ($key == count($actions)-1 && $ribbon_count == 3 ? ";" : ",");
                        }
                }
            }
            $db->query($action_query);
            $db->query($award_query);
            $db->query($ribbon_query);
            unset($action_query);
            unset($award_query);
            unset($ribbon_query);
        }

        // Insert awards
        print "Adding new awards for game $game.<br />";
        if (isset($awards) && count($awards) > 0)
        {
            $award_query = "INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES ";
            $ribbon_query = "INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES ";
            
            foreach ($awards as $key => $award)
            {
                // Insert awards into Awards table
                $award_query .= "(
                    '".$db->escape($award['type'])."',
                    '$game',
                    '".$db->escape($award['code'])."',
                    '".$db->escape($award['award_name'])."',
                    '".$db->escape($award['award_verb'])."')" .
                    // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                    ($key == count($awards)-1 ? ";" : ",");
                
                // Insert awards into Ribbons table
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
                    $ribbon_query .= "(
                    '".$db->escape($award['code'])."',
                    $award_count,
                    0,
                    '$game',
                    '".$ribbon_count."_".$db->escape($award['code']).".png',
                    '$color " .$db->escape($award['award_name']) . "')" .
                    // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                    ($key == count($awards)-1 && $ribbon_count == 3 ? ";" : ",");
                }
            }
            $db->query($award_query);
            $db->query($ribbon_query);
            unset($award_query);
            unset($ribbon_query);
        }

        // Insert weapons
        print "Adding new weapons for game $game.<br />";
        if (isset($weapons) && count($weapons) > 0)
        {
            $award_query = "INSERT IGNORE INTO `hlstats_Awards` (`awardType`, `game`, `code`, `name`, `verb`) VALUES ";
            $ribbon_query = "INSERT IGNORE INTO `hlstats_Ribbons` (`awardCode`, `awardCount`, `special`, `game`, `image`, `ribbonName`) VALUES ";
            $weapon_query = "INSERT IGNORE INTO `hlstats_Weapons` (`game`, `code`, `name`, `modifier`) VALUES ";
            foreach ($weapons as $key => $weapon)
            {
                // Insert weapons into Weapons table
                $weapon_query .= "(
                    '$game',
                    '".$db->escape($weapon['weapon_code'])."',
                    '".$db->escape($weapon['weapon_name'])."',
                    '".$db->escape($weapon['modifier'])."')" .
                    // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                    ($key == count($weapons)-1 ? ";" : ",");

                    
                // Insert weapons into Awards table
                $award_query .= "(
                    'W',
                    '$game',
                    '".$db->escape($weapon['weapon_code'])."',
                    '".$db->escape($weapon['weapon_name'])."',
                    '".$db->escape($weapon['award_verb'])."')" .
                    // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                    ($key == count($weapons)-1 ? ";" : ",");
                
                // Insert weapons into Ribbons table
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
                    $ribbon_query .= "(
                    '".$db->escape($weapon['weapon_code'])."',
                    $award_count,
                    0,
                    '$game',
                    '".$ribbon_count."_".$db->escape($weapon['weapon_code']).".png',
                    '$color ".$db->escape($weapon['weapon_name']) . "')" .
                    // Check to see if we're on the last key -- if so finish the SQL statement, otherwise leave it open to append
                    ($key == count($weapons)-1 && $ribbon_count == 3 ? ";" : ",");
                }
                
                // Update kill count for any weapons just added
                print "Updating weapon count for ".$db->escape($weapon['weapon_code'])." in game $game<br />";
                if (!empty($serverstring))
                {
                    $db->query("
                        UPDATE IGNORE
                            hlstats_Weapons
                        SET
                            `kills` = `kills` + (
                                IFNULL((
                                    SELECT count(weapon)
                                        FROM
                                            hlstats_Events_Frags
                                        WHERE
                                            `weapon` = '".$db->escape($weapon['weapon_code'])."'
                                        AND
                                            `serverId` IN ($serverstring)
                                    ),0)
                            )
                        WHERE
                            `code` = '".$db->escape($weapon['weapon_code'])."'
                        AND
                            `game` = '$game';");
                }
            }
            $db->query($weapon_query);
            $db->query($award_query);
            $db->query($ribbon_query);
            unset($weapon_query);
            unset($award_query);
            unset($ribbon_query);
        }
    }    
    
    // Perform database schema update notification
    print "Updating database and verion schema numbers.<br />";
    $db->query("UPDATE hlstats_Options SET `value` = '$version' WHERE `keyname` = 'version'");
    $db->query("UPDATE hlstats_Options SET `value` = '$dbversion' WHERE `keyname` = 'dbversion'");
?>