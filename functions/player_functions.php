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
				echo "<td class='characters'><a href='start.php?create=journey&player_id=" . $result[$u][0] . "&character_id=" . $resultc[$c][0] . "'>Create new journey</a></a>";
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
			echo "<tr bgcolor=#ddd><td>Journey<td>Grids<td>Action</tr>";
			for ($j = 0; $j < $rowsj; $j++) {

				echo "<tr><td>" . $resultj[$j][0] . ": " . $resultj[$j][3];
			
				// Determine current journey
				$journey_id = playerCurrentJourney($result[$u][0]);
				
				// Determine how many grids exist for the journey
				$sqlg = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $resultj[$j][0] . ";";
				addToDebugLog("displayPlayers(): Constructed query: " . $sqlg);
				$resultg = search($sqlg);
				$rowsg = count($resultg);				
				echo "<td align=center>" . $rowsg . "/2500";
				
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
	
	function newJourney($player_id, $character_id) {

		// Creates a new journey for the supplied player / character
	
		addToDebugLog("newJourney(): Function Entry - supplied parameters: Journey ID: " . $journey_id . ", Character ID: " . $character_id);
	
		// Create Journey Name
		$journey_name = createJourneyName();
		
		// Create Journey (player, character)
		$dml = "INSERT INTO hackcess.journey (player_id, character_id, journey_name) VALUES (" . $player_id . ", " . $character_id . ", '" . $journey_name . "');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("newJourney(): New journey added");
		} else {
			addToDebugLog("newJourney(): ERROR: New journey not added");
		}
		
		// Get new journey id
		$sql = "SELECT journey_id FROM hackcess.journey WHERE player_id = " . $player_id . " AND character_id = " . $character_id . " ORDER BY journey_id DESC LIMIT 1;";
		addToDebugLog("newJourney(): Constructed query: " . $sql);
		$result = search($sql);
		$journey_id = $result[0][0];
		
		// Create grid (journey id)
		$dml = "INSERT INTO hackcess.grid (grid_x, grid_y, directions, journey_id) VALUES (25, 1, 1191, " . $journey_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("newJourney(): New grid added");
		} else {
			addToDebugLog("newJourney(): ERROR: New grid not added");
		}	
	
		// Get new grid id
		$sql = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $journey_id . " LIMIT 1;";
		addToDebugLog("newJourney(): Constructed query: " . $sql);
		$result = search($sql);
		$grid_id = $result[0][0];
		
		// Update Character (grid id, journey id)
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . ", current_journey_id = " . $journey_id . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result== TRUE) {
			addToDebugLog("newJourney(): Character record updated");
		} else {
			addToDebugLog("newJourney(): Character record not updated");
		}
		
	}
	
	function createCharacter($player_id, $parent_character_id) {
		
		
		
		
		
		
		
		
		
		
		
	}
	
	function createJourneyName() {
	
		// Creates and returns a journey name
	
		addToDebugLog("createJourneyName(): Function Entry - no parameters");
		
		$syllables = rand(2, 4);
		$name = "";
		
		for ($a = 0; $a < $syllables; $a++) {

			$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "y", "z"); 
			$consonant = $consonants[rand(0, 20)];
			
			$vowels = array("a", "e", "i", "o", "u");
			$vowel = $vowels[rand(0, 4)];
			
			$name = $name . $consonant . $vowel;

		}
		$name = ucfirst($name);
		
		$locations = array("Forest", "Mountains", "Swamps", "Castle", "Dungeons", "Catacombs", "Wasteland", "Desert", "Frozen wastes", "Caverns", "Hills", "Fortress", "Wilderness", "Jungle");
		$length = count($locations);
		$location = $locations[rand(0, $length)];
		
		$final_name = "The " . $location . " of " . $name;
		addToDebugLog("- createJourneyName(): Generated journey name: " . $final_name);
		
		return $final_name;		
	
	}
	
?>