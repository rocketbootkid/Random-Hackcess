<html>

<head>
	<title>Random Hackcess | Select Character</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	// Create a new character
	if ($_GET['create'] == "character") {
		createCharacter($_GET['player_id'], 0);
	} else {
	
		// Show list of characters
		if ($_GET['player_id']) {
			characterSelect($_GET['player_id']);
		} else {
			echo "<div style='text-align: center;'><a href='player.php'>Go Back and select a Player</a></div>";
		}
		
		echo "<p><div style='text-align: center;'><a href='player.php'>Back to Player Select</a></div>";
	
		//outputDebugLog();

	}
		
?>



</body>

</html>