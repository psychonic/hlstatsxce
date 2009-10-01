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

function printMap($type = 'main')
{
	global $db, $game, $g_options, $clandata, $clan;
	
	if ($type == 'main')
	{
		echo ('<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$g_options['google_map_key'].'" type="text/javascript"></script>');
	}
?> 
		<script type="text/javascript">
		/* <![CDATA[ */
		//Add the preloads here...so that they don't get load
		//after the graphs load
		function preloadImages() {
			var d=document; if(d.images){ if(!d.p) d.p=new Array();
			var i,j=d.p.length,a=preloadImages.arguments; for(i=0; i<a.length; i++)
			if (a[i].indexOf("#")!=0){ d.p[j]=new Image; d.p[j++].src=a[i];}}
		}

		<?php echo "preloadImages('".IMAGE_PATH."/mm_20_blue.png', ".(($type == 'main')?"'".IMAGE_PATH."/mm_20_red.png', ":'')."'".IMAGE_PATH."/mm_20_shadow.png');"; ?>
			var icon = new GIcon();
			icon.image = "<?php echo IMAGE_PATH; ?>/mm_20_blue.png";
			icon.shadow = "<?php echo IMAGE_PATH; ?>/mm_20_shadow.png";
			icon.iconSize = new GSize(12, 20);
			icon.shadowSize = new GSize(22, 20);
			icon.iconAnchor = new GPoint(6, 20);
			icon.infoWindowAnchor = new GPoint(5, 1);

<?php
		if ($type == 'main') {
?>
			var iconS = new GIcon();
			iconS.image = "<?php echo IMAGE_PATH; ?>/mm_20_red.png";
			iconS.shadow = "<?php echo IMAGE_PATH; ?>/mm_20_shadow.png";
			iconS.iconSize = new GSize(12, 20);
			iconS.shadowSize = new GSize(22, 20);
			iconS.iconAnchor = new GPoint(6, 20);
			iconS.infoWindowAnchor = new GPoint(5, 1);
<?php
		}
?>
			var map = new GMap2(document.getElementById("map"));

			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			map.enableDoubleClickZoom();
	<?php
			printMapCenter(($type == 'clan' && $clandata['mapregion'] != '') ? $clandata['mapregion'] : $g_options['google_map_region']);
			printMapType($g_options['google_map_type']);
	?>       
				 
			function createMarker(point, city, country, player_info) {
				var marker    = new GMarker(point, icon);
				var html_text = '<table class="gmapstab"><tr><td colspan="2" class="gmapstabtitle" style="border-bottom:1px solid black;">'+city+', '+country+'</td></tr>';
				for ( i=0; i<player_info.length; i++) {
					html_text += '<tr><td><a href="hlstats.php?mode=playerinfo&amp;player='+player_info[i][0]+'">'+player_info[i][1]+'</a></td></tr>'+
								'<tr><td>Kills/Deaths</td><td>'+player_info[i][2]+':'+player_info[i][3]+'</td></tr>'<?php
			if ($type == 'main') {
				echo "+
				'<tr><td>Time</td><td>'+player_info[i][4]+'</td></tr>';";
			} else {
				echo ";";
			}
?>
				}
				html_text +=   '</table>';
				map.addOverlay(marker);
				GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html_text);});
			}

<?php
			if ($type == 'main') {
?>
			function createMarkerS(point, servers, city, country, kills) {
				var marker    = new GMarker(point, iconS);
				var html_text =   '<table class="gmapstab"><tr><td colspan="2" class="gmapstabtitle" style="border-bottom:1px solid black;">'+city+', '+country+'</td></tr>';
				for ( i=0; i<servers.length; i++) {
					html_text +=  '<tr><td><a href=\"hlstats.php?mode=servers&server_id=' + servers[i][0] + '&amp;game=<?php echo $game; ?>\">' + servers[i][2] + '</a></td></tr>'+
					'<tr><td>' + servers[i][1] + ' (<a href=\"steam://connect/' + servers[i][1] + '\">connect</a>)</td></tr>';
				}
				html_text +=      '<tr><td>'+kills+' kills</td></tr>'+
							  '</table>';  
				map.addOverlay(marker);
				GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html_text);});
			}
	<?php

				$db->query("SELECT serverId, IF(publicaddress != '', publicaddress, CONCAT(address, ':', port)) AS addr, name, kills, lat, lng, city, country FROM hlstats_Servers WHERE game='$game' AND lat IS NOT NULL AND lng IS NOT NULL");

				$servers = array();
				while ($row = $db->fetch_array())
				{
					//Skip this part, if we already have the location info (should be the same)
					if (!isset($servers[$row['lat'] . ',' . $row['lng']]))
					{
						$servers[$row['lat'] . ',' . $row['lng']] = array('lat' => $row['lat'], 'lng' => $row['lng'], 'publicaddress' => $row['public_address'], 'city' => $row['city'], 'country' => $row['country']);
					}

					$servers[$row['lat'] . ',' . $row['lng']]['servers'][] = array('serverId' => $row['serverId'], 'addr' => $row['addr'], 'name' => $row['name'], 'kills' => $row['kills']);
				}
				foreach ($servers as $map_location)
				{
					$kills = 0;
					$servers_js = array();
					foreach ($map_location['servers'] as $server)
					{
						$search_pattern = array("/[^A-Za-z0-9\[\]*.,=()!\"$%&^`´':;ß²³#+~_\-|<>\/@{}äöüÄÖÜ ]/");
						$replace_pattern = array("");
						$server['name'] = preg_replace($search_pattern, $replace_pattern, $server['name']);
						$temp = "[" . $server['serverId'] . ',';
						$temp .= "'" . htmlspecialchars(urldecode(preg_replace($search_pattern, $replace_pattern, $server['addr'])), ENT_QUOTES) . '\',';
						$temp .= "'" . htmlspecialchars(urldecode(preg_replace($search_pattern, $replace_pattern, $server['name'])), ENT_QUOTES) . '\']';
						$servers_js[] = $temp;
						$kills += $server['kills'];
					}
					echo 'createMarkerS(new GLatLng(' . $map_location['lat'] . ', ' . $map_location['lng'] . '), [' . implode(',', $servers_js) . '], "' . htmlspecialchars(urldecode($map_location['city']), ENT_QUOTES) . '", "' . htmlspecialchars(urldecode($map_location['country']), ENT_QUOTES) . '", ' . $kills . ");\n";
				}

				$data = array();
				$db->query("SELECT 
							hlstats_Livestats.* 
						FROM 
							hlstats_Livestats
						INNER JOIN    
							hlstats_Servers 
							ON (hlstats_Servers.serverId=hlstats_Livestats.server_id)
						WHERE 
							hlstats_Livestats.cli_lat IS NOT NULL 
							AND hlstats_Livestats.cli_lng IS NOT NULL
							AND hlstats_Servers.game='$game'
							");
				$players = array();
				while ($row = $db->fetch_array())
				{
					//Skip this part, if we already have the location info (should be the same)
					if (!isset($players[$row['cli_lat'] . ',' . $row['cli_lng']]))
					{
						$players[$row['cli_lat'] . ',' . $row['cli_lng']] = array('cli_lat' => $row['cli_lat'], 'cli_lng' => $row['cli_lng'], 'cli_city' => $row['cli_city'], 'cli_country' => $row['cli_country']);
					}
					$search_pattern = array("/[^A-Za-z0-9\[\]*.,=()!\"$%&^`´':;ß²³#+~_\-|<>\/@{}äöüÄÖÜ ]/");
					$replace_pattern = array("");
					$row['name'] = preg_replace($search_pattern, $replace_pattern, $row['name']);

					$players[$row['cli_lat'] . ',' . $row['cli_lng']]['players'][] = array('playerId' => $row['player_id'], 'name' => $row['name'], 'kills' => $row['kills'], 'deaths' => $row['deaths'], 'connected' => $row['connected']);
				}

				foreach ($players as $map_location)
				{
					$kills = 0;
					$players_js = array();
					foreach ($map_location['players'] as $player)
					{
						$stamp = time() - $player['connected'];
						$hours = sprintf("%02d", floor($stamp / 3600));
						$min = sprintf("%02d", floor(($stamp % 3600) / 60));
						$sec = sprintf("%02d", floor($stamp % 60));
						$time_str = $hours . ":" . $min . ":" . $sec;

						$temp = "[" . $player['playerId'] . ',';
						$temp .= "'" . htmlspecialchars(urldecode(preg_replace($search_pattern, $replace_pattern, $player['name'])), ENT_QUOTES) . "',";
						$temp .= $player['kills'] . ',';
						$temp .= $player['deaths'] . ',';
						$temp .= "'" . $time_str . "']";
						$players_js[] = $temp;
					}

					echo "createMarker(new GLatLng(" . $map_location['cli_lat'] . ", " . $map_location['cli_lng'] . "), \"" . htmlspecialchars(urldecode($map_location['cli_city']), ENT_QUOTES) . "\", \"" . htmlspecialchars(urldecode($map_location['cli_country']), ENT_QUOTES) . '", [' . implode(',', $players_js) . "]);\n";
				}
			} else if ($type == 'clan') {
				$db->query("
					SELECT
						playerId,
						lastName,
						country,
						skill,
						kills,
						deaths,
						lat,
						lng,
						city,
						country
					FROM
						hlstats_Players
					WHERE
						clan=$clan
						AND hlstats_Players.hideranking = 0
					GROUP BY
						hlstats_Players.playerId
				");
				$players = array();
				while ( $row = $db->fetch_array() )
				{
					//Skip this part, if we already have the location info (should be the same)
					if ( !isset($players[ $row['lat'] . ',' . $row['lng'] ]) )
					{
						$players[ $row['lat'] . ',' . $row['lng'] ] = array(
							'lat' => $row['lat'],
							'lng' => $row['lng'],
							'city' => $row['city'],
							'country' => $row['country']
						);
					}
					$search_pattern = array("/[^A-Za-z0-9\[\]*.,=()!\"$%&^`´':;ß²³#+~_\-|<>\/@{}äöüÄÖÜ ]/");
					$replace_pattern = array("");
					$row['name'] = preg_replace($search_pattern, $replace_pattern, $row['name']);
					
					$players[ $row['lat'] . ',' . $row['lng'] ]['players'][] = array(
						'playerId' => $row['playerId'],
						'name' => $row['lastName'],
						'kills' => $row['kills'],
						'deaths' => $row['deaths'],
						'connected' => $row['connected']
					);
				}
				
				foreach ( $players as $location )
				{
					$kills = 0;
					$players_js = array();
					foreach ( $location['players'] as $player )
					{
						$temp = "[" .  $player['playerId'] . ',';
						$temp .= "'" . htmlspecialchars(urldecode(preg_replace($search_pattern, $replace_pattern, $player['name'])), ENT_QUOTES) . "',";
						$temp .= $player['kills'] . ',';
						$temp .= $player['deaths'] . ']';
						$players_js[] = $temp;
					}
					
					echo "createMarker(new GLatLng(" . $location['lat'] . ", " . $location['lng'] . "), \"" . htmlspecialchars(urldecode($location['city']), ENT_QUOTES) . "\", \"" . htmlspecialchars(urldecode($location['country']), ENT_QUOTES) . "\", [" . implode(",", $players_js) . "]);\n";
				}
			}
?>
		/* ]]> */
	</script>
<?php
}

function printMapCenter($country)
{
	switch (strtoupper($country))
	{
		case 'EUROPE':
			echo 'map.setCenter(new GLatLng(48.8, 8.5),     4);';
			break;
		case 'NORTH AMERICA':
			echo 'map.setCenter(new GLatLng(45.0, -97.0),   3);';
			break;
		case 'SOUTH AMERICA':
			echo 'map.setCenter(new GLatLng(-14.8, -61.2),  3);';
			break;
		case 'NORTH AFRICA':
			echo 'map.setCenter(new GLatLng(25.4, 8.4),     4);';
			break;
		case 'SOUTH AFRICA':
			echo 'map.setCenter(new GLatLng(-29.0, 23.7),   5);';
			break;
		case 'NORTH EUROPE':
			echo 'map.setCenter(new GLatLng(62.6, 15.4),    4);';
			break;
		case 'EAST EUROPE':
			echo 'map.setCenter(new GLatLng(51.9, 31.8),    4);';
			break;
		case 'GERMANY':
			echo 'map.setCenter(new GLatLng(51.1, 10.1),    5);';
			break;
		case 'FRANCE':
			echo 'map.setCenter(new GLatLng(47.2, 2.4),     5);';
			break;
		case 'SPAIN':
			echo 'map.setCenter(new GLatLng(40.3, -4.0),    5);';
			break;
		case 'UNITED KINGDOM':
			echo 'map.setCenter(new GLatLng(54.0, -4.3),    5);';
			break;
		case 'DENMARK':
			echo 'map.setCenter(new GLatLng(56.1, 9.2),     6);';
			break;
		case 'SWEDEN':
			echo 'map.setCenter(new GLatLng(63.2, 16.3),    4);';
			break;
		case 'NORWAY':
			echo 'map.setCenter(new GLatLng(65.6, 13.1),    4);';
			break;
		case 'FINLAND':
			echo 'map.setCenter(new GLatLng(65.1, 26.6),    4);';
			break;
		case 'NETHERLANDS':
			echo 'map.setCenter(new GLatLng(52.3, 5.4),     7);';
			break;
		case 'BELGIUM':
			echo 'map.setCenter(new GLatLng(50.7, 4.5),     7);';
			break;
		case 'SUISSE':
			echo 'map.setCenter(new GLatLng(46.8, 8.2),     7);';
			break;
		case 'AUSTRIA':
			echo 'map.setCenter(new GLatLng(47.7, 14.1),    7);';
			break;
		case 'POLAND':
			echo 'map.setCenter(new GLatLng(52.1, 19.3),    6);';
			break;
		case 'ITALY':
			echo 'map.setCenter(new GLatLng(42.6, 12.7),    5);';
			break;
		case 'TURKEY':
			echo 'map.setCenter(new GLatLng(39.0, 34.9),    6);';
			break;
		case 'ROMANIA':
			echo 'map.setCenter(new GLatLng(45.94, 24.96),	6);';
			break;
		case 'BRAZIL':
			echo 'map.setCenter(new GLatLng(-12.0, -53.1),  4);';
			break;
		case 'ARGENTINA':
			echo 'map.setCenter(new GLatLng(-34.3, -65.7),  3);';
			break;
		case 'RUSSIA':
			echo 'map.setCenter(new GLatLng(65.7, 98.8),    3);';
			break;
		case 'ASIA':
			echo 'map.setCenter(new GLatLng(20.4, 95.6),    3);';
			break;
		case 'CHINA':
			echo 'map.setCenter(new GLatLng(36.2, 104.0),   4);';
			break;
		case 'JAPAN':
			echo 'map.setCenter(new GLatLng(36.2, 136.8),   5);';
			break;
		case 'SOUTH KOREA':
			echo 'map.setCenter(new GLatLng(36.6, 127.8),   6);';
			break;
		case 'AUSTRALIA':
			echo 'map.setCenter(new GLatLng(-26.1, 134.8),  4);';
			break;
		case 'CANADA':
			echo 'map.setCenter(new GLatLng(60.0, -97.0),   3);';
			break;
		case 'WORLD':
			echo 'map.setCenter(new GLatLng(25.0, 8.5),     2);';
			break;
		default:
			echo 'map.setCenter(new GLatLng(48.8, 8.5),     4);';
			break;
	}
	echo "\n";
}

function printMapType($maptype)
{
	switch (strtoupper($maptype))
	{
		case 'SATELLITE':
			echo 'map.setMapType(G_SATELLITE_MAP);';
			break;
		case 'MAP':
			echo 'map.setMapType(G_NORMAL_MAP);';
			break;
		case 'HYBRID':
			echo 'map.setMapType(G_HYBRID_MAP);';
			break;
		case 'PHYSICAL':
			echo 'map.setMapType(G_PHYSICAL_MAP);';
			break;
		default:
			echo 'map.setMapType(G_HYBRID_MAP);';
			break;
	}
	echo "\n";
}
?>
