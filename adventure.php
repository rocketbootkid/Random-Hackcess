<html>

<head>
	<title>Random Hackess | Adventure</title>
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
		
		echo "<table cellpadding=5 cellspacing=0 border=0 align=center><tr><td valign=top width=300px rowspan=2>";
		
			echo "<h2>Adventure</h2>";
		
			echo "Select | <a href='player.php'>Player</a> | ";
			echo "<a href='character.php?player_id=" . $player_id . "'>Character</a> | ";
			echo "<a href='journey.php?player_id=" . $player_id . "&character_id=" . $character_id . "'>Journey</a><p>";
			echo "<a href='equipment.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "'>View Player Equipment</a><p>";
			echo "<a href='journey_map.php?journey_id=" . $journey_id . "' target='_blank'>View Entire Journey Map</a>";
		
			// Lists the stores available on the map
			storeList($journey_id, $player_id);
			
			// Lists the unbeaten enemies on the map
			enemyList($character_id, $journey_id, $player_id);
			
		echo "<td colspan=3 width=1000px>";
			// Draws the grid for defined radius around the current location
			drawGrid($grid_x, $grid_y, $radius_x, $radius_y, $journey_id, $character_id, $player_id);
		
		// ************************************************************************************
		// Draw Controls
		// ************************************************************************************
		
		// Get players current grid id
		$grid_id = getCharacterCurrentGrid($character_id, $journey_id);
		
		echo "<tr>";
		
		// First column: Journal entries
		echo "<td width=425px align=left>";
		// Display Journey Name, Journal entries
		displayJournal($journey_id);
		
		// Second column: navigation controls
		echo "<td width=150px>";
		// Draw Navigation controls
		drawControls($grid_id, $journey_id, $character_id, $player_id);
		
		// Third column: Player details
		echo "<td width=425px align=right>";
		// Show character details
		displayPlayerInformation($character_id);
		
		echo "</tr></table>";
		
		//outputQueryCount();
		//outputQueryList();
		
		outputDebugLog();

	}
	
?>

</body>

</html>