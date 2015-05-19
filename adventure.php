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
	
	// ************************************************************************************
	// Draw Grid
	// ************************************************************************************
	
	$journey_id = $_GET['journey_id'];
	$character_id = $_GET['character_id'];
	
	// Determine what grid square the current character / journey is on
	$coordinates = getPlayerCurrentGridCoordinates($character_id, $journey_id);
	$grid_x = $coordinates[0][0];
	$grid_y = $coordinates[0][1];
	$radius = 10;
	
	// Draws the grid for defined radius around the current location
	drawGrid($grid_x, $grid_y, $radius, $journey_id);
	
	// ************************************************************************************
	// Draw Controls
	// ************************************************************************************
	
	// Get players current grid id
	$grid_id = getCharacterCurrentGrid($character_id, $journey_id);
	
	drawControls($grid_id, $journey_id, $character_id);

	outputDebugLog();
	
?>

</body>

</html>