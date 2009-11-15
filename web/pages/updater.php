<?php

	if ( !defined('IN_HLSTATS') )
	{
		die('Do not access this file directly.');
	}
	
	if ( !file_exists("./updater") )
	{
		die('Updater directory is missing.');
	}
	
	define('IN_UPDATER', true);
	
	pageHeader
	(
		array ($gamename, 'Updater')
	);
	
	// Check version since updater wasn't implemented until version 1.6.2
	$versioncomp = version_compare($g_options['version'], '1.6.1');
	
	if ($versioncomp === -1)
	{
		// not yet at 1.6.1
		echo "You cannot upgrade from this version (".$g_options['version']."). You can only upgrade from 1.6.1.";
	}
	else if ($versioncomp === 0)
	{
		// at 1.6.1, up to 1.6.2
		include ("./updater/update161-162.php");		
	}
	else
	{
		// at 1.6.2 or higher, can update normally
		echo "On db version ".$g_options['dbversion']."<br />";
		$i = $g_options['dbversion']+1;
		
		while (file_exists ("./updater/$i.php"))
		{
			echo "Running db update $i<br />";
			include ("./updater/$i.php");
			
			$i++;
		}
		
		if ($i == $g_options['dbversion']+1)
		{
			echo "Your db is already up to date (".$g_options['dbversion'].")";
		}
		else
		{
			echo "Successfully updated to db version ".($i-1);
		}
	}
	
	echo "<br /><br />You <strong>must delete</strong> the \"updater\" folder from your web site before your site will be operational.";
?>