<html>

<head>
	<title>Random Hackcess | Battle</title>
	<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	
	$player_id = $_GET['player_id'];
	$character_id = $_GET['character_id'];
	$journey_id = $_GET['journey_id'];
	$grid_id = $_GET['grid_id'];

	// Create feature record
	$feature_id = generateFeature($grid_id, "fight");
	
	// Create Enemy
	$enemy_id = createEnemy($journey_id, $character_id, $grid_id);
	
	// Display Character Stats
	
	// Display Enemy Stats

	// Show option: Fight or Run
	
	outputDebugLog();
	
?>

</body>

</html>