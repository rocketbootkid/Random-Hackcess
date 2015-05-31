<?php

	function playerSelect() {
		
		// Displays details for all players
	
		addToDebugLog("playerSelect(): Function Entry");	
		
		$sql = "SELECT * FROM hackcess.user;";
		addToDebugLog("playerSelect(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result); 

		echo "<table class='characters' cellpadding=3 cellspacing=0 border=1>";
		echo "<tr bgcolor=#ddd><td class='characters'>Player ID<td class='characters'>Name</tr>";
		
		for ($u = 0; $u < $rows; $u++) {
			echo "<tr><td align=center>" . $result[$u][0] . "<td><a href='character.php?player_id=" . $result[$u][0] . "'>" . ucfirst($result[$u][1]) . "</a></tr>";
		}		
		echo "</table>";
	}
	
	function characterSelect($player_id) {

		// Lists characters for the selected Player
	
		addToDebugLog("characterSelect(): Function Entry - supplied parameters: Player ID: " . $player_id);			
	
		// Get Player Name
		$name = getPlayerDetails($player_id, "username");
		echo "<h1>" . ucfirst($name) . "'s Characters</h1>";
		
		// Get List of Live Characters for this Player
		characterList($player_id, "alive");
	
		// Get list of dead / retired character for this player
		characterList($player_id, "dead");		
		
	}

	function getPlayerDetails($player_id, $attribute) {
		
		// Returns selected player attribute
	
		addToDebugLog("getPlayerDetails(): Function Entry - supplied parameters: Player ID: " . $player_id . ", Attribute: " . $attribute);	
		
		$sql = "SELECT " . $attribute . " FROM hackcess.user WHERE user_id = " . $player_id . ";";
		addToDebugLog("playerSelect(): Constructed query: " . $sql);
		$result = search($sql);

		return $result[0][0];
		
	}
	
	function characterList($player_id, $status) {
		
		// Lists characters with the right status for the selected Player
	
		addToDebugLog("characterList(): Function Entry - supplied parameters: Player ID: " . $player_id);			
		
		// Display Characters
		if ($status == "alive") {
			$sqlc = "SELECT * FROM hackcess.character WHERE player_id = " . $player_id . " AND status = 'Alive' ORDER BY character_level DESC;";	
		} elseif ($status == "dead") {
			$sqlc = "SELECT * FROM hackcess.character WHERE player_id = " . $player_id . " AND status != 'Alive' ORDER BY character_level DESC LIMIT 10;";
		}
		
		addToDebugLog("characterList(): Constructed query: " . $sqlc);
		$resultc = search($sqlc);
		$rowsc = count($resultc);
		
		if ($rowsc != 0) {
			echo "<table class='characters' cellpadding=3 cellspacing=0 border=1 width=500px>";
			echo "<tr bgcolor=#ddd><td class='characters'>Name<td class='characters'>Class<td class='characters' align=center>Level</tr>";
			for ($c = 0; $c < $rowsc; $c++) {
				if ($status == 'alive') {
					echo "<tr><td class='characters'><a href='journey.php?player_id=" . $player_id . "&character_id=" . $resultc[$c][0] . "'>" . $resultc[$c][2] . "</a>";
				} else {
					echo "<tr><td class='characters'>" . $resultc[$c][2];
				}
				echo "<td class='characters'>" . $resultc[$c][3];
				echo "<td class='characters' align=center>" . $resultc[$c][4];
				echo "</tr>";
			}
			if ($status == 'alive') {
				echo "<tr><td colspan=3><a href='character.php?create=character&player_id=" . $player_id . "'>Create new character</a></tr>";
			}
			echo "</table><p>";
		} else {
			if ($status == "alive") {
				echo "<table class='characters' cellpadding=3 cellspacing=0 border=1 width=500px>";
				echo "<tr><td>There are no live characters</tr>";
				echo "<tr><td><a href='character.php?create=character&player_id=" . $player_id . "'>Create new character</a></tr>";
				echo "</table><p>";
			} else {
				echo "There are no dead / retired characters";
			}
		}
		
	}
	
	function getCharacterDetails($character_id, $attribute) {
		
		// Returns selected player attribute
	
		addToDebugLog("getCharacterDetails(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Attribute: " . $attribute);	
		
		$sql = "SELECT " . $attribute . " FROM hackcess.character WHERE character_id = " . $character_id . ";";
		addToDebugLog("getCharacterDetails(): Constructed query: " . $sql);
		$result = search($sql);

		return $result[0][0];
		
	}

	function getAllCharacterMainInfo($character_id) {
		
		// Returns all main information for the selected player
	
		addToDebugLog("getAllCharacterDetails(): Function Entry - supplied parameters: Character ID: " . $character_id);	
		
		$sql = "SELECT * FROM hackcess.character WHERE character_id = " . $character_id . ";";
		addToDebugLog("getAllCharacterDetails(): Constructed query: " . $sql);
		$result = search($sql);

		return $result;
		
	}
	
	function getAllCharacterDetailedInfo($character_id) {
		
		// Returns all detailed information for the selected player
	
		addToDebugLog("getAllCharacterDetailedDetails(): Function Entry - supplied parameters: Character ID: " . $character_id);	
		
		$sql = "SELECT * FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		addToDebugLog("getAllCharacterDetailedDetails(): Constructed query: " . $sql);
		$result = search($sql);

		return $result;
		
	}
	
	function getCharacterDetailsInfo($character_id, $attribute) {
		
		// Returns selected player attribute
	
		addToDebugLog("getCharacterDetailsInfo(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Attribute: " . $attribute);	
		
		$sql = "SELECT " . $attribute . " FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		addToDebugLog("getCharacterDetailsInfo(): Constructed query: " . $sql);
		$result = search($sql);

		addToDebugLog("getCharacterDetailsInfo(): Attribute '" . $attribute . "' value: " . $result[0][0]);
		
		return $result[0][0];
		
	}
	
	function journeySelect($player_id, $character_id) {
		
		// Returns current journey id for supplied player
	
		addToDebugLog("journeySelect(): Function Entry - supplied parameters: Player ID: " . $player_id . ", Character ID: " . $character_id);			

		// List of Journeys
		$sqlj = "SELECT * FROM hackcess.journey WHERE player_id = " . $player_id . " AND character_id = " . $character_id . ";";
		addToDebugLog("journeySelect(): Constructed query: " . $sqlj);
		$resultj = search($sqlj);
		$rowsj = count($resultj);
		
		echo "<table class='characters' cellpadding=3 cellspacing=0 border=1>";
		echo "<tr bgcolor=#ddd><td>Journey<td>Grids<td>Action</tr>";
		for ($j = 0; $j < $rowsj; $j++) {

			echo "<tr><td>" . $resultj[$j][0] . ": " . $resultj[$j][3];
		
			// Determine current journey
			$journey_id = playerCurrentJourney($player_id, $character_id);
			addToDebugLog("journeySelect(): Current Journey ID: " . $journey_id);
			
			// Determine how many grids exist for the journey
			$sqlg = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $resultj[$j][0] . ";";
			addToDebugLog("displayPlayers(): Constructed query: " . $sqlg);
			$resultg = search($sqlg);
			$rowsg = count($resultg);				
			echo "<td align=center>" . $rowsg . "/2500";
			
			addToDebugLog("journeySelect(): Journey ID: " . $resultj[$j][0]);
			
			// Either set current journey, or embark on it
			if ($journey_id == $resultj[$j][0]) {  // current journey
				echo "<td><a href='adventure.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "'>Embark on Journey</a></tr>";	
			} else { // Not current journey					
				echo "<td><a href='journey.php?player_id=" . $player_id . "&character_id=" . $resultj[$j][2] . "&journey_id=" . $resultj[$j][0] . "'>Switch to this journey</a></tr>";		
			}
		}
		echo "<td colspan=3 class='characters'><a href='journey.php?create=journey&player_id=" . $player_id . "&character_id=" . $character_id . "'>Create new journey</a></a>";
		echo "</table><p>";		
		
	}
	
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

	function playerCurrentJourney($player_id, $character_id) {
		
		// Returns current journey id for supplied player
	
		addToDebugLog("playerCurrentJourney(): Function Entry - supplied parameters: Player ID: " . $player_id);	
		
		$sql = "SELECT current_journey_id FROM hackcess.character WHERE player_id = " . $player_id . " AND character_id = " . $character_id . ";";
		addToDebugLog("getPlayerCurrentGridCoordinates(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result[0][0];
		
	}

	function changeJourney($player_id, $character_id, $journey_id) {
		
		// Changes journey for supplied character
	
		addToDebugLog("changeJourney(): Function Entry - supplied parameters: Journey ID: " . $journey_id . ", Character ID: " . $character_id);
		
		// Determine the last grid visited / created
		$grid_id = getLastLocation($journey_id, $character_id);
					
		// Update player to that grid / journey
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . ", current_journey_id = " . $journey_id . " WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("changeJourney(): Character record updated");
		} else {
			addToDebugLog("changeJourney(): Character record not updated");
		}

		// Navigate to page
		echo "<script>window.location.href = 'journey.php?player_id=" . $player_id . "&character_id=" . $character_id . "'</script>";		
		
	}
	
	function getLastLocation($journey_id, $character_id) {
		
		// Determines last location for certain journey
	
		addToDebugLog("getLastLocation(): Function Entry - supplied parameters: Journey ID: " . $journey_id . ", Character ID: " . $character_id);

		$sql = "SELECT grid_id FROM hackcess.journal WHERE journey_id = " . $journey_id . " AND character_id = " . $character_id . " ORDER BY journal_id DESC LIMIT 1;";
		addToDebugLog("getLastLocation(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result[0][0];
		
	}
	
	function newJourney($player_id, $character_id, $parent_character_id) {

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

		// Navigate to page
		if ($parent_character_id > 0) {
			// Do nothing
		} else {
			echo "<script>window.location.href = 'journey.php?player_id=" . $player_id . "&character_id=" . $character_id . "'</script>";
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
		
		while ($location == "") {
			$locations = array("Forest", "Mountains", "Swamps", "Castle", "Dungeons", "Catacombs", "Wasteland", "Desert", "Frozen wastes", "Caverns", "Hills", "Fortress", "Wilderness", "Jungle");
			$length = count($locations);
			$location = $locations[rand(0, $length)];
		}
			
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
		echo "<tr><td colspan=4 align=center><b>" . $name . ", Level " . $level . " " . $role . "</tr>";
		echo "<tr bgcolor=#ddd><td align=center>Stats<td align=center>Value<td>Slot<td>Item</tr>";
		
		// Get Character Details
		$sql = "SELECT * FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		addToDebugLog("displayPlayerInformation(): Constructed query: " . $sql);
		$result = search($sql);

		echo "<tr><td>HP<td align=center>" . $result[0][13] . "<td>Head<td>" . getItemNameById($result[0][8]) . "</tr>";
		echo "<tr><td>ATK<td align=center>" . $result[0][3] . "<td>Chest<td>" . getItemNameById($result[0][9]) . "</tr>";
		echo "<tr><td>AC<td align=center>" . $result[0][4] . "<td>Legs<td>" . getItemNameById($result[0][10]) . "</tr>";
		echo "<tr><td>Gold<td align=center>" . $result[0][5] . "<td>Shield<td>" . getItemNameById($result[0][11]) . "</tr>";
		echo "<tr><td>STR<td align=center>" . $result[0][7] . "<td>Weapon<td>" . getItemNameById($result[0][12]) . "</tr>";
		//echo "<tr><td>XP<td align=center>" . $result[0][6] . "<td colspan=2></tr>";
		
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

		// Determine if player needs to recover HP
		$hp_alteration = "";
		$max_hp = getCharacterDetailsInfo($character_id, "hp");
		$current_hp = getCharacterDetailsInfo($character_id, "current_hp");
		if ($current_hp < $max_hp) {
			addToDebugLog("updatePlayerOnMove(): Healing by 1HP");
			$hp_alteration = ", current_hp = current_hp + 1 ";
		}
		
		// Update player XP (/ HP)
		$dml = "UPDATE hackcess.character_details SET xp = xp + 10 " . $hp_alteration . "WHERE character_id = " . $character_id . ";";
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
			$dml = "UPDATE hackcess.character_details SET hp = hp + 1, current_hp = current_hp + 1, armor_class = armor_class + 1, attack = attack + 1, strength = strength + 2 WHERE character_id = " . $character_id . ";";
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
		newJourney($player_id, $character_id, $parent_character_id);
		
		// Navigate to page
		if ($parent_character_id == 0) {
			echo "<script>window.location.href = 'character.php?player_id=" . $player_id . "'</script>";
		}
		
		return $character_id;
		
		//outputDebugLog();
		
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
			$name = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $name); // Removes CRLF
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
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Sword', 0, 1, 1, 'weapon', " . $character_id . ");";
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
		$dml = "INSERT INTO hackcess.character_details (character_id, hp, attack, armor_class, gold, xp, strength, head_slot, chest_slot, legs_slot, shield_slot, weapon_slot, current_hp) VALUES (" . $character_id . ", 20, 5, 1, 0, 0, 20, " . $head . ", " . $chest . ", " . $legs . ", " . $shield . ", " . $sword . ", 10);";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(): Character details stored");
		} else {
			addToDebugLog("move(): ERROR: Character details not stored");
		}	
		
	}
	
	function getItemNameById($item_id) {
		
		// Returns the item name
	
		addToDebugLog("getItemNameById(): Function Entry - supplied parameters: Item ID: " . $item_id);		

		$sql = "SELECT name, ac_boost, attack_boost FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
		addToDebugLog("getItemNameById(): Constructed query: " . $sql);
		$result = search($sql);
		$item_name = $result[0][0];
		$ac_boost = $result[0][1];
		$attack_boost = $result[0][2];
		
		// Add relevant modifier
		if ($ac_boost == 0) {
			$name = $item_name . " (+" . $attack_boost . ")";
		} else {
			$name = $item_name . " (+" . $ac_boost . ")";
		}
		
		return $name;
		
	}
	
	function createEnemy($player_id, $journey_id, $character_id, $grid_id) {
		
		// Creates a new enemy
	
		addToDebugLog("createEnemy(): Function Entry - supplied parameters: Player ID: " . $player_id . ", Journey ID: " . $journey_id . ", Character ID: " . $charcter_id . ", Grid ID: " . $grid_id);		
		
		// Create Character Name
		$name = generateEnemyName();
		
		// Get character base stats
		$character_hp = getCharacterDetailsInfo($character_id, "hp");
		$character_ac = getCharacterDetailsInfo($character_id, "armor_class");
		$character_atk = getCharacterDetailsInfo($character_id, "attack");
		addToDebugLog("createEnemy(): Character Stats: HP: " . $character_hp . ", Character AC: " . $character_ac . ", Character ATK: " . $character_atk);	
		
		// Get character boosts from armour / weapons
		$boost_list = getCharacterBoosts($character_id);
		$boosts = explode(",", $boost_list);
		$total_ac_boost = $boosts[0];
		$total_attack_boost = $boosts[1];
		
		// Generate enemy stats based on character's stats
		$enemy_hp = ($character_hp/2) + 5;
		if ($enemy_hp <= 0) { $enemy_hp = 1;}
		$enemy_ac = $character_ac + $total_ac_boost - rand(5, 10);
		if ($enemy_ac <= 0) { $enemy_ac = 1;}
		$enemy_atk = $character_atk - rand(0, 5);
		if ($enemy_atk <= 0) { $enemy_atk = 1;}
		addToDebugLog("createEnemy(): Enemy Stats: HP: " . $enemy_hp . ", Character AC: " . $enemy_ac . ", Character ATK: " . $enemy_atk);	
		
		// Create enemy record
		$dml = "INSERT INTO hackcess.enemy (enemy_name, player_id, character_id, grid_id, atk, ac, hp, status) VALUES ('" . $name . "', " . $player_id . ", " . $character_id . ", " . $grid_id . ", " . $enemy_atk . ", " . $enemy_ac . ", " . $enemy_hp . ", 'Alive');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createEnemy(): New enemy generated");
		} else {
			addToDebugLog("createEnemy(): ERROR: New enemy not generated");
		}
		
		// Get new enemy id
		$sql = "SELECT enemy_id FROM hackcess.enemy WHERE grid_id = " . $grid_id . " LIMIT 1;";
		addToDebugLog("createEnemy(): Constructed query: " . $sql);
		$result = search($sql);
		$enemy_id = $result[0][0];
		
		return $enemy_id;
	
	}
	
	function getEnemyInfo($enemy_id) {

		// Returns information about the supplied enemy
	
		addToDebugLog("getEnemyInfo(): Function Entry - supplied parameters: Enemy ID: " . $enemy_id);	
		
		$sql = "SELECT enemy_name, atk, ac, hp FROM hackcess.enemy WHERE enemy_id = " . $enemy_id . ";";
		addToDebugLog("getEnemyInfo(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result;		
		
	}
	
	function generateEnemyName() {
		
		// Creates and returns a enemy name
	
		addToDebugLog("generateEnemyName(): Function Entry - no parameters");
		
		// Choose the Creature
		$filepath = "lists/creatures.txt";
		$creatures = file($filepath); // reads contents of select file into the array
		$creatures_length = count($creatures);
		$creature = "";
		while ($creature == "") {
			srand(make_seed());
			$creature = $creatures[rand(0, $creatures_length)];
			$creature = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $creature); // Removes CRLF
		}
		addToDebugLog("generateEnemyName(): Selected creature: " . $creature);
		
		// Generate the name
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
		addToDebugLog("generateEnemyName(): Name: " . $name);
		
		$final_name = ucfirst($name) . " the " . ucfirst($creature);
		addToDebugLog("generateEnemyName(): Final name: " . $final_name);
		
		return $final_name;
		
	}
	
	function displayBattleStats($character_basic_info, $character_detailed_info, $enemy_info) {
		
		// Displays table of character and enemy_details
		
		addToDebugLog("displayBattleStats(): Function Entry - 3 parameters (arrays)");
		
		// Heading: $character_basic_info; 0: id, 1: player id, 2: name, 3: role, 4: level
		// Column 1: $character_detailed_info; 2: hp, 3: attack, 4: ac, 5: gold, 6: xp, 7: strength, 8: head, 9: chest, 10: legs, 11: shield, 12: weapon, 13: current_hp.
		// Column 2: Character Equipment
		// Column 3: $enemy_info; 0: enemy_name, 1: atk, 2: ac, 3: hp 
		
		// Get character AC / ATK boosts from equipment
		$boost_list = getCharacterBoosts($character_basic_info[0][0]);
		$boosts = explode(",", $boost_list);
		$total_ac_boost = $boosts[0];
		$total_attack_boost = $boosts[1];
		addToDebugLog("displayBattleStats(): Boosts: AC: " . $total_ac_boost . ", ATK: " . $total_attack_boost);
		
		// Get equipment names
		$head = getItemNameById($character_detailed_info[0][8]);
		$chest = getItemNameById($character_detailed_info[0][9]);
		$legs = getItemNameById($character_detailed_info[0][10]);
		$shield = getItemNameById($character_detailed_info[0][11]);
		$weapon = getItemNameById($character_detailed_info[0][12]);
		
		echo "<table cellpadding=3 cellspacing=0 border=1 style='margin-left: auto; margin-right: auto;'>";
		echo "\n<tr>\n\t<td colspan=4 align=center width=600px><h2>" . trim($character_basic_info[0][2]) . "</h2>Level " . $character_basic_info[0][4] . " " . trim($character_basic_info[0][3]) . "\n\t<td valign=center align=center><h2>VS</h2>\n\t<td colspan=2 align=center valign=top width=300px><h2>" . $enemy_info[0][0] . "</h2>\n</tr>"; // Display character / enemy names
		echo "\n<tr>\n\t<td align=right width=50px>Head\n\t<td width=150px>" . $head . "\n\t<td align=center width=50px>" . $character_detailed_info[0][2] . "\n\t<td width=250px align=right>" . barGraph($character_detailed_info[0][13], 'char') . "\n\t<td align=center>HP\n\t<td>" . barGraph($enemy_info[0][3], 'enemy') . "<td align=center>" . $enemy_info[0][3] . "\n</tr>";
		echo "\n<tr>\n\t<td align=right>Chest\n\t<td>" . $chest . "\n\t<td align=center>" . $character_detailed_info[0][4] . " (+" . $total_ac_boost . ")\n\t<td align=right>" . barGraph($character_detailed_info[0][4] + $total_ac_boost, 'char') . "\n\t<td align=center>AC\n\t<td>" . barGraph($enemy_info[0][2], 'enemy') . "\n\t<td align=center>" . $enemy_info[0][2] . "\n</tr>";
		echo "\n<tr>\n\t<td align=right>Legs\n\t<td>" . $legs . "\n\t<td align=center>" . $character_detailed_info[0][3] . " (+" . $total_attack_boost . ")\n\t<td align=right>" . barGraph($character_detailed_info[0][3] + $total_attack_boost, 'char') . "\n\t<td align=center>ATK\n\t<td>" . barGraph($enemy_info[0][1], 'enemy') . "\n\t<td align=center>" . $enemy_info[0][1] . "\n</tr>";
		echo "\n<tr>\n\t<td align=right>Shield\n\t<td>" . $shield . "\n\t<td align=right colspan=2>" . $character_detailed_info[0][7] . "\n\t<td align=center>STR\n\t<td colspan=2>\n</tr>";
		echo "\n<tr>\n\t<td align=right>Weapon\n\t<td>" . $weapon . "\n\t<td align=right colspan=2>" . $character_detailed_info[0][6] . "\n\t<td align=center>XP\n\t<td colspan=2>\n</tr>";
		echo "</table>";
		
	}
	
	function barGraph($value, $who) {
		
		// Produces bar graph for the versus table
		
		$width = $value * 5;
		if ($value < 50) {
			$width = $value;
		}
		if ($value < 50) {
			$width = $value * 5;
		}
		if ($value < 20) {
			$width = $value * 10;
		}
		
		if ($who == "char") {
			return "<table><tr><td>" . $value . "<td bgcolor='#0f0' height=20px width='" . $width . "px'></tr></table>";
		} else {
			return "<table><tr><td bgcolor='#f00' height=20px width='" . $width . "px'><td>" . $value . "</tr></table>";
		}
	
	}
	
	function getCharacterBoosts($character_id) {
		
		// Get character boosts from armour / weapons
		
		addToDebugLog("getCharacterBoosts(): Function Entry - supplied parameters: Character ID: " . $character_id);
		
		$sql = "SELECT ac_boost, attack_boost, slot, equipment_id FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		addToDebugLog("getCharacterBoosts(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		$total_attack_boost = 0;
		$total_ac_boost = 0;
		for ($e = 0; $e < $rows; $e++) {
			
			// Check if item actually equipped
			$is_equipped = isEquipped($result[$e][2], $result[$e][3], $character_id);
			
			if ($is_equipped == 1) {
				if ($result[$e][0] > 0) { // AC boost
					$total_ac_boost = $total_ac_boost + $result[$e][0];
				}
				if ($result[$e][1] > 0) { // Attack boost
					$total_attack_boost = $total_attack_boost + $result[$e][1];
				}			
			}
		}
		addToDebugLog("getCharacterBoosts(): AC Boost: " . $total_ac_boost . ", Attack Boost: " . $total_attack_boost);
		
		return $total_ac_boost . "," . $total_attack_boost;
		
	}
	
	function flee($character_id, $journey_id, $grid_id, $enemy_id) {
		
		// This function handles where the character runs away from a fight
		
		addToDebugLog("getCharacterBoosts(): Function Entry - supplied parameters: Character ID: " . $character_id);	

		// Reduce character HP
		$hp = rand(1, 5);
		$dml = "UPDATE hackcess.character_details SET current_hp = current_hp - " . $hp . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("flee(): Character record updated");
		} else {
			addToDebugLog("flee(): Character record not updated");
		}		
		
		// Get enemy name
		$enemy_info = getEnemyInfo($enemy_id);
		$name = $enemy_info[0][0];
		
		// Record entry in Journal
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", 'Ran from a fight with " . $name . ", lost " . $hp . "HP.');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("flee(): New journal entry added");
		} else {
			addToDebugLog("flee(): ERROR: New journal entry not added");
		}			

	}
	
	function manageEquipment($player_id, $character_id, $journey_id) {
		
		// This function lists the equipment held by each player
		
		addToDebugLog("manageEquipment(): Function Entry - supplied parameters: Player ID: " . $player_id . ", Journey ID: " . $journey_id . ", Character ID: " . $charcter_id);
		
		echo "<table cellpadding=3 cellspacing=0 border=1>";
		echo "<tr bgcolor=#ddd><td>Item<td>Slot<td align=center>Weight<td align=center>Actions</tr>";

		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . " ORDER BY slot ASC, ac_boost, attack_boost DESC;";
		addToDebugLog("manageEquipment(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);

		// 0: ID
		// 1: Name
		// 2: AC Boost
		// 3: ATK Boost
		// 4: Weight
		// 5: Slot
		
		$weight_total = 0;
		$current_slot = "";
		for ($e = 0; $e < $rows; $e++) {		
		
			if ($result[$e][5] != $current_slot) {
				echo "<tr><td colspan=4 bgcolor=#eee align=center>" . ucfirst($result[$e][5]) . "</tr>";
				$current_slot = $result[$e][5];
			}
		
			$bonus = $result[$e][2] + $result[$e][3];
			echo "<tr><td>+" . $bonus . " " . $result[$e][1]; // Bonus + Item
			echo "<td>" . ucfirst($result[$e][5]); // Slot
			echo "<td align=center>" . $result[$e][4]; // Weight

			// Determine if the item of equipment is equipped or not
			$is_equipped = isEquipped($result[$e][5], $result[$e][0], $character_id);
			$weight_total = $weight_total + $result[$e][4];
			if ($is_equipped == 1) {
				echo "<td align=center bgcolor=#6f3>Equipped";
							
			} else {
				echo "<td align=center>";
				echo "<a href='equipment.php?slot=" . $result[$e][5] . "&item_id=" . $result[$e][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&action=equip'>Equip</a>"; // $slot, $item_id, $character_id
				echo " | <a href='equipment.php?slot=" . $result[$e][5] . "&item_id=" . $result[$e][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&action=drop'>Drop</a>"; // $slot, $item_id, $character_id
			}
			
			echo "</tr>";
			
		}
		
		echo "<tr><td colspan=2 align=right>Total Weight<td align=center>" . $weight_total . "<td></tr>";
		
		// Get character strength
		$character_strength = getCharacterDetailsInfo($character_id, 'strength');
		echo "<tr><td colspan=2 align=right>Character Strength<td align=center>" . $character_strength . "<td></tr>";
		
		echo "</table>";
		
		if ($weight_total >= $character_strength) {
			return "overweight";
		} else {
			return "ok";
		}
		
	}
	
	function isEquipped($slot, $item_id, $character_id) {

		// This function lists the equipment held by each player
		
		addToDebugLog("isEquipped(): Function Entry - supplied parameters: Slot Name: " . $slot . ", Item ID: " . $item_id . ", Character ID: " . $character_id);
		
		$sql = "SELECT " . $slot . "_slot FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		addToDebugLog("isEquipped(): Constructed query: " . $sql);
		$result = search($sql);

		if ($result[0][0] == $item_id) {
			return 1;
		} else {
			return 0;
		}
		
	}
	
	function equip($slot, $item_id, $character_id) {
		
		// This function equips the item selected
		
		addToDebugLog("equip(): Function Entry - supplied parameters: Slot Name: " . $slot . ", Item ID: " . $item_id . ", Character ID: " . $character_id);	

		// Equip item
		$dml = "UPDATE hackcess.character_details SET " . $slot . "_slot = " . $item_id . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("flee(): Item equipped");
		} else {
			addToDebugLog("flee(): Item not equipped");
		}
		
	}
	
	function createRandomItem($character_id, $character_ac_boost, $character_atk_boost) {
		
		// This function equips the item selected
		
		addToDebugLog("createRandomItem(): Function Entry - supplied parameters: Character ID: " . $character_id . ", Character AC Boost: " . $character_ac_boost . ", Character Attack Boost: " . $character_atk_boost);

		$item_choice = rand(0, 4);
		
		switch ($item_choice) {
			case 0: // Head
				$slot = "head";
				$name = "Helm";
				$ac_start = round($character_ac_boost/4, 0);
				$ac_boost = rand($ac_start, $ac_start+2);
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 1: // Chest
				$slot = "chest";
				$name = "Chestplate";
				$ac_start = round($character_ac_boost/4, 0);
				$ac_boost = rand($ac_start, $ac_start+2);
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 2: // Legs
				$slot = "legs";
				$name = "Trousers";
				$ac_start = round($character_ac_boost/4, 0);
				$ac_boost = rand($ac_start, $ac_start+2);
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 3: // Shield
				$slot = "shield";
				$name = "Shield";
				$ac_start = round($character_ac_boost/4, 0);
				$ac_boost = rand($ac_start, $ac_start+2);
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 4: // Weapon
				$slot = "weapon";
				$name = "Sword";
				$attack_boost = rand(intval($character_atk_boost), intval($character_atk_boost)+2);
				$weight = round($attack_boost/2);
				$ac_boost = 0;
				$details = "+" . $attack_boost . " " . $name;
				break;
		}
		
		// Add weapon to character
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('" . $name . "', " . $ac_boost . ", " . $attack_boost . ", " . $weight . ", '" . $slot . "', " . $character_id  .");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createRandomItem(): New item added");
		} else {
			addToDebugLog("createRandomItem(): ERROR: New item not added");
		}
		
		return $details;
		
	}

	function drop($equipment_id) {
		
		// This function equips the item selected
		
		addToDebugLog("drop(): Function Entry - supplied parameters: Equipment ID: " . $equipment_id);	
		
		$dml = "DELETE FROM hackcess.character_equipment WHERE equipment_id = " . $equipment_id . ";";
		$result = delete($dml);
		if ($result == TRUE) {
			addToDebugLog("drop(): Item deleted");
		} else {
			addToDebugLog("drop(): ERROR: Item not deleted");
		}
		
	}
	
	function createPlayer($name) {
		
		// This function probably dosen't work
		
		("drop(): Function Entry - supplied parameters: Name: " . $name);
		
		$dml = "INSERT INTO hackcess.user (name) VALUES ('" . $name . "')";
		$result = delete($dml);
		if ($result == TRUE) {
			addToDebugLog("drop(): Item deleted");
		} else {
			addToDebugLog("drop(): ERROR: Item not deleted");
		}
		
	}
	
	function equipmentWeight($character_id) {
		
		// This function equips the item selected
		
		addToDebugLog("equipmentWeight(): Function Entry - supplied parameters: Character ID: " . $character_id);		

		$sql = "SELECT weight FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		addToDebugLog("equipmentWeight(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		$weight = 0;
		
		for ($r = 0; $r < $rows; $r++) {
			$weight = $weight + $result[$r][0];
		}

		return $weight;
		
	}

	function getBestItem($character_id) {
		
		// This function returns the id of the best item for the supplied character_id
		
		addToDebugLog("getBestItem(): Function Entry - supplied parameters: Character ID: " . $character_id);		
		
		$sql = "SELECT equipment_id FROM hackcess.character_equipment WHERE character_id = " . $character_id . " ORDER BY attack_boost, ac_boost DESC LIMIT 1;";
		addToDebugLog("getBestItem(): Constructed query: " . $sql);
		$result = search($sql);
		$best_item_id = $result[0][0];	
		
		return $best_item_id;
		
	}
	
?>