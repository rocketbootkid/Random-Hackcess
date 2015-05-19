<html>

<head>
	<title>Adventure</title>
	<link rel="stylesheet" type="text/css" href="css/css.css">
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	
	// ************************************************************************************
	// Move to new Grid
	// ************************************************************************************
	
	if ($_GET['direction'] && $_GET['direction'] <> "") {
			move($_GET['journey_id'], $_GET['character_id'], $_GET['direction']);
	}
	if ($_GET['jump'] && $_GET['jump'] == 'true') {
			jump($_GET['journey_id'], $_GET['character_id'], $_GET['grid_id']);
	}
	
	// ************************************************************************************
	// Draw Grid
	// ************************************************************************************
	
	$journey_id = $_GET['journey_id'];
	$character_id = $_GET['character_id'];
	
	// Determine what grid square the current character / journey is on
	$coordinates = getPlayerCurrentGridCoordinates($character_id, $journey_id);
	$grid_x = $coordinates[0][0];
	$grid_y = $coordinates[0][1];
	$radius_x = 15;
	$radius_y = 6;
	
	// Draws the grid for defined radius around the current location
	drawGrid($grid_x, $grid_y, $radius_x, $radius_y, $journey_id, $character_id);
	
	echo "<p>";
	
	// ************************************************************************************
	// Draw Controls
	// ************************************************************************************
	
	// Get players current grid id
	$grid_id = getCharacterCurrentGrid($character_id, $journey_id);
	
	drawControls($grid_id, $journey_id, $character_id);
	
	echo "<p><a href='start.php'>Back to Start</a>";

	outputDebugLog();
	
?>

</body>

</html>