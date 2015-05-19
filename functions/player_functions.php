<?php

	function displayPlayers() {
		
		// Displays details for all players
	
		addToDebugLog("displayPlayers(): Function Entry");	
		
		$sql = "SELECT * FROM hackcess.user;";
		addToDebugLog("getPlayerCurrentGridCoordinates(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);

		for ($u = 0; $u < $rows; $u++) {		
			echo "<h2>" . ucfirst($result[$u][1]) . "</h2>";
			
			// Display Characters
			$sqlc = "SELECT * FROM hackcess.character WHERE player_id = " . $result[$u][0] . ";";
			addToDebugLog("getPlayerCurrentGridCoordinates(): Constructed query: " . $sqlc);
			$resultc = search($sqlc);
			$rowsc = count($resultc);
			
			echo "<table class='characters' cellpadding=3 cellspacing=0 border=1>";
			echo "<tr bgcolor=#ddd><td class='characters'>Name<td class='characters'>Class<td class='characters'>XP<td class='characters'>Current Journey<td>New Journey</tr>";
			for ($c = 0; $c < $rowsc; $c++) {
				echo "<tr><td class='characters'>" . $resultc[$c][2];
				echo "<td class='characters'>" . $resultc[$c][3];
				echo "<td class='characters'>" . $resultc[$c][4];
				echo "<td class='characters'><a href='adventure.php?journey_id=" . $resultc[$c][6] . "&character_id=" . $resultc[$c][0] . "'>Embark upon journey</a>";
				echo "<td class='characters'><a href='start.php?create=journey?player_id=" . $result[$u][0] . "&character_id=" . $resultc[$c][0] . "'>Create new journey</a></a>";
				echo "</tr>";
			}
			
			echo "<tr><td colspan=5><a href='start.php?create=character&player_id=" . $result[$u][0] . "'>Create new character</a></tr>";
			echo "</table><p>";
			
			// List of Journeys
			$sqlj = "SELECT * FROM hackcess.journey WHERE player_id = " . $result[$u][0] . ";";
			addToDebugLog("getPlayerCurrentGridCoordinates(): Constructed query: " . $sqlj);
			$resultj = search($sqlj);
			$rowsj = count($resultj);
			
			echo "<table class='characters' cellpadding=3 cellspacing=0 border=1>";
			echo "<tr bgcolor=#ddd><td>Journey<td>Action</tr>";
			for ($j = 0; $j < $rowsj; $j++) {

				echo "<tr><td align=center>" . $resultj[$j][0];
			
				// Determine current journey
				$journey_id = playerCurrentJourney($result[$u][0]);
				
				// Either set current journey, or embark on it
				if ($journey_id == $resultj[$j][0]) {  // current journey
					echo "<td>Current Journey</tr>";	
				} else { // Not current journey					
					echo "<td><a href='start.php?journey_id=" . $resultj[$j][0] . "&character_id=" . $resultj[$j][2] . "'>Switch to this journey</a></tr>";		
				}
			}
			echo "</table><p>";

		}
		
	}

	function playerCurrentJourney($player_id) {
		
		// Returns current journey id for supplied player
	
		addToDebugLog("playerCurrentJourney(): Function Entry - supplied parameters: Player ID: " . $player_id);	
		
		$sql = "SELECT current_journey_id FROM hackcess.character WHERE player_id = " . $player_id . ";";
		addToDebugLog("getPlayerCurrentGridCoordinates(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result[0][0];
		
	}

	function changeJourney($journey_id, $character_id) {
		
		// Changes journey for supplied character
	
		addToDebugLog("changeJourney(): Function Entry - supplied parameters: Journey ID: " . $journey_id . ", Character ID: " . $character_id);
		
		// Determine the last grid visited / created
		$grid_id = getLastLocation($journey_id, $character_id);
					
		// Update player to that grid / journey
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . ", current_journey_id = " . $journey_id . " WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("move(): Character record updated");
		} else {
			addToDebugLog("move(): Character record not updated");
		}	
		
	}
	
	function getLastLocation($journey_id, $character_id) {
		
		// Determines last location for certain journey
	
		addToDebugLog("getLastLocation(): Function Entry - supplied parameters: Journey ID: " . $journey_id . ", Character ID: " . $character_id);

		$sql = "SELECT grid_id FROM hackcess.journal WHERE journey_id = " . $journey_id . " AND character_id = " . $character_id . " ORDER BY journal_id DESC LIMIT 1;";
		addToDebugLog("getLastLocation(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result[0][0];
		
	}
	
	function newJourney() {
		
		
		// Create Journey (player, character)
		
		
		// Create grid (journey id)
		
		
		// Update Character (grid id, journey id)
		
		
		
		
		
	}
	
?>