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

Originally idea for sig.php by Tankster
*/

foreach ($_SERVER as $key => $entry) {
	if ($key !== 'HTTP_COOKIE') {
		$search_pattern  = array('/<script>/', '/<\/script>/', '/[^A-Za-z0-9.\-\/=:;_?#&~]/');
		$replace_pattern = array('', '', '');
		$entry = preg_replace($search_pattern, $replace_pattern, $entry);
  
		if ($key == 'PHP_SELF') {
			if ((strrchr($entry, '/') !== '/hlstats.php') &&
				(strrchr($entry, '/') !== '/ingame.php') &&
				(strrchr($entry, '/') !== '/show_graph.php') &&
				(strrchr($entry, '/') !== '/sig.php') &&
				(strrchr($entry, '/') !== '/sig2.php') &&
				(strrchr($entry, '/') !== '/index.php') &&
				(strrchr($entry, '/') !== '/status.php') &&
				(strrchr($entry, '/') !== '/top10.php') &&
				(strrchr($entry, '/') !== '/config.php') &&
				(strrchr($entry, '/') !== '/') &&
				($entry !== '')) {
				header('Location: http://'.$_SERVER['HTTP_HOST'].'/hlstats.php');    
				exit;
			}    
		}
		$_SERVER[$key] = $entry;
	}
}
  
define('IN_HLSTATS', true);
header("Content-Type: image/png");

// Load database classes
require ('config.php');
require (INCLUDE_PATH . '/class_db.php');
require (INCLUDE_PATH . '/functions.php');

$db_classname = 'DB_' . DB_TYPE;
if (class_exists($db_classname))
{
	$db = new $db_classname(DB_ADDR, DB_USER, DB_PASS, DB_NAME, DB_PCONNECT);
}
else
{
	error('Database class does not exist.  Please check your config.php file for DB_TYPE');
}

$g_options = getOptions();

@error_reporting(E_ALL ^ E_NOTICE);

function f_num($number) {
	if (($number >= 10) &&($number < 20))
		return $number.'th';
	else {
		switch ($number % 10) {
			case 1:
				return $number.'st';
				break;
			case 2:
				return $number.'nd';
				break;
			case 3:
				return $number.'rd';
				break;
			default:
				return $number.'th';
				break;
		}
	}
}

if (!$g_options['scripturl'])
	$g_options['scripturl'] = $PHP_SELF;
	$g_options['scripturl'] = str_replace('/status.php', '', $g_options['scripturl']);

	$player_id = 0;  
	if ((isset($_GET['player_id'])) && (is_numeric($_GET['player_id'])))
		$player_id = valid_request($_GET['player_id'], 1);
	$show_flags = $g_options['countrydata'];
	if ((isset($_GET['show_flags'])) && (is_numeric($_GET['show_flags'])))
		$show_flags = valid_request($_GET['show_flags'], 1);



	if (file_exists(IMAGE_PATH.'/progress/sig_'.$player_id.'.png')) {
		$file_timestamp = @filemtime(IMAGE_PATH.'/progress/sig_'.$player_id.'.png');
		if ($file_timestamp + IMAGE_UPDATE_INTERVAL > time()) {
			if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
				$browser_timestamp = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
				if ($browser_timestamp + IMAGE_UPDATE_INTERVAL > time()) {
					header('HTTP/1.0 304 Not Modified');
					exit; 
				}
			}

			$mod_date = date('D, d M Y H:i:s \G\M\T', $file_timestamp);
			header('Last-Modified:'.$mod_date);
			exit;
		}  
	}

	////
	//// Main
	////


/** 
  * Convert colors Usage:  color::hex2rgb("FFFFFF")
  * 
  * @author      Tim Johannessen <root@it.dk>
  * @version    1.0.1
*/
function hex2rgb($hexVal = '') { 
	$hexVal = preg_replace('/[^a-fA-F0-9]/i', '', $hexVal); 
	if (strlen($hexVal) != 6) { return 'ERR: Incorrect colorcode, expecting 6 chars (a-f, 0-9)'; } 
	$arrTmp = explode(' ', chunk_split($hexVal, 2, ' ')); 
	$arrTmp = array_map('hexdec', $arrTmp); 
	return array('red' => $arrTmp[0], 'green' => $arrTmp[1], 'blue' => $arrTmp[2]); 
}

if ((isset($_GET['color'])) && (is_string($_GET['color'])))
	$color = hex2rgb(valid_request($_GET['color'], 0));
if ((isset($_GET['caption_color'])) && (is_string($_GET['caption_color'])))
	$caption_color = hex2rgb(valid_request($_GET['caption_color'], 0));
if ((isset($_GET['link_color'])) && (is_string($_GET['link_color'])))
	$link_color = hex2rgb(valid_request($_GET['link_color'], 0));
  
if ($player_id > 0) {
	$db->query("
		SELECT
			playerId, 
			game, 
			FROM_UNIXTIME((last_event), '%a %D %b %k:%H') as lastevent, 
			connection_time,
			last_skill_change,
			lastName,
			country,
			flag,
			kills, 
			deaths, 
			suicides, 
			skill, 
			shots, 
			hits, 
			headshots, IFNULL(ROUND(headshots/kills * 100), '-') AS hpk, 
			IFNULL(kills/deaths, '-') AS kpd, 
			IFNULL(ROUND((hits / shots * 100), 1), 0.0) AS acc, 
			activity, 
			hideranking 
		FROM 
			hlstats_Players 
		WHERE 
			playerId='$player_id'
	");
	if ($db->num_rows() != 1)
		error("No such player '$player'.");

	$playerdata = $db->fetch_array();
	$db->free_result();

	$pl_name = $playerdata['lastName'];
    
	if(function_exists(imagettftext)) {
		if (strlen($pl_name) > 30) {
			$pl_name = substr($pl_name, 0, 27) . '...';
		}
	} else {
		if (strlen($pl_name) > 30) {
			$pl_shortname =	substr($pl_name, 0, 27) . '...';
		} else {
			$pl_shortname	= $pl_name;
			$pl_name		= htmlspecialchars($pl_name, ENT_COMPAT);
			$pl_shortname	= htmlspecialchars($pl_shortname, ENT_COMPAT);
			$pl_urlname		= urlencode($playerdata['lastName']);
		}
	}

	$db->query("
		SELECT
			COUNT(*) as count
		FROM
			hlstats_Players
		WHERE
			game='".$playerdata['game']."'");
	$pl_count = $db->fetch_array();
	$db->free_result();

	if (($playerdata['activity'] > 0) && ($playerdata['hideranking'] == 0)) {
		$rank = get_player_rank($playerdata);
	} else {
		if ($playerdata['hideranking'] == 1)
			$rank = 'Hidden';
		elseif ($playerdata['hideranking'] == 2)
			$rank = 'Banned';
		else
			$rank = 'Not active';
	}

	if ($playerdata['activity'] == -1)
		$playerdata['activity'] = 0;

	$skill_change = '0';
	if ($playerdata['last_skill_change'] > 0)
		$skill_change = $playerdata['last_skill_change'];
	else if ($playerdata['last_skill_change'] < 0)
		$skill_change = $playerdata['last_skill_change'];  
	
	$background='random';
	if ((isset($_GET['background'])) && ( (($_GET['background'] > 0) && ($_GET['background'] < 12)) || ($_GET['background']=='random')) )
		$background = valid_request($_GET['background'], 0);

	if ($background == 'random')
		$background = rand(1,11);
	
	$hlx_sig_image = getImage('/games/'.$playerdata['game'].'/sig/'.$background);
	if ($hlx_sig_image)
	{
		$hlx_sig = $hlx_sig_image['path'];
	}
	else
	{
		$hlx_sig = IMAGE_PATH."/sig/$background.png";
	}

	switch ($background) {
		case 1:		$caption_color = array('red' => 0, 'green' => 0, 'blue' => 255);
					$link_color = array('red' => 0, 'green' => 155, 'blue' => 0);
					$color = array('red' => 0, 'green' => 0, 'blue' => 0);
				$caption_colorb = array('red' => 255, 'green' => 255, 'blue' => 255);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 255, 'green' => 255, 'blue' => 255);
					break;
		case 2:		$caption_color = array('red' => 147, 'green' => 23, 'blue' => 18);
					$link_color = array('red' => 147, 'green' => 23, 'blue' => 18);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 3:		$caption_color = array('red' => 150, 'green' => 180, 'blue' => 99);
					$link_color = array('red' => 150, 'green' => 180, 'blue' => 99);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 4:		$caption_color = array('red' => 255, 'green' => 203, 'blue' => 4);
					$link_color = array('red' => 255, 'green' => 203, 'blue' => 4);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 5:		$caption_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$link_color = array('red' => 0, 'green' => 102, 'blue' => 204);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 6:		$caption_color = array('red' => 103, 'green' => 103, 'blue' => 103);
					$link_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 7:		$caption_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$link_color = array('red' => 100, 'green' => 100, 'blue' => 100);
					$color = array('red' => 0, 'green' => 0, 'blue' => 0);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 255, 'green' => 255, 'blue' => 255);
					$colorb = array('red' => 255, 'green' => 255, 'blue' => 255);
					break;
		case 8:		$caption_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$link_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 9:		$caption_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$link_color = array('red' => 0, 'green' => 0, 'blue' => 0);
					$color = array('red' => 0, 'green' => 0, 'blue' => 0);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 255, 'green' => 255, 'blue' => 255);
					$colorb = array('red' => 255, 'green' => 255, 'blue' => 255);
					break;
		case 10:	$caption_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$link_color = array('red' => 255, 'green' => 255, 'blue' => 255);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		case 11:	$caption_color = array('red' => 150, 'green' => 180, 'blue' => 99);
					$link_color = array('red' => 150, 'green' => 180, 'blue' => 99);
					$color = array('red' => 255, 'green' => 255, 'blue' => 255);
				$caption_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$link_colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					$colorb = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
		default:	$caption_color = array('red' => 0, 'green' => 0, 'blue' => 255);
					$link_color = array('red' => 0, 'green' => 155, 'blue' => 0);
					$color = array('red' => 0, 'green' => 0, 'blue' => 0);
					break;
}

	$image			= imagecreatetruecolor(427, 102);

        imagealphablending($image, false);
        imagesavealpha($image, true);

	$white			= imagecolorallocate($image, 255, 255, 255); 
	$bgray			= imagecolorallocate($image, 192, 192, 192); 
	$yellow			= imagecolorallocate($image, 255, 255,   0); 
	$black			= imagecolorallocate($image,   0,   0,   0); 
	$red			= imagecolorallocate($image, 255,   0,   0); 
	$green			= imagecolorallocate($image,   0, 155,   0); 
	$blue			= imagecolorallocate($image,   0,   0, 255); 
	$grey_shade		= imagecolorallocate($image, 204, 204, 204); 
	$font_color		= imagecolorallocate($image, $color['red'], $color['green'], $color['blue']);
	$caption_color	= imagecolorallocate($image, $caption_color['red'], $caption_color['green'], $caption_color['blue']);
	$link_color		= imagecolorallocate($image, $link_color['red'], $link_color['green'], $link_color['blue']);
	$font_colorb		= imagecolorallocate($image, $colorb['red'], $colorb['green'], $colorb['blue']);
	$caption_colorb	= imagecolorallocate($image, $caption_colorb['red'], $caption_colorb['green'], $caption_colorb['blue']);
	$link_colorb		= imagecolorallocate($image, $link_colorb['red'], $link_colorb['green'], $link_colorb['blue']);


	$background_img = imagecreatefrompng($hlx_sig);



	if ($background_img) {
		imagecopy($image, $background_img, 0, 0, 0, 0, 427, 102);
		imagedestroy($background_img);
	}   

	if ($background == 1)
		imagerectangle($image, 0, 0, 399, 74, $bgray);

	$start_header_name = 8;
	if ($show_flags > 0)  {
		$flag = imagecreatefromgif(getFlag($playerdata['flag'], 'path'));
		if ($flag) {
			imagecopy($image, $flag, 8, 4, 0, 0, 18, 12); 
			$start_header_name += 22;
			imagedestroy($flag);
		}
	}
        imagealphablending($image, true);
	$timestamp   = $playerdata['connection_time'];
	$days        = floor($timestamp / 86400);
	$hours       = $days * 24;   
	$hours       += floor($timestamp / 3600 % 24);
	if ($hours < 10)
		$hours = '0'.$hours; 
	$min         = floor($timestamp / 60 % 60); 
	if ($min < 10)
		$min = '0'.$min; 
	$sec         = floor($timestamp % 60);
	if ($sec < 10)
		$sec = '0'.$sec; 
	$con_time = $hours.':'.$min.':'.$sec;

	if ($playerdata['last_skill_change'] == '')
		$playerdata['last_skill_change'] = 0;
	if ($playerdata['last_skill_change'] == 0)
		$trend_image_name = IMAGE_PATH.'/t1.gif';
	elseif ($playerdata['last_skill_change'] > 0)
		$trend_image_name = IMAGE_PATH.'/t0.gif';
	elseif ($playerdata['last_skill_change'] < 0)
		$trend_image_name = IMAGE_PATH.'/t2.gif';
	$trend = imagecreatefromgif($trend_image_name);
    
	if(function_exists(imagettftext))
	{
		$font = IMAGE_PATH.'/sig/font/DejaVuSans.ttf';
		imagettftext($image, 9, 0, 31, 14, $caption_colorb, $font, $pl_name);
		imagettftext($image, 9, 0, 30, 14, $caption_color, $font, $pl_name);
	}
	else
	{
		imagestring($image, 9, $start_header_name, 3, $playerdata['lastName'], $caption_colorb);
		imagestring($image, 9, $start_header_name, 2, $playerdata['lastName'], $caption_color);
	}

	imagestring($image, 2, 16, 24, 'Position ', $font_colorb);
	imagestring($image, 2, 15, 23, 'Position ', $font_color);
	if (is_numeric($rank)) {
		imagestring($image, 3, 71, 24, number_format($rank), $font_colorb);
		imagestring($image, 3, 70, 23, number_format($rank), $font_color);
		$start_pos_x = 71 + (imagefontwidth(3) * strlen(number_format($rank))) + 7;
	} else {
		imagestring($image, 3, 71, 24, $rank, $font_colorb);
		imagestring($image, 3, 70, 23, $rank, $font_color);
		$start_pos_x = 71 + (imagefontwidth(3) * strlen($rank)) + 7;
	}
	imagestring($image, 2, $start_pos_x, 24, 'of '.$pl_count['count'].' players with '.$playerdata['skill']." (", $font_colorb);
	imagestring($image, 2, $start_pos_x, 23, 'of '.$pl_count['count'].' players with '.$playerdata['skill']." (", $font_color);
	$start_pos_x += (imagefontwidth(2) * strlen('of '.$pl_count['count'].' players with '.$playerdata['skill'].' ('));
	if ($trend) {
		imagecopy($image, $trend, $start_pos_x, 26, 0, 0, 7, 7);
		$start_header_name += 22;
		imagedestroy($trend);
		$start_pos_x += 10;
	}
	imagestring($image, 2, $start_pos_x, 24, $skill_change.') points', $font_colorb);
	imagestring($image, 2, $start_pos_x, 23, $skill_change.') points', $font_color);
	imagestring($image, 2,  16, 36, 'Frags: '.$playerdata['kills'].' kills : '.$playerdata['deaths'].' deaths ('.$playerdata['kpd'].'), '.$playerdata['headshots'].' headshots ('.$playerdata['hpk'].'%)', $font_colorb);
	imagestring($image, 2,  15, 35, 'Frags: '.$playerdata['kills'].' kills : '.$playerdata['deaths'].' deaths ('.$playerdata['kpd'].'), '.$playerdata['headshots'].' headshots ('.$playerdata['hpk'].'%)', $font_color);
	imagestring($image, 2,  16, 47, 'Activity: '.$playerdata['lastevent'].' ('.$playerdata['activity'].'%), Time: '.$con_time.' hours', $font_colorb);
	imagestring($image, 2,  15, 46, 'Activity: '.$playerdata['lastevent'].' ('.$playerdata['activity'].'%), Time: '.$con_time.' hours', $font_color);
	imagestring($image, 2,  16, 58, 'Statistics: ', $font_colorb);imagestring($image, 3,  85, 58, $g_options['siteurl'], $link_colorb);
	imagestring($image, 2,  15, 57, 'Statistics: ', $font_color);imagestring($image, 3,  85, 57, $g_options['siteurl'], $link_color);

	$mod_date = date('D, d M Y H:i:s \G\M\T', time());
	Header('Last-Modified:'.$mod_date);

	imagepng($image);
	imagedestroy($image);	

}   
?>
