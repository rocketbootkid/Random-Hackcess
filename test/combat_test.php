<html>

<head>
	<title>Random Hackcess | Fight Mechanics Tester</title>
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
	
	$enemy_wins = 0;
	$character_wins = 0;
	$fights = 50;
	
	for ($f = 0; $f < $fights; $f++) {
		
		$winner = doFight($character_id, $enemy_id);
		
		if ($winner == "enemy") {
			$enemy_wins++;
		} else {
			$character_wins++;
		}
		
	}
	echo "Character: " . $character_wins . " vs " . $enemy_wins . " :Enemy";
	$ratio = round(($character_wins / $fights) * 100, 0);
	echo "<br/>Character Win Ratio: " . $ratio . "%";
	
	//outputDebugLog();
	
?>

</body>

</html>
