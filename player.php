<html>

<head>
	<title>Random Hackcess | Select Player</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	addToDebugLog("player.php, page, INFO");
	
	playerSelect();
	
	echo "<p><div style='text-align: center;'><a href='docs/doc_generator.php' target='_blank'>Generate Docs</a> | <a href='docs/api.html' target='_blank'>View Docs</a></div>";
	echo "<h2 align=center>Meta</h2><div style='text-align: center;'><a href='meta/heat_map.php' target='_blank'>Grid Heat Map</a></div>";
	
	outputDebugLog();
	
?>

</body>

</html>