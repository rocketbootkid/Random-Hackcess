<?php

	function getPlayerCurrentGrid($character_id, $journey_id) {
		
		// Returns the grid id for current character / journey
	
		addToDebugLog("getPlayerCurrentGrid(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Journey ID: " . $journey_id);	
 	
		// Determine first what grid square we're on, based on current player and journey
		$sql = "SELECT character_grid_id FROM hackcess.character WHERE character_id = " . $character_id . " AND current_journey_id = " . $journey_id . ";";
		addToDebugLog("getPlayerCurrentGrid(): Constructed query: " . $sql);
		$result = search($sql);
		$grid_id = $result[0][0];
		
		$sql = "SELECT grid_x, grid_y FROM hackcess.grid WHERE grid_id = " . $grid_id . ";";
		addToDebugLog("getPlayerCurrentGrid(): Constructed query: " . $sql);
		$result = search($sql);		
		
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
	
		addToDebugLog("getGridDirectionsByID(): Function Entry - supplied parameters: Grid ID: " . $character_id);	
 	
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
	
?>