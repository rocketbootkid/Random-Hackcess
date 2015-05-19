<?php

	function getPlayerCurrentGridCoordinates($character_id, $journey_id) {
		
		// Returns the grid coordinates for current character / journey
	
		addToDebugLog("getPlayerCurrentGridCoordinates(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Journey ID: " . $journey_id);	
 	
		// Determine first what grid square we're on, based on current player and journey
		$grid_id = getCharacterCurrentGrid($character_id, $journey_id);
		addToDebugLog("getPlayerCurrentGridCoordinates(): Player's current grid ID: " . $grid_id);
		
		$sql = "SELECT grid_x, grid_y FROM hackcess.grid WHERE grid_id = " . $grid_id . ";";
		addToDebugLog("getPlayerCurrentGridCoordinates(): Constructed query: " . $sql);
		$result = search($sql);

		// $result[0][0] = X
		// $result[0][1] = Y
		
		return $result;	
		
	}

	function drawGrid($grid_x, $grid_y, $radius, $journey_id) {

		// Draws the adventure grid
	
		addToDebugLog("drawGrid(): Function Entry - supplied parameters: Grid X: " . $grid_x . ", Grid Y: " . $grid_y . ", Radius: " . $radius . ", Journey ID: " . $journey_id);	
		
		// Draw grid for 5 square radius around current position
		$start_x = $grid_x - $radius;
		$start_y = $grid_y + $radius;
		addToDebugLog("drawGrid(): Grid Start Coordinates: " . $start_x . "," . $start_y);
		
		$rows_cols = (2 * $radius) + 1;
		
		echo "<table cellpadding=0 cellspacing=0 border=1>";
		
		for ($y = 0; $y < $rows_cols; $y++) {
		
			$current_y = $start_y - $y;
			addToDebugLog("drawGrid(): Current Y: " . $current_y);
			if ($current_y <= 50 && $current_y > 0) {
				echo "<tr height=25px>";
			}

			for ($x = 0; $x < $rows_cols; $x++) {	
				$current_x = $start_x + $x;
				addToDebugLog("drawGrid(): Current Grid Coordinates: " . $current_x . "," . $current_y);
				if ($current_x > 0 && $current_x <= 50 && $current_y <= 50 && $current_y > 0) {
					if ($grid_x == $current_x && $grid_y == $current_y) {
						$color = "#6f6";
					} else {
						$color = "#fff";
					}
					
					// Determine which tile image to show
					$directions = getGridDirectionsByCoordinates($current_x, $current_y, $journey_id);
					addToDebugLog("drawGrid(): Directions: " . $directions);
					
					echo "<td height='25' width=25px bgcolor='" . $color . "' title='" . $current_x . "," . $current_y . "'><img src='images/" . $directions . ".png' border=0>";
				} else {
					addToDebugLog("drawGrid(): Coordinates not on grid; skipping...");
				}	
			}
			echo "</tr>";
		}
		echo "</table>";

	}

	function getGridDirectionsByID($grid_id) {

		// Returns the grid directions for the supplied grid_id
	
		addToDebugLog("getGridDirectionsByID(): Function Entry - supplied parameters: Grid ID: " . $grid_id);	
 	
		$sql = "SELECT directions FROM hackcess.grid WHERE grid_id = " . $grid_id . ";";
		addToDebugLog("getGridDirectionsByID(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		if ($rows != 0) {
			$grid_directions = $result[0][0];
		} else {
			$grid_directions = "9999";
		}
		
		return $grid_directions;	
	
	}

	function getGridDirectionsByCoordinates($grid_x, $grid_y, $journey_id) {

		// Returns the grid directions for the supplied grid coordinates
	
		addToDebugLog("getGridDirectionsByCoordinates(): Function Entry - supplied parameters: Grid X: " . $grid_x . ", Grid Y: " . $grid_y . ", Journey ID: " . $journey_id);	
 	
		$sql = "SELECT directions FROM hackcess.grid WHERE grid_x = " . $grid_x . " AND grid_y = " . $grid_y . " AND journey_id = " . $journey_id . ";";
		addToDebugLog("getGridDirectionsByCoordinates(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		if ($rows != 0) {
			$grid_directions = $result[0][0];
		} else {
			$grid_directions = "9999";
		}
		
		return $grid_directions;	
	
	}
	
	function drawControls($grid_id, $journey_id, $character_id) {

		// Draws the controls grid
	
		addToDebugLog("drawControls(): Function Entry - supplied parameters: Grid ID: " . $grid_id);

		$available_directions = getGridDirectionsByID($grid_id);
		addToDebugLog("drawControls(): Available directions: " . $available_directions);
		
		$available_directions = str_pad($available_directions, 4, "0", STR_PAD_LEFT);
		addToDebugLog("drawControls(): Left padded: " . $available_directions);
		
		$north = substr($available_directions,-4,1);
		$east = substr($available_directions,-3,1);
		$south = substr($available_directions,-2,1);
		$west = substr($available_directions,-1);
		addToDebugLog("drawControls(): North: " . $north . ", South: " . $south . ", East: " . $east . ", West: " . $west);
		
		echo "<table>";
		echo "<tr><td><img src='images/110.png'><td>";
		if ($north == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=north'>North</a>";
		}
		echo "<td><img src='images/11.png'></tr>";
		echo "<tr><td>";
		if ($west == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=west'>West</a>";
		}
		echo "<td><td>";
		if ($east == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=east'>East</a>";
		}
		echo "</tr>";
		echo "<tr><td><img src='images/1100.png'><td>";
		if ($south == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=south'>South</a>";
		}
		echo "<td><img src='images/1001.png'></tr>";
		echo "</table>";
		
	}
	
	function getCharacterCurrentGrid($character_id, $journey_id) {

		// Returns the grid id for current character / journey
	
		addToDebugLog("getPlayerCurrentGrid(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Journey ID: " . $journey_id);		
	
		// Determine first what grid square we're on, based on current player and journey
		$sql = "SELECT character_grid_id FROM hackcess.character WHERE character_id = " . $character_id . " AND current_journey_id = " . $journey_id . ";";
		addToDebugLog("getPlayerCurrentGrid(): Constructed query: " . $sql);
		$result = search($sql);
		$grid_id = $result[0][0];	
		
		return $grid_id;
		
	}
	
	function move($journey_id, $character_id, $direction) {
		

		// Moves the player to a new grid
	
		addToDebugLog("move(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Journey ID: " . $journey_id . ", Direction: " . $direction);	
		
		// Generate new grid in correct location
		// *************************************
		
		// Cut current grid location, read from character's record
		$current_location = getPlayerCurrentGridCoordinates($character_id, $journey_id);
		$x = $current_location[0][0];
		$y = $current_location[0][1];
		addToDebugLog("move(): Current Coordinates: " . $x . "," . $y);
		
		// Generate available directions at new grid
		$north = 1000 * rand(0,1);
		$east = 100 * rand(0,1);
		$south = 10 * rand(0,1);
		$west = 1 * rand(0,1);
		addToDebugLog("move(): Available Directions: North: " . $north . ", East: " . $east . ", South: " . $south . ", West: " . $west);
		
		// Generate new grid coordinates and ensure reciprocal direction available.
		// e.g. if direction is North, South must be available on the new square.		
		switch ($direction) {
			case "north":
				$y = $y + 1;
				$south = 10;
				break;
			case "east":
				$x = $x + 1;
				$west = 1;
				break;
			case "south":
				$y = $y - 1;
				$north = 1000;
				break;
			case "west":
				$x = $x - 1;
				$east = 100;
				break;
		}
		
		// Determine if the grid already exists / has already been visited.
		
		
		$available_directions = $north + $south + $east + $west;
		$display = str_pad($available_directions, 4, "0", STR_PAD_LEFT);
		addToDebugLog("move(): Directions available at new grid: " . $available_directions);
		
		// Create new grid record
		$dml = "INSERT INTO hackcess.grid (grid_x, grid_y, directions, journey_id) VALUES (" . $x . ", " . $y . ", " . $available_directions . ", " . $journey_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): New grid generated");
		} else {
			addToDebugLog("move(): ERROR: New grid not generated");
		}
		
		// Get new grid ID
		$sql = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $journey_id . " ORDER BY grid_id DESC LIMIT 1;";
		addToDebugLog("move(): Constructed query: " . $sql);
		$result = search($sql);
		$grid_id = $result[0][0];
		addToDebugLog("move(): New grid ID: " . $grid_id);
		
		// Add new entry to journal
		$details = "Travelled " . ucfirst($direction) . " to " . $x . "," . $y;
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
		$result_m = insert($dml);
		if ($result_m == TRUE) {
			addToDebugLog("move(): ERROR: Grid added to journey");
		} else {
			addToDebugLog("move(): ERROR: Grid not added to journey");
		}
		
		// Move player location to new grid square
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . ", character_xp = character_xp + 10 WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("move(): Character record updated");
		} else {
			addToDebugLog("move(): Character record not updated");
		}		
		
	}
	
	//echo "<script>window.location.href = 'adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "';</script>";
	
?>