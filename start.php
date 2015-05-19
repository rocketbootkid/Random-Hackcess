<html>

<head>
	<title>Random Hackcess</title>
	<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	if ($_GET['journey_id'] && $_GET['character_id']) {
		changeJourney($_GET['journey_id'], $_GET['character_id']);
	}
	
	if ($_GET['create']) {
		newJourney();
	}
	
	displayPlayers();

	outputDebugLog();
	
?>

</body>

</html>