<html>

<head>
	<title>Random Hackcess | Journey Map</title>
	<link rel="stylesheet" type="text/css" href="css/css.css">
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	$journey_id = $_GET['journey_id'];
	
	// Draw entire journey
	drawEntireJourney($journey_id);
	
	outputDebugLog();
		
?>



</body>

</html>