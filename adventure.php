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
	include 'functions/player_functions.php';
	include 'functions/store_functions.php';
	
	// ************************************************************************************
	// Move to new Grid
	// ************************************************************************************
	
	if ($_GET['direction'] && $_GET['direction'] <> "") {
			move($_GET['journey_id'], $_GET['character_id'], $_GET['direction'], $_GET['player_id']);
	} elseif ($_GET['jump'] && $_GET['jump'] == 'true') {
			jump($_GET['journey_id'], $_GET['character_id'], $_GET['grid_id'], $_GET['player_id']);
	} else { // Only draw everything if not using page to move / jump, since those functions will reload the page
	
		// ************************************************************************************
		// Draw Grid
		// ************************************************************************************
		
		$player_id = $_GET['player_id'];
		$character_id = $_GET['character_id'];
		$journey_id = $_GET['journey_id'];
		
		
		// Determine what grid square the current character / journey is on
		$coordinates = getPlayerCurrentGridCoordinates($character_id, $journey_id);
		$grid_x = $coordinates[0][0];
		$grid_y = $coordinates[0][1];
		$radius_x = 10;
		$radius_y = 6;
		
		// Draws the grid for defined radius around the current location
		drawGrid($grid_x, $grid_y, $radius_x, $radius_y, $journey_id, $character_id, $player_id);
		
		echo "<p>";
		
		// ************************************************************************************
		// Draw Controls
		// ************************************************************************************
		
		// Get players current grid id
		$grid_id = getCharacterCurrentGrid($character_id, $journey_id);
		
		echo "<table width=1400px><tr>";
		
		// First column: hyperlinks
		echo "<td width=300px align=left valign=top>";
		echo "<a href='equipment.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "'>View Player Equipment</a><br/>";
		echo "<a href='journey.php?player_id=" . $player_id . "&character_id=" . $character_id . "'>Back to Journey Select</a>";
		
		$grid_id = getCharacterCurrentGrid($character_id, $journey_id);

		// Determine if there is a store here
		$store_id = isThereAStoreHere($grid_id);
		if ($store_id > 0) {
			echo "<p><a href='store.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&store_id=" . $store_id . "'>Visit Store</a>"; // character, player, journey, store
		} 
		
		// Determine if there is an enemy here
		$enemy_id = isEnemyHere($grid_id, $character_id);
		if ($enemy_id > 0) {
			echo "<p><a href='battle.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&enemy_id=" . $enemy_id . "&grid_id=" . $grid_id . "'>Face Enemy</a>";
		}
		
		// Second column: Journal entries
		echo "<td width=300px align=right>";
		// Display Journey Name, Journal entries
		displayJournal($journey_id);
		
		// Third column: navigation controls
		echo "<td width=100px>";
		// Draw Navigation controls
		drawControls($grid_id, $journey_id, $character_id, $player_id);
		
		// Fourth column: Player details
		echo "<td>";
		// Show character details
		displayPlayerInformation($character_id);
		
		echo "</tr></table>";
		
		//outputQueryCount();
		//outputQueryList();
		//outputDebugLog();

	}
	
?>

</body>

</html>