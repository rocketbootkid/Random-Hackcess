<html>

<head>
	<title>Random Hackcess | Fight History</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	
	$player_id = $_GET['player_id'];
	$character_id = $_GET['character_id'];
	
	// Display Character Name
	$character_name = getCharacterDetails($character_id, "character_name");
	echo "<h1 align=center>" . trim($character_name) . "'s Fight History</h1><p>";
	
	echo "<p><div style='text-align: center;'><a href='character.php?player_id=" . $player_id . "'>Back to Character Select</a></div><p>";
	
	showCharacterHistory($character_id);

	echo "<p><div style='text-align: center;'><a href='character.php?player_id=" . $player_id . "'>Back to Character Select</a></div>";
	
	//outputDebugLog();
	
?>

</body>

</html>