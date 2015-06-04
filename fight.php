<html>

<head>
	<title>Random Hackcess | Fight</title>
	<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	$character_id = $_GET['character_id'];
	$enemy_id = $_GET['enemy_id'];
	$grid_id = $_GET['grid_id'];
	$player_id = $_GET['player_id'];
	$journey_id = $_GET['journey_id'];
	
	doFight($character_id, $enemy_id, $grid_id, $player_id, $journey_id);
	
	//outputDebugLog();
	
?>

</body>

</html>