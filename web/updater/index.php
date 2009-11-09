<?php

	define('IN_HLSTATS', true);
	
	// Load required files
	require("config.php");
	require(INCLUDE_PATH . "/class_db.php");
	require(INCLUDE_PATH . "/functions.php");

	// Initialize DB
	$db_classname = "DB_" . DB_TYPE;
	if ( class_exists($db_classname) )
	{
		$db = new $db_classname(DB_ADDR, DB_USER, DB_PASS, DB_NAME, DB_PCONNECT);
	}
	else
	{
		error('Database class does not exist.  Please check your config.php file for DB_TYPE');
	}

	$g_options = getOptions();

	include (PAGE_PATH . '/header.php');
	
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
		include ("updatedata/update161-162.php");		
		echo "Update to 1.6.2 completed successfully.";
	}
	else
	{
		// at 1.6.2 or higher, can update normally
		echo "On db version ".$g_options['dbversion']."<br />";
		$i = $g_options['dbversion']+1;
		
		while (file_exists ("updatedata/$i.php"))
		{
			echo "Running db update $i<br />";
			include ("updatedata/$i.php");
			
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
	
	include (PAGE_PATH . '/footer.php');
	
?>