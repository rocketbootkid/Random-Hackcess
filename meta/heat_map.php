<html>

<head>
	<title>Random Hackcess | Meta | Grid Heat Map</title>
</head>

<body>

<?php

	include '../functions/debug_functions.php';
	include '../functions/mysql_functions.php';
	include '../functions/grid_functions.php';
	include '../functions/player_functions.php';

	addToDebugLog("heat_map.php, page, INFO");
	
	gridHeatMap();
	
	outputDebugLog();
	
?>

</body>

</html>