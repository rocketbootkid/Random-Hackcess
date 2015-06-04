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

	function drawGrid($grid_x, $grid_y, $radius_x, $radius_y, $journey_id, $character_id, $player_id) {

		// Draws the adventure grid
	
		addToDebugLog("drawGrid(): Function Entry - supplied parameters: Grid X: " . $grid_x . ", Grid Y: " . $grid_y . ", Radius X: " . $radius_x . ", Radius Y: " . $radius_y . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id);	
		
		// Draw grid for 5 square radius around current position
		$start_x = $grid_x - $radius_x;
		$start_y = $grid_y + $radius_y;
		addToDebugLog("drawGrid(): Grid Start Coordinates: " . $start_x . "," . $start_y);
		
		$rows = (2 * $radius_y) + 1;
		$cols = (2 * $radius_x) + 1;
		
		$character_current_grid_id = getCharacterCurrentGrid($character_id, $journey_id);
		
		echo "<table cellpadding=0 cellspacing=0 border=1>";
		
		for ($y = 0; $y < $rows; $y++) {
		
			$current_y = $start_y - $y;
			addToDebugLog("drawGrid(): Current Y: " . $current_y);
			if ($current_y <= 50 && $current_y > 0) {
				echo "<tr height=25px>";
			}

			for ($x = 0; $x < $cols; $x++) {	
				$current_x = $start_x + $x;
				addToDebugLog("drawGrid(): Current Grid Coordinates: " . $current_x . "," . $current_y);
				
				// Get grid_id
				/*
				$grid_id = getGridIDByCoordinates($current_x, $current_y, $journey_id);
				$is_enemy_here = 0;
				if ($grid_id > 0) {
					// Determine if this grid has a live enemy at it
					$is_enemy_here = isEnemyHere($grid_id, $character_id);
				} else {
					$is_enemy_here == 0;
				}
				*/
				
				
				if ($current_x > 0 && $current_x <= 50 && $current_y <= 50 && $current_y > 0) {
					if ($grid_x == $current_x && $grid_y == $current_y) {
						$class = "current";
					} elseif ($current_x == 25 && $current_y == 1) {
						$class = "start";
					//} elseif ($is_enemy_here == 1) {
					//	$class = "enemy";
					} else {
						$class = "normal";
					}
					
					// Determine which tile image to show
					$directions = getGridDirectionsByCoordinates($current_x, $current_y, $journey_id);
					addToDebugLog("drawGrid(): Directions: " . $directions);
					$grid_id = getGridIDByCoordinates($current_x, $current_y, $journey_id);
					addToDebugLog("drawGrid(): Grid ID: " . $grid_id);
					
					echo "<td class='" . $class . "' height='25' width=25px bgcolor='" . $color . "' title='(" . $current_x . "," . $current_y . ") ID: " . $grid_id . "'>";
					if ($directions != '9999') {
						// Determine if there's an enemy or a store here, but only if this is the characters current grid
						if ($grid_id == $character_current_grid_id) {
							
							// Determine if there is a store here
							$store_id = isThereAStoreHere($grid_id);
							if ($store_id > 0) {
								echo "<a href='store.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&jump=true&player_id=" . $player_id . "'>";
							}
							
							
						} else {						
							echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&jump=true&player_id=" . $player_id . "'>";
						}
						
					}
					echo "<img src='images/" . $directions . ".png' border=0>";
					echo "</a>";
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
	
	function drawControls($grid_id, $journey_id, $character_id, $player_id) {

		// Draws the controls grid
	
		addToDebugLog("drawControls(): Function Entry - supplied parameters: Grid ID: " . $grid_id . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id . ", Player ID: " . $player_id);

		$available_directions = getGridDirectionsByID($grid_id);
		addToDebugLog("drawControls(): Available directions: " . $available_directions);
		
		$north = substr($available_directions,-4,1);
		$east = substr($available_directions,-3,1);
		$south = substr($available_directions,-2,1);
		$west = substr($available_directions,-1);
		addToDebugLog("drawControls(): North: " . $north . ", South: " . $south . ", East: " . $east . ", West: " . $west);
		
		echo "<table cellpadding=0 cellspacing=0>";
		echo "<tr><td class='controls'><img src='images/9119.png'><td class='controls'>";
		if ($north == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=north&player_id=" . $player_id . "'><img src='images/north.png' alt='North' title='Go North' border=0></a>";
		} else {
			echo "<img src='images/9191.png' border=0>";
		}
		echo "<td class='controls'><img src='images/9911.png'></tr>";
		echo "<tr><td class='controls'>";
		if ($west == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=west&player_id=" . $player_id . "'><img src='images/west.png' alt='West' title='Go West' border=0></a>";
		} else {
			echo "<img src='images/1919.png' border=0>";
		}
		echo "<td class='controls'><img src='images/center.png' alt='Rose'><td class='controls'>";
		if ($east == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=east&player_id=" . $player_id . "'><img src='images/east.png' alt='East' title='Go East' border=0></a>";
		} else {
			echo "<img src='images/1919.png' border=0>";
		}
		echo "</tr>";
		echo "<tr><td class='controls'><img src='images/1199.png'><td class='controls'>";
		if ($south == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=south&player_id=" . $player_id . "'><img src='images/south.png' alt='South' title='Go South' border=0></a>";
		} else {
			echo "<img src='images/9191.png' border=0>";
		}
		echo "<td class='controls'><img src='images/1991.png'></tr>";
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
	
	function move($journey_id, $character_id, $direction, $player_id) {		

		// Moves the player to a new grid
	
		addToDebugLog("move(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Journey ID: " . $journey_id . ", Direction: " . $direction);	
		
		// Generate new grid in correct location
		// *************************************
		
		// Cut current grid location, read from character's record
		$current_location = getPlayerCurrentGridCoordinates($character_id, $journey_id);
		$x = $current_location[0][0];
		$y = $current_location[0][1];
		addToDebugLog("move(): Current Coordinates: " . $x . "," . $y);
		
		// Generate new grid coordinates and ensure reciprocal direction available.
		// e.g. if direction is North, South must be available on the new square.		
		// Also, determine if the grids around the new grid already exist
		// If so, check if they have a path leading to the new grid, and ensure a path is created to join it.

		switch ($direction) {
			case "north": // Going North
				$y = $y + 1; // Update Y coord
				$south = 10; // Ensure reciprocal direction available.
				// Check if adjacent grids have path leading to this one, and ensure path is created.
				$north = checkDirection("north", $x, $y, $journey_id); // North
				$east = checkDirection("east", $x, $y, $journey_id); // East
				$west = checkDirection("west", $x, $y, $journey_id); // West
				addToDebugLog("move(): NORTH: After comparing neighbouring grids, final directions are: North: " . $north . ", East: " . $east . ", South: " . $south . ", West: " . $west);
				break;
			case "east":
				$x = $x + 1;
				$west = 1;
				// Check if adjacent grids have path leading to this one, and ensure path is created.
				$north = checkDirection("north", $x, $y, $journey_id); // North
				$east = checkDirection("east", $x, $y, $journey_id); // East
				$south = checkDirection("south", $x, $y, $journey_id); // South
				addToDebugLog("move(): EAST: After comparing neighbouring grids, final directions are: North: " . $north . ", East: " . $east . ", South: " . $south . ", West: " . $west);
				break;
			case "south":
				$y = $y - 1;
				$north = 1000;
				// Check if adjacent grids have path leading to this one, and ensure path is created.
				$south = checkDirection("south", $x, $y, $journey_id); // South
				$east = checkDirection("east", $x, $y, $journey_id); // East
				$west = checkDirection("west", $x, $y, $journey_id); // West
				addToDebugLog("move(): SOUTH: After comparing neighbouring grids, final directions are: North: " . $north . ", East: " . $east . ", South: " . $south . ", West: " . $west);
				break;
			case "west":
				$x = $x - 1;
				$east = 100;
				// Check if adjacent grids have path leading to this one, and ensure path is created.
				$north = checkDirection("north", $x, $y, $journey_id); // North
				$south = checkDirection("south", $x, $y, $journey_id); // South
				$west = checkDirection("west", $x, $y, $journey_id); // West
				addToDebugLog("move(): WEST: After comparing neighbouring grids, final directions are: North: " . $north . ", East: " . $east . ", South: " . $south . ", West: " . $west);
				break;	
		}
		
		// Determine if the grid already exists / has already been visited.
		$directions = getGridDirectionsByCoordinates($x, $y, $journey_id);
		addToDebugLog("move(): Directions available at new grid: " . $directions);
		
		if ($directions == "9999") { // We've not visited the grid, so need to generate a new one
			addToDebugLog("move(): We've not visited the new grid");

			// Construct the available directions
			$available_directions = $north + $south + $east + $west;
			//$display = str_pad($available_directions, 4, "0", STR_PAD_LEFT);
			addToDebugLog("move(): Directions available at new grid: " . $available_directions);
			
			// Create new grid record
			$grid_id = writeGrid($x, $y, $available_directions, $journey_id);
			addToDebugLog("move(): New Grid ID: " . $grid_id);
			
			// Determine if there's going to be a store here
			srand(make_seed());
			$isStore = rand(0, 30);
			if ($isStore == 15) {
				addToDebugLog("move(): Generating a store at Grid ID " . $grid_id);
				generateStore($grid_id, $journey_id, $character_id);
			}
			
		} else {
			addToDebugLog("move(): We've visited the next grid before");
			
			// Get grid id for the existing grid
			$grid_id = getGridIDByCoordinates($x, $y, $journey_id);
			addToDebugLog("move(): New grid ID (previously visited grid): " . $grid_id);
			
		}
		
		// Add new entry to journal
		$details = "Travelled " . ucfirst($direction) . " to " . $x . "," . $y;
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
		$result_m = insert($dml);
		if ($result_m == TRUE) {
			addToDebugLog("move(): ERROR: Grid added to journey");
		} else {
			addToDebugLog("move(): ERROR: Grid not added to journey");
		}
		
		// Update player position / xp / manage levelling up
		updatePlayerOnMove($character_id, $grid_id, $journey_id);
		
		// If grid is a dead end, redirect to the battle page
		if ($available_directions == "1999" || $available_directions == "9199" || $available_directions == "9919" || $available_directions == "9991") {
			echo "<script>window.location.href = 'battle.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&grid_id=" . $grid_id . "&action=create'</script>";
		}
		
		// Reload page
		echo "<script>window.location.href = 'adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";
		//echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'>Back</a>";
		
		//outputDebugLog();
		
	}
	
	function jump($journey_id, $character_id, $grid_id, $player_id) {

		// Fast-travels the player to a new grid
	
		addToDebugLog("move(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Journey ID: " . $journey_id . ", New Grid ID: " . $grid_id);
		
		// Get coordinates for Grid ID
		$coordinates = getCoordinatesByGridID($grid_id, $journey_id);
		$x = $coordinates[0][0];
		$y = $coordinates[0][1];
	
		// Add new entry to journal
		$details = "Fast-travelled to Grid " . $grid_id . " (" . $x . "," . $y . ")";
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
		$result_m = insert($dml);
		if ($result_m == TRUE) {
			addToDebugLog("move(): ERROR: Grid added to journey");
		} else {
			addToDebugLog("move(): ERROR: Grid not added to journey");
		}
		
		// Move player location to new grid square, but no XP increase
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . " WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("move(): Character record updated");
		} else {
			addToDebugLog("move(): Character record not updated");
		}

		// Reload page
		echo "<script>window.location.href = 'adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";		

	}
	
	
	function getGridIDByCoordinates($grid_x, $grid_y, $journey_id) {

		// Returns the grid id for the supplied grid coordinates
	
		addToDebugLog("getGridDirectionsByCoordinates(): Function Entry - supplied parameters: Grid X: " . $grid_x . ", Grid Y: " . $grid_y . ", Journey ID: " . $journey_id);	
 	
		$sql = "SELECT grid_id FROM hackcess.grid WHERE grid_x = " . $grid_x . " AND grid_y = " . $grid_y . " AND journey_id = " . $journey_id . ";";
		addToDebugLog("getGridDirectionsByCoordinates(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);

		if ($rows > 0) {
			return $result[0][0];	
		} else {
			return 0;
		}
	
	}

	function getCoordinatesByGridID($grid_id, $journey_id) {

		// Returns the coordinates for the supplied grid id
	
		addToDebugLog("getCoordinatesByGridID(): Function Entry - supplied parameters: Grid ID: " . $grid_id . ", Journey ID: " . $journey_id);	
 	
		$sql = "SELECT grid_x, grid_y FROM hackcess.grid WHERE grid_id = " . $grid_id . " AND journey_id = " . $journey_id . ";";
		addToDebugLog("getGridDirectionsByCoordinates(): Constructed query: " . $sql);
		$result = search($sql);

		return $result;	
	
	}
	
	function checkDirection($direction, $x, $y, $journey_id) {
		
		// Returns the coordinates for the supplied grid id
	
		addToDebugLog("checkDirection(): Function Entry - supplied parameters: Direction: " . $direction . ", Grid X: " . $x . ", Grid Y: " . $y);	
		
		// $direction	is the direction from which we entered the grid
		// $x			is the X coordinate of the grid
		// $y			is the Y coordinate of the grid
		// $journey_id	is the id of the current journey
		
		switch($direction) {
			case "north": 
				if ($y < 50) {
					// Generate coords of northern neighbour
					$neighbour_y = $y + 1;
					$neighbour_x = $x;
					$neighbour_directions = getGridDirectionsByCoordinates($neighbour_x, $neighbour_y, $journey_id);
					addToDebugLog("checkDirection(): Available neighbour directions: " . $neighbour_directions);
					
					if ($neighbour_directions == 9999) {
						addToDebugLog("checkDirection(): Northern neighbour is empty");
						$north = rand(0, 1) * 1000; // // Determine whether to draw path or not
						if ($north == 0) { $north = 9000;}
					} else {
						// Need to know if the northern grid has a southward path
						$is_path = substr($neighbour_directions, 2, 1); // extract southern component
						addToDebugLog("checkDirection(): " . ucfirst($direction) . " neighbour has path to this grid? (1 = yes, 9 = no): " . $is_path);
						if ($is_path == 1) {
							$north = 1000; // Must have path to join with northern neighbour
						} else {
							$north = 9000; // Must not have path
						}
					}
				} else { // Off grid, so must not have a path
					$north = 9000;
					addToDebugLog("checkDirection(): We're at the northernmost edge of the map");
				}
				
				$return_value = $north;
				break;
			case "east":
				if ($x < 50) {
					// Generate coords of eastern neighbour
					$neighbour_y = $y;
					$neighbour_x = $x + 1;
					$neighbour_directions = getGridDirectionsByCoordinates($neighbour_x, $neighbour_y, $journey_id);
					addToDebugLog("checkDirection(): Available neighbour directions: " . $neighbour_directions);
					
					if ($neighbour_directions == 9999) {
						addToDebugLog("checkDirection(): Eastern neighbour is empty");
						$east = rand(0, 1) * 100; // // Determine whether to draw path or not
						if ($east == 0) { $east = 900;}
					} else {
						// Need to know if the eastern grid has a westward path
						$is_path = substr($neighbour_directions, 3, 1); // extract western component
						addToDebugLog("checkDirection(): " . ucfirst($direction) . " neighbour has path to this grid? (1 = yes, 9 = no): " . $is_path);
						if ($is_path == 1) {
							$east = 100;
						} else {
							$east = 900;
						}
					}
				} else {
					$east = 900;
					addToDebugLog("checkDirection(): We're at the easternmost edge of the map");
				}
				$return_value = $east;
				break;
			case "west":
				if ($x > 1) {
					$neighbour_y = $y;
					$neighbour_x = $x - 1;
					$neighbour_directions = getGridDirectionsByCoordinates($neighbour_x, $neighbour_y, $journey_id);
					addToDebugLog("checkDirection(): Available neighbour directions: " . $neighbour_directions);
					
					if ($neighbour_directions == 9999) {
						addToDebugLog("checkDirection(): Western neighbour is empty");
						$west = rand(0, 1) * 1; // // Determine whether to draw path or not
						if ($west == 0) { $west = 9;}
					} else {					
						// Need to know if the western grid has a eastward path
						$is_path = substr($neighbour_directions, 1, 1);
						addToDebugLog("checkDirection(): " . ucfirst($direction) . " neighbour has path to this grid? (1 = yes, 9 = no): " . $is_path);
						if ($is_path == 1) {
							$west = 1;
						} else {
							$west = 9;
						}	
					}					
				} else {
					$west = 9;
					addToDebugLog("checkDirection(): We're at the westernmost edge of the map");
				}
				$return_value = $west;
				break;
			case "south":
				if ($y > 1) {
					$neighbour_y = $y - 1;
					$neighbour_x = $x;
					$neighbour_directions = getGridDirectionsByCoordinates($neighbour_x, $neighbour_y, $journey_id);
					addToDebugLog("checkDirection(): Available neighbour directions: " . $neighbour_directions);
					
					if ($neighbour_directions == 9999) {
						addToDebugLog("checkDirection(): Eastern neighbour is empty");
						$south = rand(0, 1) * 10; // // Determine whether to draw path or not
						if ($south == 0) { $south = 90;}
					} else {
						// Need to know if the southern grid has a northward path
						$is_path = substr($neighbour_directions, 0, 1);
						addToDebugLog("checkDirection(): " . ucfirst($direction) . " neighbour has path to this grid? (1 = yes, 9 = no): " . $is_path);
						if ($is_path == 1) {
							$south = 10;
						} else {
							$south = 90;
						}	
					}					
				} else {
					$south = 90;
					addToDebugLog("checkDirection(): We're at the southernmost edge of the map");
				}
				$return_value = $south;
				break;
		}
	
		addToDebugLog("checkDirection(): " . ucfirst($direction) . " neighbour path allowed? (1xxx = yes, 9xxx = no): " . $return_value);
	
		return $return_value;		
		
	}
	
	function displayJournal($journey_id) {
		
		// Displays the latest journal entries for this journey
	
		addToDebugLog("displayJournal(): Function Entry - supplied parameters: Journey ID: " . $journey_id);
		
		$entries = 6;
		
		// Get Journey Name
		$journey_name = getJourneyDetails($journey_id, "journey_name");
		
		echo "<table cellpadding=2 cellspacing=0 border=0 width=500px>";
		echo "<tr><td colspan=3 align=center><b>" . $journey_name . "</tr>";
		echo "<tr bgcolor=#ddd><td align=center>Entry No.<td align=center>Grid ID<td>Entry</tr>";
		
		// Display the N latest journal entries for this journey
		$sql = "SELECT journal_id, grid_id, journal_details FROM hackcess.journal WHERE journey_id = " . $journey_id . " ORDER BY journal_id DESC LIMIT " . $entries . ";";
		addToDebugLog("getJourneyDetails(): Constructed query: " . $sql);
		$result = search($sql);

		for ($j = 0; $j < 5; $j++) {
			echo "<tr><td align=center>" . $result[$j][0] . "<td align=center>" . $result[$j][1] . "<td>" . $result[$j][2] . "</tr>";
		}
		
		echo "</table>";

	}
	
	function getJourneyDetails($journey_id, $attribute) {
		
		// Returns the request detail for this journey
	
		addToDebugLog("getJourneyDetails(): Function Entry - supplied parameters: Journey ID: " . $journey_id . ", Attribute: " . $attribute);		

		$sql = "SELECT " . $attribute . " FROM hackcess.journey WHERE journey_id = " . $journey_id . " LIMIT 1;";
		addToDebugLog("getJourneyDetails(): Constructed query: " . $sql);
		$result = search($sql);
		$attribute = $result[0][0];
		
		return $attribute;
		
	}
	
	function chooseFeature($journey_id, $character_id, $grid_id) {

		// Decide feature for supplied grid
	
		addToDebugLog("chooseFeature(): Function Entry - supplied parameters: Grid ID: " . $grid_id);	
		
		srand(make_seed());
		$feature_choice = rand(1, 4);
		switch ($feature_choice) {
			case 1: // Fight
				addToDebugLog("chooseFeature(): Feature Choice: Fight");
				srand(make_seed());
				$fight = rand(1, 5);
				if ($fight == 5) {
					// Redirect to the battle page
					echo "<script>window.location.href = 'battle.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&grid_id=" . $grid_id . "&action=create'</script>";
				}
				break;
			case 2: // Store
				addToDebugLog("chooseFeature(): Feature Choice: Store");
				srand(make_seed());
				$store = rand(1, 20);
				if ($fight == 20) {
					$feature_id = generateFeature($grid_id, "store");
					addToDebugLog("chooseFeature(): Created feature, ID: " . $feature_id);
				}
				break;			
			case 3: // Stranger
				addToDebugLog("chooseFeature(): Feature Choice: Stranger");
				srand(make_seed());
				$stranger = rand(1, 30);
				if ($stranger == 30) {
					$feature_id = generateFeature($grid_id, "stranger");
				}
				break;
			case 4: // Teleport
				addToDebugLog("chooseFeature(): Feature Choice: Teleport");
				srand(make_seed());
				$teleport = rand(1, 30);
				if ($teleport == 30) {
					
					// Choose which grid to teleport to
					$radius = 7;
					$teleport_to_grid_id = generateRandomGrid($grid_id, $radius, $journey_id);
					addToDebugLog("chooseFeature(): New Grid ID: " . $teleport_to_grid_id);

					// Create Feature
					$feature_id = generateFeature($grid_id, "Teleport");
					addToDebugLog("chooseFeature(): Feature ID: " . $feature_id);
					
					// Teleport Player
					jump($journey_id, $character_id, $teleport_to_grid_id);
					
					// 
				}
				break;	
		}
		
	}
	
	function generateFeature($grid_id, $feature) {
		
		// Generates features for supplied grid
	
		addToDebugLog("generateFeature(): Function Entry - supplied parameters: Grid ID: " . $grid_id . ", Feature Type: " . $feature);

		// Create Feature record
		$dml = "INSERT INTO hackcess.features (grid_id, feature_details) VALUES (" . $grid_id . ", '" . $feature . "');";
		addToDebugLog("generateFeature(): Constructed query: " . $dml);
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("generateFeature(): Feature generated");
		} else {
			addToDebugLog("generateFeature(): ERROR: Feature not generated");
		}
		
		// Return Feature ID
		$sql = "SELECT feature_id FROM hackcess.features WHERE grid_id = " . $grid_id . " ORDER BY feature_id DESC LIMIT 1;";
		addToDebugLog("generateFeature(): Constructed query: " . $sql);
		$result = search($sql);
		$feature_id = $result[0][0];
		
		return $feature_id;
		
	}
	
	function generateRandomGrid($grid_id, $radius, $journey_id) {
		
		// Creates a random new grid with radius of provided grid
	
		addToDebugLog("generateRandomGrid(): Function Entry - supplied parameters: Grid ID: " . $grid_id . ", Radius: " . $radius);
		
		// Get coordinates of provided grid id
		$coordinates = getCoordinatesByGridID($grid_id);
		$grid_x = $coordinates[0][0];
		$grid_y = $coordinates[0][1];
		addToDebugLog("generateRandomGrid(): Grid ID " . $grid_id . "'s coordinates: " . $grid_x . "," . $grid_y);

		$exists = 1;
		
		while ($exists > 0) {
			
			// Generate new coordinates
			srand(make_seed());		
			$random_grid_x = rand($grid_x - $radius, $grid_x + $radius);
			$random_grid_y = rand($grid_y - $radius, $grid_y + $radius);
			
			// Check if there is already a grid with these coordinates
			$exists = getGridIDByCoordinates();
			
		}
		
		// Determine available directions at new grid
		$directions = 9999;
		while ($directions > 9000) {
			$north = random9or1() * 1000;
			$east = random9or1() * 100;
			$south = random9or1() * 10;
			$west = random9or1();
			$directions = $north + $east + $south + $west;
		}
		addToDebugLog("generateRandomGrid(): Available directions at the new grid: " . $directions);
				
		// Create new grid with new coordinates
		$new_grid_id = writeGrid($random_grid_x, $random_grid_y, $directions, $journey_id);
		addToDebugLog("generateRandomGrid(): New Grid ID: " . $new_grid_id);
		
		return $new_grid_id;
		
	}
	
	function random9or1() {
		
		// Randomly returns 9 or 1 (for the purposes of generating map directions
		
		srand(make_seed());
		$value = 9 * rand(0, 1);
		if ($value == 0) {
			$value = 1000;
		}
		
		return $value;
		
	}

	function writeGrid($x, $y, $directions, $journey_id) {
		
		// Writes a new grid record, and returns the grid id
	
		addToDebugLog("writeGrid(): Function Entry - supplied parameters: Grid X: " . $x . ", Grid Y: " . $y . ", Directions: " . $directions . ", Journey ID: " . $journey_id);		
		
		// Create new grid record
		$dml = "INSERT INTO hackcess.grid (grid_x, grid_y, directions, journey_id) VALUES (" . $x . ", " . $y . ", " . $directions . ", " . $journey_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("writeGrid(): New grid generated");
		} else {
			addToDebugLog("writeGrid(): ERROR: New grid not generated");
		}
		
		// Get new grid ID
		$sql = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $journey_id . " ORDER BY grid_id DESC LIMIT 1;";
		addToDebugLog("writeGrid(): Constructed query: " . $sql);
		$result = search($sql);
		$grid_id = $result[0][0];
		addToDebugLog("writeGrid(): New grid ID (Newly created grid): " . $grid_id);
		
		return $grid_id;
		
	}
	
	function isEnemyHere($grid_id, $character_id) {
		
		// REturns boolean if there is a live enemy at supplied grid
	
		addToDebugLog("isEnemyHere(): Function Entry - supplied parameters: Grid ID: " . $grid_id . ", Character ID: " . $character_id);			
		
		$sql = "SELECT enemy_id FROM hackcess.enemy WHERE grid_id = " . $grid_id . " AND character_id = " . $character_id . " AND status = 'Alive';";
		addToDebugLog("isEnemyHere(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		if ($rows > 0) {
			return $return[0][0]; // Enemy present
			addToDebugLog("isEnemyHere(): Enemy found");
		} else {
			return 0; // Enemy not present
			addToDebugLog("isEnemyHere(): Enemy not found");
		}
		
	}

	function doFight($character_id, $enemy_id, $grid_id, $player_id, $journey_id) {
		
		// Handles combat
	
		addToDebugLog("doFight(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Enemy ID: " . $enemy_id . ", Grid ID: " . $grid_id . ", Player ID: " . $player_id . ", Journey ID: " . $journey_id);			
		
		// Load Character Details
		$character_basic_info = getAllCharacterMainInfo($character_id);
		$character_name = trim($character_basic_info[0][2]); 		// 2	Name
		$character_role = $character_basic_info[0][3]; 				// 3	Role
		$character_level = $character_basic_info[0][4]; 			// 4	Level
		$character_status = $character_basic_info[0][7]; 			// 7	Status
		$character_detailed_info = getAllCharacterDetailedInfo($character_id);
		$character_hp = $character_detailed_info[0][2]; 			// 2	HP
		$character_atk = $character_detailed_info[0][3];			// 3	ATK
		$character_ac = $character_detailed_info[0][4];				// 4	AC
		$character_boosts = getCharacterBoosts($character_id);
		$character_ac_boost = $character_boosts[0];					// 0	AC Boost
		$character_atk_boost = $character_boosts[1];				// 1	ATK Boost
		
		// Load Enemy Details
		$enemy_info = getEnemyInfo($enemy_id);
		$enemy_name = $enemy_info[0][0];	// 0	Name
		$enemy_atk = $enemy_info[0][1];// 1	ATK
		$enemy_ac = $enemy_info[0][2];// 2	AC
		$enemy_hp = $enemy_info[0][3];// 3	HP
		$enemy_gold = ($enemy_atk + $enemy_ac + $enemy_hp) * 3;
		$enemy_xp = ($enemy_atk + $enemy_ac + $enemy_hp) * 2;

		echo "<table cellpadding=3 cellspacing=0 border=1 width=100%>";
		echo "<tr><td>";
		echo "<td align=center><h2>" . $character_name . ", Level " . $character_level . " " . $character_role . "</h2>";
		echo "(HP: " . $character_hp . ", ATK: " . $character_atk . " + " . $character_atk_boost . ", AC: " . $character_ac . " + " . $character_ac_boost . ")";
		echo "<td align=center><h2>" . $enemy_name . "</h2>";
		echo "(HP: " . $enemy_hp . ", ATK: " . $enemy_atk . ", AC: " . $enemy_ac . ")</tr>";
		
		// Run fight
			// For each round, Character rand(0,ATK)+Boosts vs Enemy rand(0,AC)
				// if hit, damage = rand(0, ATK/4)
				// repeat for enemy
		$round = 0;
		while ($character_hp > 0 && $enemy_hp > 0) { // start round if both combatants still stand

			$round++;
			addToDebugLog("Fight.php: Round: " . $round);

			// Character attacks first
			$character_attack = rand(0, $character_atk) + $character_atk_boost;
			addToDebugLog("Fight.php: - Character Attack: " . $character_attack);
			$enemy_defend = rand(0, $enemy_ac);
			addToDebugLog("Fight.php: - Enemy Defend: " . $enemy_defend);
			
			echo "<tr><td width=100px align=center>Round " . $round . "<td width=700px>" . $character_name . " attacks " . $enemy_name;
			
			if ($character_attack > $enemy_defend) { // Hit
				addToDebugLog("Fight.php: - Character hits Enemy");
				$enemy_damage = rand(1, ($character_atk + $character_atk_boost)/3);
				addToDebugLog("Fight.php: - Enemy takes damage: " . $enemy_damage);
				$enemy_hp = $enemy_hp - $enemy_damage;
				addToDebugLog("Fight.php: - Enemy HP reduced to: " . $enemy_hp);
				echo ", and hits for " . $enemy_damage . " points of damage!<br/>" . $enemy_name . " now has " . $enemy_hp . "HP";
			} else {
				echo ", and misses!<br/>" . $enemy_name . " still has " . $enemy_hp . "HP";
				addToDebugLog("Fight.php: - Enemy HP remains at: " . $enemy_hp);
			}
			
			// Check if enemy still standing
			if ($enemy_hp > 0) {
				
				$enemy_attack = rand(0, $enemy_atk);
				addToDebugLog("Fight.php: - Enemy Attack: " . $enemy_attack);
				$character_defend = rand(0, $character_ac + $character_ac_boost) ;
				addToDebugLog("Fight.php: - Character Defend: " . $character_defend);
				
				echo "<td width=700px>" . $enemy_name . " attacks " . $character_name;
				
				if ($enemy_attack > $character_defend) { // Hit
					addToDebugLog("Fight.php: - Enemy hits Character");
					$character_damage = rand(ceil($enemy_attack/4), $enemy_attack/2);
					addToDebugLog("Fight.php: - Character takes damage: " . $character_damage);
					$character_hp = $character_hp - $character_damage;
					addToDebugLog("Fight.php: - Character HP reduced to: " . $character_hp);
					echo ", and hits for " . $character_damage . " points of damage!<br/>" . $character_name . " now has " . $character_hp . "HP";
				} else {
					echo ", and misses!<br/>" . $character_name . " still has " . $character_hp . "HP";
					addToDebugLog("Fight.php: - Character HP remains at: " . $character_hp);
				}	
				echo "</tr>";

			} else {
				echo "<td>" . $enemy_name . " is defeated!</tr>";
			}
			
		}
		
		echo "<tr><td colspan=3 align=center><h2>";
		if ($character_hp <= 0) { // character died
			echo $character_name . " has been defeated! May their legend never die.";
			$winner = "enemy";
			echo "</h2></tr></table>";	
			//return "enemy," . $round;
		} else { // enemy died
			echo $enemy_name . " has been defeated, and good riddance to that scum!";
			$winner = "character";
			echo "</h2></tr></table>";	
			//return "character," . $round;
		}

		// Record fight (new table)
		$dml = "INSERT INTO hackcess.fight (character_id, enemy_id, grid_id, rounds) VALUES (" . $character_id . ", " . $enemy_id . ", " . $grid_id . ", " . $round . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("doFight(): Fight entry added");
		} else {
			addToDebugLog("doFight(): ERROR: Fight entry not added");
		}	
		
		if ($winner == "enemy") { // Enemy wins
			// Player dead
			$dml = "UPDATE hackcess.character SET status = 'Dead' WHERE character_id = " . $character_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("doFight(): Character record updated");
			} else {
				addToDebugLog("doFight(): Character record not updated");
			}	
			
			// Create Descendant
			$new_character_id = createCharacter($player_id, $character_id);
			
			// Assign descendent some gold and best piece of equipment
			// Get predecessor gold
			$gold = getCharacterDetailsInfo($character_id, "gold");
			$xp = getCharacterDetailsInfo($character_id, "xp");
			// Get predecessor best item
			$best_item_id = getBestItem($character_id);
			
			// Update new character with fraction of predecessor gold and xp
			$new_gold = round($gold/3, 0);
			$new_xp = round($xp/3, 0);
			$dml = "UPDATE hackcess.character_details SET gold = " . $new_gold . ", xp = " . $new_xp . " WHERE character_id = " . $new_character_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("doFight(): Character record updated");
			} else {
				addToDebugLog("doFight(): Character record not updated");
			}			

			// Update new character with predecessor item
			$dml = "UPDATE hackcess.character_equipment SET character_id = " . $new_character_id . " WHERE equipment_id = " . $best_item_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("doFight(): Character record updated");
			} else {
				addToDebugLog("doFight(): Character record not updated");
			}
			
			// Get summary of inherited weapon
			$item_summary = getItemSummary($best_item_id);
			
			// Display details of new Character
			$new_character_name = getCharacterDetails($new_character_id, "character_name");
			echo "<p>" . $character_name . " might feast with the gods, but their child, " . trim($new_character_name) . ", shall continue the fight!";
			
			echo "<p>" . trim($new_character_name) . " inherits " . $new_gold . " gold from their ancestor, as well as " . $character_name . "'s " . $item_summary;
			
			// Back to Character Select
			echo "<p><a href='character.php?player_id=" . $player_id . "'>Back to Character Select</a>";
			
		} else { // Character wins
			// Kill enemy
			$dml = "UPDATE hackcess.enemy SET status = 'Dead' WHERE enemy_id = " . $enemy_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("doFight(): Enemy record updated");
			} else {
				addToDebugLog("doFight(): Enemy record not updated");
			}				
			
			// Give Gold / XP
			$dml = "UPDATE hackcess.character_details SET gold = gold + " . $enemy_gold . ", xp = xp + " . $enemy_xp . " WHERE character_id = " . $character_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("doFight(): Character record updated");
			} else {
				addToDebugLog("doFight(): Character record not updated");
			}			
			
			// Give random item if player has enough strength left
			$character_strength = getCharacterDetailsInfo($character_id, 'strength');
			$equipment_weight = equipmentWeight($character_id);

			if ($equipment_weight < $character_strength) {
				$details = createRandomItem($character_id, $character_ac, $character_atk);
				$details = $details . ", which " . $character_name . " picks up.";
			} else {
				$details = "piece of equipment which " . $character_name . " can't pick up as  they're carrying too much equipment.";
			}
			
			// Output details
			
			echo "<p>" . $enemy_name . " drops " . $enemy_gold . " gold and a " . $details . "<br/>";
			echo $character_name . " also gains " . $enemy_xp . "XP.<p>";
			echo "<a href='equipment.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "'>Show Equipment</a><br/>"; // Show Equipment screen
			echo "<a href='adventure.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "'>Back to Adventure</a>"; // Back to Adventure
		
		}
		
	}
	
?>
