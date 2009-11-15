package HLstats_Player;
# HLstatsX Community Edition - Real-time player and clan rankings and statistics
# Copyleft (L) 2008-20XX Nicholas Hastings (nshastings@gmail.com)
# http://www.hlxcommunity.com
#
# HLstatsX Community Edition is a continuation of 
# ELstatsNEO - Real-time player and clan rankings and statistics
# Copyleft (L) 2008-20XX Malte Bayer (steam@neo-soft.org)
# http://ovrsized.neo-soft.org/
# 
# ELstatsNEO is an very improved & enhanced - so called Ultra-Humongus Edition of HLstatsX
# HLstatsX - Real-time player and clan rankings and statistics for Half-Life 2
# http://www.hlstatsx.com/
# Copyright (C) 2005-2007 Tobias Oetzel (Tobi@hlstatsx.com)
#
# HLstatsX is an enhanced version of HLstats made by Simon Garner
# HLstats - Real-time player and clan rankings and statistics for Half-Life
# http://sourceforge.net/projects/hlstats/
# Copyright (C) 2001  Simon Garner
#             
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
# 
# For support and installation notes visit http://www.hlxcommunity.com

#
# Constructor
#

use Encode;

sub new
{
	my $class_name = shift;
	my %params = @_;
	
	my $self = {};
	bless($self, $class_name);
	
	# Initialise Properties
	$self->{userid}            = 0;
	$self->{server}            = "";
	$self->{server_id}         = 1;
	$self->{name}              = "";
	$self->{uniqueid}          = "";
	$self->{plain_uniqueid}    = "";
	$self->{address}           = "";
	$self->{cli_port}          = "";
	$self->{ping}              = 0;
	$self->{connect_time}      = time();
	$self->{last_update}       = 0;
	$self->{last_update_skill} = 0;
	$self->{day_skill_change}  = 0;
	$self->{last_address_check} = 0;

	$self->{city}              = undef;
	$self->{state}             = undef;
	$self->{country}           = undef;
	$self->{flag}              = undef;
	$self->{lat}               = undef;
	$self->{lng}               = undef;
	
	$self->{playerid}          = 0;
	$self->{clan}              = 0;
	$self->{kills}             = 0;
	$self->{total_kills}       = 0;
	$self->{deaths}            = 0;
	$self->{suicides}          = 0;
	$self->{skill}             = 1000;
	$self->{game}              = "";
	$self->{team}              = "";
	$self->{role}              = "";
	$self->{timestamp}         = 0;
	$self->{headshots}         = 0;
	$self->{shots}             = 0;
	$self->{hits}              = 0;
	
	$self->{auto_command}      = "";
	$self->{auto_type}         = "";
	$self->{auto_time}         = 0;
	$self->{auto_time_count}   = 0;
	
	$self->{session_skill}     = 0;
	$self->{session_kills}     = 0;
	$self->{session_deaths}    = 0;
	$self->{session_suicides}  = 0;
	$self->{session_headshots} = 0;
	$self->{session_shots}     = 0;
	$self->{session_hits}      = 0;
	$self->{session_start_pos} = -1;

	$self->{map_kills}         = 0;
	$self->{map_deaths}        = 0;
	$self->{map_suicides}      = 0;
	$self->{map_headshots}     = 0;
	$self->{map_shots}         = 0;
	$self->{map_hits}          = 0;
	$self->{is_dead}           = 0;
	$self->{has_bomb}          = 0;
	
	$self->{is_banned}         = 0;
	$self->{is_bot}            = 0;

	$self->{display_events}    = 1;
	$self->{display_chat}      = 1;
	$self->{kills_per_life}	   = 0;
	$self->{last_history_day}  = "";
	$self->{last_death_weapon}  = 0;
	$self->{last_sg_build}  = 0;
	$self->{last_disp_build}  = 0;
	$self->{last_entrance_build}  = 0;
	$self->{last_exit_build}  = 0;
	$self->{last_team_change}  = "";
	$self->{deaths_in_a_row}   = 0;
	$self->{kill_streak}	   = 0;
	$self->{death_streak}	   = 0;
	
	
	# Set Property Values
	
	die("HLstats_Player->new(): must specify player's uniqueid\n")
		unless (defined($params{uniqueid}));
	

	while (my($key, $value) = each(%params))
	{
		if ($key ne "name" && $key ne "uniqueid")
		{
			$self->set($key, $value);
		}
	}

	$self->updateTrackable();
	$self->{plain_uniqueid} = $params{plain_uniqueid};
	$self->setUniqueId($params{uniqueid});
	$self->setName($params{name});
	
	$self->flushDB();



	&::printNotice("Created new player object " . $self->getInfoString());
	return $self;
}

sub playerCleanup
{
	my ($self) = @_;
	$self->flushDB();
	$self->deleteLivestats();
}


#
# Set property 'key' to 'value'
#

sub set
{
	my ($self, $key, $value, $no_updatetime) = @_;
	
	if (defined($self->{$key}))
	{
		if ($no_updatetime == 0) {
			$self->{timestamp} = $::ev_unixtime;
		}
		
		if ($self->{$key} eq $value)
		{
			if ($::g_debug > 2)
			{
				&::printNotice("Hlstats_Player->set ignored: Value of \"$key\" is already \"$value\"");
			}
			return 0;
		}
		
		if ($key eq "uniqueid")
		{
			return $self->setUniqueId($value);
		}
		elsif ($key eq "name")
		{
			return $self->setName($value);
		}
		elsif ($key eq "skill" && $self->{userid} < 1)
		{
			return $self->{skill};
		}
		else
		{
			$self->{$key} = $value;
			return 1;
		}
	}
	else
	{
		warn("HLstats_Player->set: \"$key\" is not a valid property name\n");
		return 0;
	}
}


#
# Increment (or decrement) the value of 'key' by 'amount' (or 1 by default)
#

sub increment
{
	my ($self, $key, $amount, $no_updatetime) = @_;
	
	if ($key eq "skill" && $self->{userid} < 1) {
		return $self->{skill};
	}
	
	$amount = 1 if (!defined($amount));
	
	if ($amount != 0) {	
		my $value = $self->{$key};
		$self->set($key, $value + $amount, $no_updatetime);
	}
}


sub check_history 
{
	my ($self, $playerId, $is_bot) = @_;

    #my ($sec,$min,$hour,$mday,$mon,$year) = localtime(time());
    my ($sec,$min,$hour,$mday,$mon,$year) = localtime($::ev_unixtime);
    my $date = sprintf("%04d-%02d-%02d", $year+1900, $mon+1, $mday, $hour, $min, $sec);
	my $srv_addr  = $self->{server};
	
	if (!$is_bot) {
		$is_bot = 0;
	}  
	if (($playerId > 0) && ($::g_stdin == 0 || $::g_timestamp > 0))
	{
		if (($is_bot == 0) || (($is_bot == 1) && ($::g_servers{$srv_addr}->{ignore_bots} == 0))) {
			$self->{last_history_day} = sprintf("%02d", $mday);
			my $query = "
				SELECT
					playerId, skill_change
				FROM
					hlstats_Players_History
				WHERE
					playerId='" . $playerId . "'
					AND eventTime='".$date."'
					AND game='".&::quoteSQL($::g_servers{$srv_addr}->{game})."'
			";
			my $result = &::doQuery($query);
		
			if ($result->rows < 1)
			{
				my $query = "
					INSERT INTO
						hlstats_Players_History
						(		
							playerId,
							eventTime,
							game
						)
					VALUES
						(
							$playerId,
							'$date',
							'" . &::quoteSQL($::g_servers{$srv_addr}->{game}) . "'
						)
				";
				&::execNonQuery($query);
				$self->{day_skill_change}     = 0;
			} else {
				my ($playerid, $skill_change) = $result->fetchrow_array;
				$self->{day_skill_change}     = $skill_change;
			}
			$result->finish;
		}  
	}	
}


#
# Set player's uniqueid
#

sub setUniqueId
{
	my ($self, $uniqueid) = @_;
	
	my $playerid = &::getPlayerId($uniqueid);
	
	if ($playerid)
	{
		my $lat = undef;
		my $lng = undef;
		
    	# An existing player. Get their skill rating.
		my $query = "
			SELECT
				skill, kills, city, state, country, flag, lat, lng, displayEvents, kill_streak, death_streak
			FROM
				hlstats_Players
			WHERE
				playerId='$playerid'
		";
		my $result = &::doQuery($query);
		($self->{skill}, $self->{total_kills}, $self->{city}, $self->{state}, $self->{country}, $self->{flag}, $lat, $lng, $self->{display_events},$self->{kill_streak},$self->{death_streak}) = $result->fetchrow_array;
		$result->finish;
     	$self->{playerid} = $playerid;
		$self->{lat} = (($lat eq "")?undef:$lat);
		$self->{lng} = (($lng eq "")?undef:$lng);
        $self->{session_start_pos} = $self->getRank();
	}
	else
	{
     	my $srv_addr  = $self->{server};
		# This is a new player. Create a new record for them in the Players
		# table.
		my $hideins = "";
		my $hideval = "";
		if ($::g_servers{$srv_addr}->{play_game} eq "l4d" && $self->{userid} < 0) {
			$hideins = ", hideranking";
			$hideval = ", 1";
		}
		my $query = "
			INSERT INTO
				hlstats_Players
				(
					lastName,
					clan,
					game,
					displayEvents,
					createdate
					$hideins
				)
			VALUES
			(
				'" . &::quoteSQL($self->{name}) . "',
				" . $self->{clan} . ",
				'" . &::quoteSQL($::g_servers{$srv_addr}->{game}) . "',
				" . $self->{display_events} . ",
				UNIX_TIMESTAMP()
				$hideval
			)
		";
		&::execNonQuery($query);
		
		($playerid) = $::db_conn->{'mysql_insertid'};
		
		if ($playerid)
		{
			$query = "
				INSERT INTO
					hlstats_PlayerUniqueIds
					(
						playerId,
						uniqueId,
						game
					)
				VALUES
				(
					$playerid,
					'" . &::quoteSQL($uniqueid) . "',
					'" . &::quoteSQL($::g_servers{$srv_addr}->{game}) . "'
				)
			";
			&::execNonQuery($query);
		}
		else
		{
			error("Unable to create player:\n$query");
		}
	}
	
	$self->{uniqueid} = $uniqueid;
	$self->{playerid} = $playerid;

	if ($playerid > 0) {
		$self->check_history($playerid);
	}
	
	return 1;
}



#
# Set player's name
#

sub setName
{
	my ($self, $name) = @_;
	
	my $oldname = $self->{name};

	if ($oldname eq $name)
	{
		return 2;
	}
	
	if ($oldname)
	{
		$self->updateDB();
	}
	
	$self->{name} = $name;

	my $is_bot = $self->{is_bot};
    my $server_address = $self->{server};
	if (($is_bot == 1) && ($::g_servers{$server_address}->{ignore_bots} == 1)) {
		$self->{clan} = "";
	} else {
		$self->{clan} = &::getClanId($name);
  	}  
	
	my $playerid = $self->{playerid};
	
	if ($playerid)
	{
		my $query = "
			SELECT
				playerId
			FROM
				hlstats_PlayerNames
			WHERE
				playerId = $playerid
				AND name ='" . &::quoteSQL($self->{name}) . "'
		";
		my $result = &::doQuery($query);
		
		if ($result->rows < 1)
		{
			my $query = "
				REPLACE INTO
					hlstats_PlayerNames
					(
						playerId,
						name,
						lastuse,
						numuses
					)
				VALUES
				(
					$playerid,
					'" . &::quoteSQL($self->{name}) . "',
					FROM_UNIXTIME(" . $::ev_unixtime . "),
					1
				)
			";
			&::execNonQuery($query);
		}
		else
		{
			my $query = "
				UPDATE
					hlstats_PlayerNames
				SET
					lastuse=FROM_UNIXTIME(" . $::ev_unixtime . "),
					numuses=numuses+1
				WHERE
					playerId = $playerid
					AND name='" . &::quoteSQL($self->{name}) . "'
			";
			&::execNonQuery($query);
		}
		
		$result->finish;
	}
	else
	{
		&::error("HLstats_Player->setName(): No playerid");
	}
}



#
# Update player information in database
#

sub flushDB
{
	my ($self, $leaveLastUse, $callref) = @_;
	
	my $playerid  = $self->{playerid};
	my $srv_addr  = $self->{server};
	my $serverid  = $self->{server_id};
	my $name      = $self->{name};
	my $clan      = $self->{clan};
	my $kills     = $self->{kills};
	my $deaths    = $self->{deaths};
	my $suicides  = $self->{suicides};
	my $skill     = $self->{skill};
	if ($skill < 0) {$skill = 0;}
	my $headshots = $self->{headshots};
	my $shots     = $self->{shots};
	my $hits      = $self->{hits};
	
	my $team          = $self->{team};
	my $map_kills     = $self->{map_kills};
	my $map_deaths    = $self->{map_deaths};
	my $map_suicides  = $self->{map_suicides};
	my $map_headshots = $self->{map_headshots};
	my $map_shots     = $self->{map_shots};
	my $map_hits      = $self->{map_hits};
	my $steamid       = $self->{plain_uniqueid};
	
	my $is_dead       = $self->{is_dead};
	my $has_bomb      = $self->{has_bomb};
	my $ping          = $self->{ping};
	my $connected     = $self->{connect_time};
	my $skill_change  = $self->{session_skill};
	
	my $death_streak  = $self->{death_streak};
	my $kill_streak   = $self->{kill_streak};
	
    my $add_connect_time = 0;
	if (($::g_stdin == 0) && ($self->{last_update} > 0))  {
		$add_connect_time = time() - $self->{last_update};
	} elsif (($::g_stdin == 1) && ($self->{last_update} > 0))  {
		$add_connect_time = $::ev_unixtime - $self->{last_update};
	} 
	if (($::g_stdin == 1) && ($add_connect_time > 600))  {
		$self->{last_update} = $::ev_unixtime;
		$add_connect_time = 0;
	} 
	
    $self->getAddress();
	my $address = $self->{address};
	
	unless ($playerid)
	{
		warn ("Player->Update() with no playerid set!\n");
		return 0;
	}
	
	if (($::g_stdin == 0) && ($self->{session_start_pos} == 0)) {
		$self->{session_start_pos} = $self->getRank();
    }

	# TAG - review this, should probably be localtime($ev_unixtime);
	# and why no Players_History if stdin?
	#my ($sec,$min,$hour,$mday,$mon,$year) = localtime(time());
	my ($sec,$min,$hour,$mday,$mon,$year) = localtime($::ev_unixtime);
    my $date = sprintf("%04d-%02d-%02d", $year+1900, $mon+1, $mday, $hour, $min, $sec);

	if ($::g_stdin == 0 || $::g_timestamp > 0) {
		my $last_history_day = $self->{last_history_day};
		if ($last_history_day ne sprintf("%02d", $mday)) {
			my $query = "
				INSERT IGNORE INTO
					hlstats_Players_History
					(  
						playerId,
						eventTime,
						game
					) VALUES (
						$playerid,
						'$date',
						'" . &::quoteSQL($::g_servers{$srv_addr}->{game}) . "'
					)
			";
			&::execNonQuery($query);
			$self->{day_skill_change} = 0;
			$self->{last_history_day} = sprintf("%02d", $mday);
		}
	}
    
    my $add_history_skill = 0;
	if ($self->{last_update_skill} > 0)  {
	  $add_history_skill = $skill - $self->{last_update_skill};
	} 
    $self->{day_skill_change} += $add_history_skill;
    my $last_skill_change = $self->{day_skill_change};


	my $is_bot = $self->{is_bot};
    my $server_address = $self->{server};
	if (($is_bot == 1) && ($::g_servers{$server_address}->{ignore_bots} == 1)) {
		# Update player details
		my $query = "
			UPDATE
				hlstats_Players
			SET
				connection_time = connection_time + $add_connect_time,
				lastName='" . &::quoteSQL($name) . "',
				clan=0,
				kills=kills + $kills,
				deaths=deaths + $deaths,
				suicides=suicides + $suicides,
				skill=0,
				headshots=headshots + $headshots,
				shots=shots + $shots,
				hits=hits + $hits,
				last_event=".$::ev_unixtime.",
				hideranking=1
			WHERE
				playerId=$playerid
		";
		&::execNonQuery($query);
	} else {
		# Update player details
		my $query = "
			UPDATE
				hlstats_Players
			SET
				connection_time = connection_time + $add_connect_time,
				lastName='" . &::quoteSQL($name) . "',
				lastAddress='$address',
				clan='$clan',
				kills=kills + $kills,
				deaths=deaths + $deaths,
				suicides=suicides + $suicides,
				skill=$skill,
				headshots=headshots + $headshots,
				shots=shots + $shots,
				hits=hits + $hits,
				last_event=".$::ev_unixtime.",
				last_skill_change=$last_skill_change,
				death_streak=$death_streak,
				kill_streak=$kill_streak,
				hideranking=IF(hideranking=3,0,hideranking),
				activity = 100
			WHERE
				playerId=$playerid
		";
		&::execNonQuery($query);

		if ($::g_stdin == 0 || $::g_timestamp > 0) {
			# Update player details
			my $query = "
				UPDATE
					hlstats_Players_History
				SET
					connection_time = connection_time + $add_connect_time,
					kills=kills + $kills,
					deaths=deaths + $deaths,
					suicides=suicides + $suicides,
					skill=$skill,
					headshots=headshots + $headshots,
					shots=shots + $shots,
					hits=hits + $hits,
					skill_change=skill_change + $add_history_skill
				WHERE
					playerId=$playerid
					AND eventTime='$date'
					AND game='".&::quoteSQL($::g_servers{$srv_addr}->{game})."'
			";
			&::execNonQuery($query);
		}
	}
	
	if ($name)
	{
		# Update alias details
		$query = "
			UPDATE
				hlstats_PlayerNames
			SET
  			    connection_time = connection_time + $add_connect_time,
				kills=kills + $kills,
				deaths=deaths + $deaths,
				suicides=suicides + $suicides,
   			    headshots=headshots + $headshots,
    			shots=shots + $shots,
   			    hits=hits + $hits"
		;
		
		unless ($leaveLastUse)
		{
			# except on ChangeName we update the last use on a player's old name
			
			$query .= ",
				lastuse=FROM_UNIXTIME(" . $::ev_unixtime . ")"
			;
		}
		
		$query .= "
			WHERE
				playerId= $playerid
				AND name='" . &::quoteSQL($self->{name}) . "'
		";
		&::execNonQuery($query);
	}
	
	# reset player stat properties
	$self->set("kills",     0, 1);
	$self->set("deaths",    0, 1);
	$self->set("suicides",  0, 1);
	$self->set("headshots", 0, 1);
	$self->set("shots",     0, 1);
	$self->set("hits",      0, 1);
	
	if (($is_bot == 1) && ($::g_servers{$server_address}->{ignore_bots} == 1)) {
		$skill        = 0;
		$skill_change = 0;
	}
	
	if ($::g_stdin == 0 && $self->{userid} > 0) {
		# Update live stats
		my $query = "
			REPLACE INTO
				hlstats_Livestats
			SET
				player_id=$playerid,
				server_id=$serverid,
				cli_address='$address',
				cli_city='".&::quoteSQL($self->{city})."',
				cli_country='".&::quoteSQL($self->{country})."',
				cli_flag='".&::quoteSQL($self->{flag})."',
				cli_state='".&::quoteSQL($self->{state})."',
				cli_lat='$self->{lat}',
				cli_lng='$self->{lng}',
				steam_id='" . &::quoteSQL($steamid) . "',
				name='" . &::quoteSQL($name) . "',
				team='".&::quoteSQL($team)."',
				kills=$map_kills,
				deaths=$map_deaths,
				suicides=$map_suicides,
				headshots=$map_headshots,
				shots=$map_shots,
				hits=$map_hits,
				is_dead=$is_dead,
				has_bomb=$has_bomb,
				ping=$ping,
				connected=$connected,
				skill_change=$skill_change,
				skill=$skill
		";
		&::execNonQuery($query);
	}

    if ($::g_stdin == 0)  {
		$self->{last_update} = time();
	} elsif ($::g_stdin == 1)  {
		$self->{last_update} = $::ev_unixtime;
	}
	
	$self->{last_update_skill} = $skill;
	
	&::printNotice("Updated player object " . $self->getInfoString());
	
	return 1;
}


#
# Update player timestamp (time of last event for player - used to detect idle
# players)
#

sub updateTimestamp
{
	my ($self, $timestamp) = @_;
	$timestamp = $::ev_unixtime
		unless ($timestamp);
	$self->{timestamp} = $timestamp;
	return $timestamp;
}

sub updateDB
{
	my ($self) = @_;
	my $playerid = $self->{playerid};
	$::g_changed_players{$playerid} = $self;
	
}

sub deleteLivestats
{
	my ($self) = @_;

	# delete live stats
	my $query = "DELETE FROM hlstats_Livestats WHERE player_id=".$self->{playerid};
	&::execNonQuery($query);
}


#
# Returns a string of information about the player.
#

sub getInfoString
{
	my ($self) = @_;
	return sprintf("\"%s\" \<P:%d,U:%d,W:%d,T:%s\>", $self->{name}, $self->{playerid}, $self->{userid}, $self->{uniqueid}, $self->{team});
}


sub getAddress
{
	my ($self) = @_;

	if (($self->{is_bot} == 0 && $self->{address} eq "" && $self->{userid} > 0) && $::g_stdin == 0 && (time() - $self->{last_address_check}) > 120)
	{
		$self->{last_address_check} = time();
		$s_addr = $self->{server};
		
		&::printNotice("rcon_getaddress");
		my $result = $::g_servers{$s_addr}->rcon_getaddress($self->{uniqueid});
		if ($result) {
			$self->{address}  = $result->{Address};
			$self->{cli_port} = $result->{ClientPort};
			$self->{ping}     = $result->{Ping};
		
			&::printEvent("RCON", "Got Address $self->{address} for Player $self->{name}", 1);
			&::printNotice("rcon_getaddress successfully");
		}
		$self->geoLookup();
	}
	return 1;
}

sub geoLookup
{
	my ($self) = @_;
	my $ip_address = $self->{address};
	my $found = 0;
	
	if ($ip_address ne "")
	{
		my $country_code = undef;
		my $country_code3 = undef;
		my $country_name = undef;
		my $region = undef;
		my $city = undef;
		my $postal_code = undef;
		my $lat = undef;
		my $lng = undef;
		my $metro_code = undef;
		my $area_code = undef;
		
		if ($::g_geoip_binary > 0) {
			($country_code, $country_code3, $country_name, $region, $city, $postal_code, $lat, $lng, $metro_code, $area_code) = $::g_gi->get_city_record($ip_address);
			if ($lng) {
				$found++;
				$self->{city} = encode("utf8",$city);
				$self->{state} = encode("utf8",$region);
				$self->{country} = encode("utf8",$country_name);
				$self->{flag} = encode("utf8",$country_code);
				$self->{lat} = (($lat eq "")?undef:$lat);
				$self->{lng} = (($lng eq "")?undef:$lng);
			}
		} else {
			my @ipp = split (/\./,$ip_address);
			my $ip_number = $ipp[0]*16777216+$ipp[1]*65536+$ipp[2]*256+$ipp[3];
			my $query = "
			SELECT locId FROM geoLiteCity_Blocks WHERE startIpNum<=".$ip_number." AND endIpNum>=".$ip_number." LIMIT 1;";
			my $result = &::doQuery($query);
			if ($result->rows > 0) {
				my $locid = $result->fetchrow_array;
				$result->finish;
				my $query = "SELECT city, region AS state, name AS country, country AS flag, latitude AS lat, longitude AS lng FROM geoLiteCity_Location a  inner join hlstats_Countries b ON a.country=b.flag WHERE locId=".$locid." LIMIT 1;";
				my $result = &::doQuery($query);
				if ($result->rows > 0) {
					$found++;
					($city, $state, $country, $flag, $lat, $lng) = $result->fetchrow_array;
					$self->{city} = $city;
					$self->{state} = $state;
					$self->{country} = $country;
					$self->{flag} = $flag;
					$self->{lat} = (($lat eq "")?undef:$lat);
					$self->{lng} = (($lng eq "")?undef:$lng);
				}
				$result->finish;
			}
		}
		if ($found > 0) {
			&::execNonQuery("
				UPDATE
					hlstats_Players
				SET
					city='".&::quoteSQL($self->{city})."',
					`state`='".&::quoteSQL($self->{state})."',
					country='".&::quoteSQL($self->{country})."',
					flag='".&::quoteSQL($self->{flag})."',
					lat=".((defined($self->{lat}))?$self->{lat}:"NULL").",
					lng=".((defined($self->{lng}))?$self->{lng}:"NULL")."
				WHERE
					playerId = $self->{playerid}
			");
		}
	}
}

sub getRank
{
	my ($self) = @_;
	
	my $srv_addr  = $self->{server};
	my $result = &::doQuery("
		SELECT
			kills,
			deaths,
			hideranking
		FROM
			hlstats_Players
		WHERE
			playerId=" . $self->{playerid}
	);
	my ($kills, $deaths, $hideranking) = $result->fetchrow_array;
	$result->finish;
	
	return 0 if ($hideranking > 0);
	
	$deaths = 1 if ($deaths == 0);
	my $kpd = $kills/$deaths;
    
	my $rank = 0;
	
	if ($::g_ranktype ne "kills")
	{
		if (!defined($self->{skill}))
		{
			&::printEvent("ERROR","Attempted to get rank for uninitialized player \"".$self->{name}."\"");
			return 0;
		}
		
		my $rankresult = &::doQuery("
			SELECT
				COUNT(*)
			FROM
				hlstats_Players
			WHERE
				game='".&::quoteSQL($self->{game})."'
				AND hideranking = 0
				AND kills >= 1
				AND (
						(skill > ".$self->{skill}.") OR (
							(skill = ".$self->{skill}.") AND ((kills/IF(deaths=0,1,deaths)) > $kpd)
						)
				)
		");
		($rank) = $rankresult->fetchrow_array;
		$rankresult->finish;
		$rank++;
	}
	else
	{
		my $rankresult = &::doQuery("
			SELECT
				COUNT(*)
			FROM
				hlstats_Players
			WHERE
				game='".&::quoteSQL($self->{game})."'
				AND hideranking = 0
				AND (
						(kills > $kills) OR (
							(kills = $kills) AND ((kills/IF(deaths=0,1,deaths)) > $kpd)
						)
				)
		");
		($rank) = $rankresult->fetchrow_array;
		$rankresult->finish;
		$rank++;
	}
	
	return $rank;
}

sub updateTrackable
{
	my ($self) = @_;
	
	if ((&::isTrackableTeam($self->{team}) == 0) || (($::g_servers{$self->{server}}->{ignore_bots} == 1) && (($self->{is_bot} == 1) || ($self->{userid} <= 0)))) {
		$self->{trackable} = 0;
		return;
	}
	$self->{trackable} = 1;
}

1;