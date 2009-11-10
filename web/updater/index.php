<?php
	$url = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	header('Location: $url/hlstats.php?mode=updater');
?>