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

	function drawGrid($grid_x, $grid_y, $radius_x, $radius_y, $journey_id, $character_id) {

		// Draws the adventure grid
	
		addToDebugLog("drawGrid(): Function Entry - supplied parameters: Grid X: " . $grid_x . ", Grid Y: " . $grid_y . ", Radius X: " . $radius_x . ", Radius Y: " . $radius_y . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id);	
		
		// Draw grid for 5 square radius around current position
		$start_x = $grid_x - $radius_x;
		$start_y = $grid_y + $radius_y;
		addToDebugLog("drawGrid(): Grid Start Coordinates: " . $start_x . "," . $start_y);
		
		$rows = (2 * $radius_y) + 1;
		$cols = (2 * $radius_x) + 1;
		
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
				if ($current_x > 0 && $current_x <= 50 && $current_y <= 50 && $current_y > 0) {
					if ($grid_x == $current_x && $grid_y == $current_y) {
						$class = "current";
					} elseif ($current_x == 25 && $current_y == 1) {
						$class = "start";
					} else {
						$class = "normal";
					}
					
					// Determine which tile image to show
					$directions = getGridDirectionsByCoordinates($current_x, $current_y, $journey_id);
					addToDebugLog("drawGrid(): Directions: " . $directions);
					$grid_id = getGridIDByCoordinates($current_x, $current_y, $journey_id);
					addToDebugLog("drawGrid(): Grid ID: " . $grid_id);
					
					echo "<td class='" . $class . "' height='25' width=25px bgcolor='" . $color . "' title='" . $current_x . "," . $current_y . " " . $grid_id . "'>";
					if ($directions != '9999') {
						echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&jump=true'>";
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
	
	function drawControls($grid_id, $journey_id, $character_id) {

		// Draws the controls grid
	
		addToDebugLog("drawControls(): Function Entry - supplied parameters: Grid ID: " . $grid_id);

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
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=north'><img src='images/north.png' alt='North' title='Go North' border=0></a>";
		} else {
			echo "<img src='images/9191.png' border=0>";
		}
		echo "<td class='controls'><img src='images/9911.png'></tr>";
		echo "<tr><td class='controls'>";
		if ($west == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=west'><img src='images/west.png' alt='West' title='Go West' border=0></a>";
		} else {
			echo "<img src='images/1919.png' border=0>";
		}
		echo "<td class='controls'><img src='images/center.png' alt='Rose'><td class='controls'>";
		if ($east == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=east'><img src='images/east.png' alt='East' title='Go East' border=0></a>";
		} else {
			echo "<img src='images/1919.png' border=0>";
		}
		echo "</tr>";
		echo "<tr><td class='controls'><img src='images/1199.png'><td class='controls'>";
		if ($south == 1) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&grid_id=" . $grid_id . "&direction=south'><img src='images/south.png' alt='South' title='Go South' border=0></a>";
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
			addToDebugLog("move(): New grid ID (Newly created grid): " . $grid_id);
			
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
		
	}
	
	function jump($journey_id, $character_id, $grid_id) {

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

	}
	
	
	function getGridIDByCoordinates($grid_x, $grid_y, $journey_id) {

		// Returns the grid id for the supplied grid coordinates
	
		addToDebugLog("getGridDirectionsByCoordinates(): Function Entry - supplied parameters: Grid X: " . $grid_x . ", Grid Y: " . $grid_y . ", Journey ID: " . $journey_id);	
 	
		$sql = "SELECT grid_id FROM hackcess.grid WHERE grid_x = " . $grid_x . " AND grid_y = " . $grid_y . " AND journey_id = " . $journey_id . ";";
		addToDebugLog("getGridDirectionsByCoordinates(): Constructed query: " . $sql);
		$result = search($sql);

		
		return $result[0][0];	
	
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
		echo "<tr><td align=center>Entry No.<td align=center>Grid ID<td>Entry</tr>";
		
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
	
?>