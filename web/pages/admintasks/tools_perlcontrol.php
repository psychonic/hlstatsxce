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

	if ( !defined('IN_HLSTATS') ) { die('Do not access this file directly.'); }
	if ($auth->userdata["acclevel"] < 80) die ("Access denied!");
?>

&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo IMAGE_PATH; ?>/downarrow.gif" width=9 height=6 class="imageformat"><b>&nbsp;<?php echo $task->title; ?></b><p>

<?php

   $servers[0]["name"] = "Perl Backend, listening on 127.0.0.1";
   $servers[0]["host"] = "127.0.0.1"; 
 
   $commands[0]["name"] = "Reload the PERL & Servers Configuration from the database";
   $commands[0]["cmd"] = "RELOAD";
   $commands[1]["name"] = "Shut down the perl backend script";
   $commands[1]["cmd"] = "KILL";
    
 
    if (isset($_POST['confirm']))
    {
      echo "<ul>\n";
      $s_id = $_POST['masterserver'];
      $host = $servers[$s_id]["host"];
      $port = $_POST["port"];
      $command = $commands[$_POST["command"]]["cmd"];
      if ($port==0) $port = "27500";
      
      echo "<li>Sending Command to Perl backend...";
      $host = gethostbyname($host);
      $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
      $packet = "C;".$command.";";
      $bytes_sent = socket_sendto($socket, $packet, strlen($packet), 0, $host, $port);
      echo "<b>".$bytes_sent."</b> bytes <b>OK</b></li>";

      echo "<li>Waiting for Backend Answer...";
      $recv_bytes = 0;
      $buffer     = "";
      $timeout    = 5;
      $answer     = "";
      $packets    = 0;
      $read       = array($socket);
      while (socket_select($read, $write = NULL, $except = NULL, &$timeout) > 0) {
        $recv_bytes += socket_recvfrom($socket, &$buffer, 2000, 0, &$host, &$port);
        $answer     .= $buffer;
        $buffer     = "";
        $timeout    = "1";
        $packets++;
      }   

//      $steam_ids = explode(chr(255), $answer);
//      array_pop($steam_ids);
      echo "recieving <b>$recv_bytes</b> bytes in <b>$packets</b> packets...<b>OK</b></li>";
      
      if ($packets>0) {
       echo "<li>Backend Answer: ".$answer;
      } else {
       echo "<li><i>No packets received - check if backend dead or not listening on $host:$port</i>";
      }
      
      echo "<li>Closing connection to backend...";
      socket_close($socket);
      echo "<b>OK</b></li>";
      echo "</ul>\n";
    } else {
        
?>        

<form method="POST">
<table width="60%" align="center" border=0 cellspacing=0 cellpadding=0 class="border">

<tr>
    <td>
        <table width="100%" border=0 cellspacing=1 cellpadding=10>
        
        <tr class="bg1">
            <td class="fNormal">
Note: This page is still in beta and will not operate properly for all installs. If you have any trouble, you may need to interact with the daemon manually via command line/SSH.<br /><br />
With this module, you have limited control over the backend, eg. if you want to re-read the changed configuration or kill the backend listener for delayed restart after updating perl scripts.
<p>
Choose Backend (only localhost at the moment):<br> 
<SELECT NAME="masterserver">

<?php
  $i = 0;
  foreach ($servers as $server) {
   echo "<OPTION VALUE=\"$i\">".$server["name"];
   $i++;
  } 
?>   
</SELECT>

<p>
Specify port, the backend listens on:  
<INPUT TYPE='text' SIZE='6' VALUE='27500' NAME='port'>


<p>
Choose Command to be executed by the backend:<br> 
<SELECT NAME="command">

<?php
  $i = 0;
  foreach ($commands as $cmd) {
   echo "<OPTION VALUE=\"$i\">".$cmd["name"];
   $i++;
  } 
?>   

</SELECT>

<p>

Note: Backend will only be restarted when using the <b>run_hlstats_autorestart</b> script, otherwise the backend will not come up again without manual start.
<p>

<input type="hidden" name="confirm" value="1">
<center><input type="submit" value="  EXECUTE  "></center>
</td>
        </tr>
        
        </table></td>
</tr>

</table>
</form>

<?php
    }
?>    
    