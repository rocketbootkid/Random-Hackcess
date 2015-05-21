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
			echo "<tr bgcolor=#ddd><td class='characters'>Name<td class='characters'>Class<td class='characters'>Level<td class='characters'>Current Journey<td>New Journey</tr>";
			for ($c = 0; $c < $rowsc; $c++) {
				echo "<tr><td class='characters'>" . $resultc[$c][2];
				echo "<td class='characters'>" . $resultc[$c][3];
				echo "<td class='characters' align=center>" . $resultc[$c][4];
				$journey_name = getJourneyDetails($resultc[$c][6], 'journey_name');
				echo "<td class='characters'><a href='adventure.php?journey_id=" . $resultc[$c][6] . "&character_id=" . $resultc[$c][0] . "'>Journey to " . $journey_name . "</a>";
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
		
		// Create new journal entry
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", 'Dropped off at 25,1 in " . $journey_name . "');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("newJourney(): New journal entry added");
		} else {
			addToDebugLog("newJourney(): ERROR: New journal entry not added");
		}		
		
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
	
	function displayPlayerInformation($character_id) {
		
		// Displays the latest journal entries for this journey
	
		addToDebugLog("displayPlayerInformation(): Function Entry - supplied parameters: Character ID: " . $character_id);
		
		// Get Character Basic details
		$sql = "SELECT character_name, character_role, character_level FROM hackcess.character WHERE character_id = " . $character_id . ";";
		addToDebugLog("displayPlayerInformation(): Constructed query: " . $sql);
		$result = search($sql);
		$name = $result[0][0];
		$role = $result[0][1];
		$level = $result[0][2];
		
		echo "<table cellpadding=2 cellspacing=0 border=0 width=500px>";
		echo "<tr><td colspan=3 align=center><b>" . $name . ", Level " . $level . " " . $role . "</tr>";
		echo "<tr><td align=center>Stats<td align=center>Value<td>Slot<td>Item</tr>";
		
		// Get Character Details
		$sql = "SELECT * FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		addToDebugLog("displayPlayerInformation(): Constructed query: " . $sql);
		$result = search($sql);

		echo "<tr><td>HP<td align=center>" . $result[0][2] . "<td>Head<td>" . $result[0][8] . "</tr>";
		echo "<tr><td>ATK<td align=center>" . $result[0][3] . "<td>Chest<td>" . $result[0][9] . "</tr>";
		echo "<tr><td>AC<td align=center>" . $result[0][4] . "<td>Legs<td>" . $result[0][10] . "</tr>";
		echo "<tr><td>Gold<td align=center>" . $result[0][5] . "<td>Shield<td>" . $result[0][11] . "</tr>";
		echo "<tr><td>XP<td align=center>" . $result[0][6] . "<td>Weapon<td>" . $result[0][12] . "</tr>";
		echo "<tr><td>STR<td align=center>" . $result[0][7] . "<td colspan=2></tr>";
		
		echo "</table>";
		
		
	}		

	function updatePlayerOnMove($character_id, $grid_id, $journey_id) {

		// Manage player changes on move
	
		addToDebugLog("updatePlayerOnMove(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Grid ID: " . $grid_id . ", Journey ID: " . $journey_id);	
	
		// Move player location to new grid square
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . " WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("updatePlayerOnMove(): Character record updated");
		} else {
			addToDebugLog("updatePlayerOnMove(): Character record not updated");
		}		

		// Update player XP
		$dml = "UPDATE hackcess.character_details SET xp = xp + 10 WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("updatePlayerOnMove(): Character details updated");
		} else {
			addToDebugLog("updatePlayerOnMove(): Character details not updated");
		}
		
		// Get player XP
		$sql = "SELECT xp FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		addToDebugLog("updatePlayerOnMove(): Constructed query: " . $sql);
		$result = search($sql);
		$xp = $result[0][0];

		// Get player current level
		$sql = "SELECT character_level FROM hackcess.character WHERE character_id = " . $character_id . ";";
		addToDebugLog("updatePlayerOnMove(): Constructed query: " . $sql);
		$result = search($sql);
		$level = $result[0][0];

		$new_level = $xp / $level;
		if ($new_level >= 1000) {
			// Increase stats
			$dml = "UPDATE hackcess.character_details SET hp = hp + 1, armor_class = armor_class + 1, attack = attack + 1, strength = strength + 2 WHERE character_id = " . $character_id . ";";
			$resultdml = insert($dml);
			if ($resultdml == TRUE) {
				addToDebugLog("updatePlayerOnMove(): Character details updated");
			} else {
				addToDebugLog("updatePlayerOnMove(): Character details not updated");
			}				
			
			// Increase level
			$dml = "UPDATE hackcess.character SET character_level = character_level + 1 WHERE character_id = " . $character_id . ";";
			$resultdml = insert($dml);
			if ($resultdml == TRUE) {
				addToDebugLog("updatePlayerOnMove(): Character level updated");
			} else {
				addToDebugLog("updatePlayerOnMove(): Character level not updated");
			}
			
			// Get player current level
			$sql = "SELECT character_level, character_role, character_name FROM hackcess.character WHERE character_id = " . $character_id . ";";
			addToDebugLog("updatePlayerOnMove(): Constructed query: " . $sql);
			$result = search($sql);
			$level = $result[0][0];
			$role = $result[0][1];
			$name = $result[0][2];

			//	Add journal entry
			$details = "LEVEL UP! " . $name . " is now a Level " . $level . " " . $role;
			$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("move(): New grid generated");
			} else {
				addToDebugLog("move(): ERROR: New grid not generated");
			}
			
		}
	
	}
	
	function createCharacter($player_id, $parent_character_id) {
		
		// Creates a new character
	
		addToDebugLog("createCharacter(): Function Entry - supplied parameters: Player ID: " . $player_id . ", Parent Character ID: " . $parent_character_id);		
		
		// Create Character Name
		$name = generateCharacterName();
		
		// Create Role
		$role = generateRole();
		
		// Create record
		$dml = "INSERT INTO hackcess.character (player_id, character_name, character_role, character_level, status) VALUES (" . $player_id . ", '" . $name . "', '" . $role . "', 1, 'Alive');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createCharacter(): New character generated");
		} else {
			addToDebugLog("createCharacter(): ERROR: New character not generated");
		}
		
		// Get new Character id
		$sql = "SELECT character_id FROM hackcess.character WHERE player_id = " . $player_id . " ORDER BY character_id DESC LIMIT 1;";
		addToDebugLog("createCharacter(): Constructed query: " . $sql);
		$result = search($sql);
		$character_id = $result[0][0];

		// Generate character details / items
		generateCharacterStatsItems($character_id);
		
		// Create Journey
		newJourney($player_id, $character_id);	
		
	}
	
	function generateCharacterName() {
		
		// Creates and returns a character name
	
		addToDebugLog("createJourneyName(): Function Entry - no parameters");
		
		// Determine the consonant to use for the name
		$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "y", "z"); 
		$consonant = $consonants[rand(0, 20)];
			
		// Choose the Name based on the consonant
		$filepath = "lists/names/" . $consonant . ".txt";
		$names = file($filepath); // reads contents of select file into the array
		$names_length = count($names);
		$name = "";
		while ($name == "") {
			srand(make_seed());
			$name = $names[rand(0, $names_length)];
		}
		
		// Choose the title based on the consonant
		$filepath = "lists/adjectives/" . $consonant . ".txt";
		$titles = file($filepath); // reads contents of select file into the array
		$titles_length = count($titles);
		$title = "";
		while ($title == "") {
			srand(make_seed());
			$title = $titles[rand(0, $titles_length)];
		}
		
		$final_name = ucfirst($name) . " the " . ucfirst($title);
		
		return $final_name;
		
	}
	
	function generateRole() {
		
		// Creates and returns a character name
	
		addToDebugLog("generateRole(): Function Entry - no parameters");		

		// Determine the consonant to use for the role
		$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "z"); 
		$consonant = $consonants[rand(0, 20)];
			
		// Choose the Role based on the consonant
		$filepath = "lists/roles/" . $consonant . ".txt";
		$roles = file($filepath); // reads contents of select file into the array
		$roles_length = count($roles);
		$role = "";
		while ($role == "") {
			srand(make_seed());
			$role = $roles[rand(0, $roles_length)];
		}
		
		return ucfirst($role);
		
	}
	
	function make_seed() {
		
		// Seeds the random number generator for selection of names / roles
		
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	
	}
	
	function generateCharacterStatsItems($character_id) {

		// Generates stats / items for the new character
	
		addToDebugLog("generateCharacterStatsItems(): Function Entry - supplied parameters: Character ID: " . $character_id);
	
		// Create character items - Head
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Helm', 1, 0, 1, 'head', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Helm generated");
		} else {
			addToDebugLog("move(): ERROR: Helm not generated");
		}			

		// Create character items - Chest
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Chestplate', 1, 0, 1, 'chest', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Chestplate generated");
		} else {
			addToDebugLog("move(): ERROR: Chestplate not generated");
		}	

		// Create character items - Legs
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Trousers', 1, 0, 1, 'legs', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Trousers generated");
		} else {
			addToDebugLog("move(): ERROR: Trousers not generated");
		}
		
		// Create character items - Shield
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Shield', 1, 0, 1, 'shield', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Shield generated");
		} else {
			addToDebugLog("move(): ERROR: Shield not generated");
		}	

		// Create character items - Sword
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Sword', 0, 1, 1, 'sword', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Shield generated");
		} else {
			addToDebugLog("move(): ERROR: Shield not generated");
		}	
		
		// Get items ids for details table
		$sql = "SELECT equipment_id FROM hackcess.character_equipment WHERE character_id = " . $character_id . " ORDER BY character_id ASC;";
		addToDebugLog("createCharacter(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		if ($rows == 5) {
			$head = $result[0][0];
			$chest = $result[1][0];
			$legs = $result[2][0];
			$shield = $result[3][0];
			$sword = $result[4][0];
		} else {
			addToDebugLog("createCharacter(): ERROR: Couldn't get item ids");
		}
			
		// Create character details
		$dml = "INSERT INTO hackcess.character_details (character_id, hp, attack, armor_class, gold, xp, strength, head_slot, chest_slot, legs_slot, shield_slot, weapon_slot) VALUES (" . $character_id . ", 10, 5, 1, 0, 0, 20, " . $head . ", " . $chest . ", " . $legs . ", " . $shield . ", " . $sword . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Character details stored");
		} else {
			addToDebugLog("move(): ERROR: Character details not stored");
		}	
		
	}
	
?>