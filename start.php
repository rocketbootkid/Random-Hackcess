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

	addToDebugLog("start.php, page, INFO");
	
	if ($_GET['journey_id'] && $_GET['character_id']) {
		changeJourney($_GET['journey_id'], $_GET['character_id']);
	}
	
	if ($_GET['create'] == "journey") {
		newJourney($_GET['player_id'], $_GET['character_id']);
	}
	
	if ($_GET['create'] == "character") {
		createCharacter($_GET['player_id'], 0);
	}
	
	displayPlayers();

	outputDebugLog();
	
?>

</body>

</html>