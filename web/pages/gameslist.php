<?php
/*
HLstatsX Community Edition - Real-time player and clan rankings and statistics
Copyleft (L) 2008-20XX Nicholas Hastings (nshastings@gmail.com)
http://www.hlxcommunity.com

HLstatsX Community Edition is a continuation of 
ELstatsNEO - Real-time player and clan rankings and statistics
Copyleft (L) 2008-20XX Malte Bayer (steam@neo-soft.org)
http://ovrsized.neo-soft.org/

ELstatsNEO is an very improved & enhanced - so called Ultra-Humongus Edition of HLstatsX
HLstatsX - Real-time player and clan rankings and statistics for Half-Life 2
http://www.hlstatsx.com/
Copyright (C) 2005-2007 Tobias Oetzel (Tobi@hlstatsx.com)

HLstatsX is an enhanced version of HLstats made by Simon Garner
HLstats - Real-time player and clan rankings and statistics for Half-Life
http://sourceforge.net/projects/hlstats/
Copyright (C) 2001  Simon Garner
            
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

For support and installation notes visit http://www.hlxcommunity.com
*/

	if ( !defined('IN_HLSTATS') )
	{
		die('Do not access this file directly.');
	}
	
	// Contents
	
	$resultGames = $db->query("
		SELECT
			code,
			name
		FROM
			hlstats_Games
		WHERE
			hidden='0'
		ORDER BY
			realgame, name ASC
	");
	
	$num_games = $db->num_rows($resultGames);
	$redirect_to_game = 0;  
	
	?>
<ul id="header_gameslist">
<?php        
		while ($gamedata = $db->fetch_row($resultGames))
		{
			$result = $db->query("
				SELECT
					playerId,
					lastName,
					activity
				FROM
					hlstats_Players
				WHERE
					game='$gamedata[0]'
					AND hideranking=0
				ORDER BY
					".$g_options['rankingtype']." DESC
				LIMIT 1
			");
		
			if ($db->num_rows($result) == 1)
			{
				$topplayer = $db->fetch_row($result);
			}
			else
			{
				$topplayer = false;
			}
					
			$result = $db->query("
				SELECT
					hlstats_Clans.clanId,
					hlstats_Clans.name,
					AVG(hlstats_Players.skill) AS skill,
					AVG(hlstats_Players.kills) AS kills,
					COUNT(hlstats_Players.playerId) AS numplayers
				FROM
					hlstats_Clans
				LEFT JOIN
					hlstats_Players
				ON
					hlstats_Players.clan = hlstats_Clans.clanId
				WHERE
					hlstats_Clans.game='$gamedata[0]'
					AND hlstats_Players.hideranking = 0
				GROUP BY
					hlstats_Clans.clanId
				HAVING
					".$g_options['rankingtype']." IS NOT NULL
					AND numplayers >= 3
				ORDER BY
					".$g_options['rankingtype']." DESC
				LIMIT 1
			");

			if ($db->num_rows($result) == 1)
			{
				$topclan = $db->fetch_row($result);
			}
			else
			{
				$topclan = false;
			}

			$result= $db->query("
				SELECT
					SUM(act_players) AS `act_players`,                                
					SUM(max_players) AS `max_players`
				FROM
					hlstats_Servers
				WHERE
					game='$gamedata[0]'
			");
							
			$numplayers = $db->fetch_array($result);
			if ($numplayers['act_players'] == 0 and $numplayers['max_players'] == 0)
				$numplayers = false;
			else
				$player_string = $numplayers['act_players'].'/'.$numplayers['max_players'];
?>				
		<li>
			<a href="<?php echo $g_options['scripturl'] . "?game=$gamedata[0]"; ?>">
					<img src="<?php	$image = getImage("/games/$gamedata[0]/game");
				if ($image)
					echo $image['url'];
				else
					echo IMAGE_PATH . '/game.gif';
	               ?>"  style="margin-left: 2px; margin-right: 2px;" alt="Game" title="<?php echo $gamedata[1]; ?>"/>
      </a>
    </li>  
<?php
	}
?>
</ul>