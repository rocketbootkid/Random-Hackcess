<?php

	function characterFightCount($character_id) {
	
		// This function displays the combat history for the selected character
	
		addToDebugLog("characterFightCount(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
	
		$sql = "SELECT fight_id FROM hackcess.fight WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
	
		return $rows;
			
	}

	function playerSelect() {
		
		// Displays details for all players
	
		addToDebugLog("playerSelect(), Function Entry, INFO");	
		
		$sql = "SELECT * FROM hackcess.user;";
		$result = search($sql);
		$rows = count($result); 
		
		echo "<h1 align=center>Select Player</h1>";

		echo "<table class='characters' cellpadding=3 cellspacing=0 border=1 align=center>";
		echo "<tr bgcolor=#ddd><td class='characters'>Player ID<td class='characters'>Name</tr>";
		
		for ($u = 0; $u < $rows; $u++) {
			echo "<tr><td align=center>" . $result[$u][0] . "<td><a href='character.php?player_id=" . $result[$u][0] . "'>" . ucfirst($result[$u][1]) . "</a></tr>";
		}		
		echo "</table>";
	}
	
	function characterSelect($player_id) {

		// Lists characters for the selected Player
	
		addToDebugLog("characterSelect(), Function Entry - supplied parameters: Player ID: " . $player_id . ", INFO");			
	
		// Get Player Name
		$name = getPlayerDetails($player_id, "username");
		echo "<h1 align=center>" . ucfirst($name) . "'s Characters</h1>";
		
		// Get List of Live Characters for this Player
		characterList($player_id, "alive");
	
		// Get list of dead / retired character for this player
		characterList($player_id, "dead");		
		
	}

	function getPlayerDetails($player_id, $attribute) {
		
		// Returns selected player attribute
	
		addToDebugLog("getPlayerDetails(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Attribute: " . $attribute . ", INFO");	
		
		$sql = "SELECT " . $attribute . " FROM hackcess.user WHERE user_id = " . $player_id . ";";
		$result = search($sql);

		return $result[0][0];
		
	}
	
	function characterList($player_id, $status) {
		
		// Lists characters with the right status for the selected Player
	
		addToDebugLog("characterList(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Status: " . $status . ", INFO");			
		
		// Display Characters
		if ($status == "alive") {
			$sqlc = "SELECT * FROM hackcess.character WHERE player_id = " . $player_id . " AND status = 'Alive' ORDER BY character_level DESC;";	
		} elseif ($status == "dead") {
			$sqlc = "SELECT * FROM hackcess.character WHERE player_id = " . $player_id . " AND status != 'Alive' ORDER BY character_level DESC LIMIT 20;";
		}
		
		addToDebugLog("characterList(), Constructed query: " . $sqlc . ", INFO");
		$resultc = search($sqlc);
		$rowsc = count($resultc);
		
		if ($rowsc != 0) {
			echo "<table class='characters' cellpadding=3 cellspacing=0 border=1 align=center>";
			echo "<tr bgcolor=#ddd><td class='characters' width=50px align=center>Gen.";
			echo "<td class='characters' width=200px>Name";
			echo "<td class='characters' width=100px>Class";
			echo "<td class='characters' align=center>Level";
			echo "<td class='characters' width=200px>Parent";
			echo "<td class='characters' align=center>Fights</tr>";
			for ($c = 0; $c < $rowsc; $c++) {
				echo "<tr><td align=center>" . $resultc[$c][8]; // Generation
				if ($status == 'alive') { // Name
					echo "<td class='characters'><a href='journey.php?player_id=" . $player_id . "&character_id=" . $resultc[$c][0] . "'>" . $resultc[$c][2] . "</a>";
				} else {
					echo "<td class='characters'>" . $resultc[$c][2];
				}
				echo "<td class='characters'>" . $resultc[$c][3];
				echo "<td class='characters' align=center>" . $resultc[$c][4];
				
				// Parent Name
				if ($resultc[$c][9] > 0) {
					$parent_name = getCharacterDetails($resultc[$c][9], "character_name");
					echo "<td class='characters'><a href='history.php?player_id=" . $player_id . "&character_id=" . $resultc[$c][9] . "'>" . $parent_name . "</a>";
				} else {
					echo "<td class='characters' align=center>-";
				}		
				// History
				$fights = characterFightCount($resultc[$c][0]);
				echo "<td align=center><a href='history.php?player_id=" . $player_id . "&character_id=" . $resultc[$c][0] . "'>" . $fights . "</a>";
				echo "</tr>";
			}
			if ($status == 'alive') {
				echo "<tr><td colspan=6 align=center><a href='character.php?create=character&player_id=" . $player_id . "'>Create new character</a></tr>";
			}
			echo "</table><p>";
		} else {
			if ($status == "alive") {
				echo "<table class='characters' cellpadding=3 cellspacing=0 border=1 width=500px>";
				echo "<tr><td>There are no live characters</tr>";
				echo "<tr><td align=center><a href='character.php?create=character&player_id=" . $player_id . "'>Create new character</a></tr>";
				echo "</table><p>";
			} else {
				echo "<div style='text-align: center;'>There are no dead / retired characters.</div>";
			}
		}
		
	}
	
	function getCharacterDetails($character_id, $attribute) {
		
		// Returns selected player attribute
	
		addToDebugLog("getCharacterDetails(), Function Entry - supplied parameters: Character ID: " . $character_id . "; Attribute: " . $attribute . ", INFO");	
		
		$sql = "SELECT " . $attribute . " FROM hackcess.character WHERE character_id = " . $character_id . ";";
		$result = search($sql);

		return $result[0][0];
		
	}

	function getAllCharacterMainInfo($character_id) {
		
		// Returns all main information for the selected player
	
		addToDebugLog("getAllCharacterDetails(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");	
		
		$sql = "SELECT * FROM hackcess.character WHERE character_id = " . $character_id . ";";
		$result = search($sql);

		return $result;
		
	}
	
	function getAllCharacterDetailedInfo($character_id) {
		
		// Returns all detailed information for the selected player
	
		addToDebugLog("getAllCharacterDetailedDetails(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");	
		
		$sql = "SELECT * FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		$result = search($sql);

		return $result;
		
	}
	
	function getCharacterDetailsInfo($character_id, $attribute) {
		
		// Returns selected player attribute
	
		addToDebugLog("getCharacterDetailsInfo(), Function Entry - supplied parameters: Character ID: " . $character_id . "; Attribute: " . $attribute . ", INFO");	
		
		$sql = "SELECT " . $attribute . " FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		$result = search($sql);

		addToDebugLog("getCharacterDetailsInfo(), Attribute '" . $attribute . "' value: " . $result[0][0] . ", INFO");
		
		return $result[0][0];
		
	}
	
	function journeySelect($player_id, $character_id) {
		
		// Returns current journey id for supplied player
	
		addToDebugLog("journeySelect(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Character ID: " . $character_id . ", INFO");			

		// List of Journeys
		$sqlj = "SELECT * FROM hackcess.journey WHERE player_id = " . $player_id . " AND character_id = " . $character_id . ";";
		$resultj = search($sqlj);
		$rowsj = count($resultj);
		
		echo "<table class='characters' cellpadding=3 cellspacing=0 border=1 align=center>";
		echo "<tr bgcolor=#ddd><td>Journey<td>Grids<td>Action</tr>";
		for ($j = 0; $j < $rowsj; $j++) {

			echo "<tr><td>" . $resultj[$j][0] . ": " . $resultj[$j][3];
		
			// Determine current journey
			$journey_id = playerCurrentJourney($player_id, $character_id);
			addToDebugLog("journeySelect(), Current Journey ID: " . $journey_id . ", INFO");
			
			// Determine how many grids exist for the journey
			$sqlg = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $resultj[$j][0] . ";";
			addToDebugLog("displayPlayers(), Constructed query: " . $sqlg . ", INFO");
			$resultg = search($sqlg);
			$rowsg = count($resultg);				
			echo "<td align=center>" . $rowsg . "/2500";
			
			addToDebugLog("journeySelect(), Journey ID: " . $resultj[$j][0] . ", INFO");
			
			// Either set current journey, or embark on it
			if ($journey_id == $resultj[$j][0]) {  // current journey
				echo "<td><a href='adventure.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "'>Embark on Journey</a></tr>";	
			} else { // Not current journey					
				echo "<td><a href='journey.php?player_id=" . $player_id . "&character_id=" . $resultj[$j][2] . "&journey_id=" . $resultj[$j][0] . "'>Switch to this journey</a></tr>";		
			}
		}
		echo "<td colspan=3 class='characters' align=center><a href='journey.php?create=journey&player_id=" . $player_id . "&character_id=" . $character_id . "'>Create new journey</a></a>";
		echo "</table><p>";		
		
	}
	
	function displayPlayers() {
		
		// Displays details for all players
	
		addToDebugLog("displayPlayers(), Function Entry, INFO");	
		
		$sql = "SELECT * FROM hackcess.user;";
		$result = search($sql);
		$rows = count($result); 

		for ($u = 0; $u < $rows; $u++) {		
			echo "<h2>" . ucfirst($result[$u][1]) . "</h2>";
			
			// Display Characters
			$sqlc = "SELECT * FROM hackcess.character WHERE player_id = " . $result[$u][0] . ";";
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
	
		addToDebugLog("playerCurrentJourney(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Character ID: " . $character_id . ", INFO");	
		
		$sql = "SELECT current_journey_id FROM hackcess.character WHERE player_id = " . $player_id . " AND character_id = " . $character_id . ";";
		$result = search($sql);
		
		return $result[0][0];
		
	}

	function changeJourney($player_id, $character_id, $journey_id) {
		
		// Changes journey for supplied character
	
		addToDebugLog("changeJourney(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $character_id . ", INFO");
		
		// Determine the last grid visited / created
		$grid_id = getLastLocation($journey_id, $character_id);
					
		// Update player to that grid / journey
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . ", current_journey_id = " . $journey_id . " WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("changeJourney(), Character record updated, INFO");
		} else {
			addToDebugLog("changeJourney(), Character record not updated, ERROR");
		}

		outputDebugLog();
		
		// Navigate to page
		echo "<script>window.location.href = 'journey.php?player_id=" . $player_id . "&character_id=" . $character_id . "'</script>";		
		
	}
	
	function getLastLocation($journey_id, $character_id) {
		
		// Determines last location for certain journey
	
		addToDebugLog("getLastLocation(), Function Entry - supplied parameters: Journey ID: " . $journey_id . "; Character ID: " . $character_id . ", INFO");

		$sql = "SELECT grid_id FROM hackcess.journal WHERE journey_id = " . $journey_id . " AND character_id = " . $character_id . " ORDER BY journal_id DESC LIMIT 1;";
		$result = search($sql);
		
		return $result[0][0];
		
	}
	
	function newJourney($player_id, $character_id, $parent_character_id) {

		// Creates a new journey for the supplied player / character
	
		addToDebugLog("newJourney(), Function Entry - supplied parameters: Journey ID: " . $journey_id . "; Character ID: " . $character_id . "; Parent Character ID: " . $parent_character_id . ", INFO");
	
		// Create Journey Name
		$journey_name = createJourneyName();
		
		// Create Journey (player, character)
		$dml = "INSERT INTO hackcess.journey (player_id, character_id, journey_name) VALUES (" . $player_id . ", " . $character_id . ", '" . $journey_name . "');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("newJourney(), New journey added, INFO");
		} else {
			addToDebugLog("newJourney(), ERROR: New journey not added, ERROR");
		}
		
		// Get new journey id
		$sql = "SELECT journey_id FROM hackcess.journey WHERE player_id = " . $player_id . " AND character_id = " . $character_id . " ORDER BY journey_id DESC LIMIT 1;";
		$result = search($sql);
		$journey_id = $result[0][0];
		
		// Create grid (journey id)
		$dml = "INSERT INTO hackcess.grid (grid_x, grid_y, directions, journey_id) VALUES (25, 1, 1191, " . $journey_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("newJourney(), New grid added, INFO");
		} else {
			addToDebugLog("newJourney(), ERROR: New grid not added, ERROR");
		}	
	
		// Get new grid id
		$sql = "SELECT grid_id FROM hackcess.grid WHERE journey_id = " . $journey_id . " LIMIT 1;";
		$result = search($sql);
		$grid_id = $result[0][0];
		
		// Update Character (grid id, journey id)
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . ", current_journey_id = " . $journey_id . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result== TRUE) {
			addToDebugLog("newJourney(), Character record updated, INFO");
		} else {
			addToDebugLog("newJourney(), Character record not updated, ERROR");
		}
		
		// Create new journal entry
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", 'Dropped off at 25,1 in " . $journey_name . "');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("newJourney(), New journal entry added, INFO");
		} else {
			addToDebugLog("newJourney(), New journal entry not added, ERROR");
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
	
		addToDebugLog("createJourneyName(), Function Entry - no parameters");
		
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
		addToDebugLog("- createJourneyName(), Generated journey name: " . $final_name);
		
		return $final_name;		
	
	}
	
	function displayPlayerInformation($character_id) {
		
		// Displays the latest journal entries for this journey
	
		addToDebugLog("displayPlayerInformation(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		// Get Character Basic details
		$sql = "SELECT character_name, character_role, character_level FROM hackcess.character WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$name = $result[0][0];
		$role = $result[0][1];
		$level = $result[0][2];
		
		echo "<table cellpadding=2 cellspacing=0 border=0 width=100%>";
		echo "<tr><td colspan=4 align=center><b>" . $name . ", Level " . $level . " " . $role . "</tr>";
		echo "<tr bgcolor=#ddd><td align=center><td align=center>Value<td><td>Item</tr>";
		
		// Get Character AC / ATK Boosts from Equipment
		$equipment_boosts = getCharacterBoosts($character_id);
		$eqp_ac_boost = $equipment_boosts[0];
		$eqp_atk_boost = $equipment_boosts[1];
		
		// Get effect boosts
		$effect_boosts = getEffectBoosts($character_id);
		
		$total_atk_boost = $eqp_atk_boost + $effect_boosts["atk"];
		$total_ac_boost = $eqp_ac_boost + $effect_boosts["ac"];
		
		// Get Character Details
		$sql = "SELECT * FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		$result = search($sql);

		echo "<tr><td align=right>HP<td align=center>" . $result[0][13];
		if ($effect_boosts["hp"] != 0) { echo " (+" . $effect_boosts["hp"] . ")"; }
		echo "<td align=right>Head | <td>" . getItemNameById($result[0][8]) . "</tr>";
		echo "<tr><td align=right>ATK<td align=center>" . $result[0][3] . " (+" . $total_atk_boost . ")<td align=right>Chest | <td>" . getItemNameById($result[0][9]) . "</tr>";
		echo "<tr><td align=right>AC<td align=center>" . $result[0][4] . " (+" . $total_ac_boost . ")<td align=right>Legs | <td>" . getItemNameById($result[0][10]) . "</tr>";
		echo "<tr><td align=right>Gold<td align=center>" . $result[0][5] . "<td align=right>Shield | <td>" . getItemNameById($result[0][11]) . "</tr>";
		echo "<tr><td align=right>STR<td align=center>" . $result[0][7];
		if ($effect_boosts["str"] != 0) { echo " (+" . $effect_boosts["str"] . ")"; }
		echo "<td align=right>Weapon | <td>" . getItemNameById($result[0][12]) . "</tr>";
		echo "</table>";
		
	}		

	function updatePlayerOnMove($character_id, $grid_id, $journey_id) {

		// Manage player changes on move
	
		addToDebugLog("updatePlayerOnMove(), Function Entry - supplied parameters: Character ID: " . $character_id . "; Grid ID: " . $grid_id . "; Journey ID: " . $journey_id . ", INFO");	
	
		// Move player location to new grid square
		$dml = "UPDATE hackcess.character SET character_grid_id = " . $grid_id . " WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("updatePlayerOnMove(), Character record updated, INFO");
		} else {
			addToDebugLog("updatePlayerOnMove(), Character record not updated, ERROR");
		}		

		// Determine if player needs to recover HP
		$hp_alteration = "";
		$max_hp = getCharacterDetailsInfo($character_id, "hp");
		$current_hp = getCharacterDetailsInfo($character_id, "current_hp");
		if ($current_hp < $max_hp) {
			addToDebugLog("updatePlayerOnMove(), Healing by 1HP");
			$hp_alteration = ", current_hp = current_hp + 1 ";
		}
		
		// Update player XP (/ HP)
		$dml = "UPDATE hackcess.character_details SET xp = xp + 10 " . $hp_alteration . "WHERE character_id = " . $character_id . ";";
		$resultdml = insert($dml);
		if ($resultdml == TRUE) {
			addToDebugLog("updatePlayerOnMove(), Character details updated, INFO");
		} else {
			addToDebugLog("updatePlayerOnMove(), Character details not updated, ERROR");
		}
		
		// Get player XP
		$sql = "SELECT xp FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$xp = $result[0][0];

		// Get player current level
		$sql = "SELECT character_level FROM hackcess.character WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$level = $result[0][0];

		$new_level = $xp / $level;
		if ($new_level >= 1000) {
			// Increase stats
			$dml = "UPDATE hackcess.character_details SET hp = hp + 1, current_hp = current_hp + 1, armor_class = armor_class + 1, attack = attack + 1, strength = strength + 4 WHERE character_id = " . $character_id . ";";
			$resultdml = insert($dml);
			if ($resultdml == TRUE) {
				addToDebugLog("updatePlayerOnMove(), Character details updated, INFO");
			} else {
				addToDebugLog("updatePlayerOnMove(), Character details not updated, ERROR");
			}				
			
			// Increase level
			$dml = "UPDATE hackcess.character SET character_level = character_level + 1 WHERE character_id = " . $character_id . ";";
			$resultdml = insert($dml);
			if ($resultdml == TRUE) {
				addToDebugLog("updatePlayerOnMove(), Character level updated, INFO");
			} else {
				addToDebugLog("updatePlayerOnMove(), Character level not updated, ERROR");
			}
			
			// Get player current level
			$sql = "SELECT character_level, character_role, character_name FROM hackcess.character WHERE character_id = " . $character_id . ";";
			$result = search($sql);
			$level = $result[0][0];
			$role = $result[0][1];
			$name = $result[0][2];

			//	Add journal entry
			$details = "LEVEL UP! " . $name . " is now a Level " . $level . " " . $role;
			$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("move(), New grid generated, INFO");
			} else {
				addToDebugLog("move(), New grid not generated, ERROR");
			}
			
		}
	
	}
	
	function createCharacter($player_id, $parent_character_id) {
		
		// Creates a new character
	
		addToDebugLog("createCharacter(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Parent Character ID: " . $parent_character_id . ", INFO");		
		
		// Create Character Name
		$name = generateCharacterName();
		
		// Create Role
		$role = generateRole();
		
		// Create record
		$dml = "INSERT INTO hackcess.character (player_id, character_name, character_role, character_level, status, generation, parent_id) VALUES (" . $player_id . ", '" . $name . "', '" . $role . "', 1, 'Alive', 0, 0);";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createCharacter(), New character generated, INFO");
		} else {
			addToDebugLog("createCharacter(), New character not generated, ERROR");
		}
		
		// Get new Character id
		$sql = "SELECT character_id FROM hackcess.character WHERE player_id = " . $player_id . " ORDER BY character_id DESC LIMIT 1;";
		$result = search($sql);
		$character_id = $result[0][0];
		
		// Update generational information is parent character id present
		if ($parent_character_id > 0) {
			
			// Get parent generation
			$parent_generation = getCharacterDetails($parent_character_id, "generation");
			$parent_generation = $parent_generation + 1;
			
			// Update new character with generation and parent id
			$dml = "UPDATE hackcess.character SET parent_id = " . $parent_character_id . ", generation = " . $parent_generation . " WHERE character_id = " . $character_id . ";";
			$resultdml = insert($dml);
			if ($resultdml == TRUE) {
				addToDebugLog("updatePlayerOnMove(), Character level updated, INFO");
			} else {
				addToDebugLog("updatePlayerOnMove(), Character level not updated, ERROR");
			}				
		}

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
	
		addToDebugLog("createJourneyName(), Function Entry - no parameters");
		
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
		
		$final_name = trim(ucfirst($name)) . " the " . trim(ucfirst($title));
		
		return $final_name;
		
	}
	
	function generateRole() {
		
		// Creates and returns a character name
	
		addToDebugLog("generateRole(), Function Entry - no parameters");		

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
	
		addToDebugLog("generateCharacterStatsItems(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
	
		// Create character items - Head
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Helm', 1, 0, 1, 'head', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(), Helm generated");
		} else {
			addToDebugLog("move(), ERROR: Helm not generated");
		}			

		// Create character items - Chest
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Chestplate', 1, 0, 1, 'chest', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(), Chestplate generated");
		} else {
			addToDebugLog("move(), ERROR: Chestplate not generated");
		}	

		// Create character items - Legs
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Trousers', 1, 0, 1, 'legs', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(), Trousers generated");
		} else {
			addToDebugLog("move(), ERROR: Trousers not generated");
		}
		
		// Create character items - Shield
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Shield', 1, 0, 1, 'shield', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(), Shield generated");
		} else {
			addToDebugLog("move(), ERROR: Shield not generated");
		}	

		// Create character items - Sword
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('Sword', 0, 1, 1, 'weapon', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(), Shield generated");
		} else {
			addToDebugLog("move(), ERROR: Shield not generated");
		}	
		
		// Get items ids for details table
		$sql = "SELECT equipment_id FROM hackcess.character_equipment WHERE character_id = " . $character_id . " ORDER BY character_id ASC;";
		$result = search($sql);
		$rows = count($result);
		if ($rows == 5) {
			$head = $result[0][0];
			$chest = $result[1][0];
			$legs = $result[2][0];
			$shield = $result[3][0];
			$sword = $result[4][0];
		} else {
			addToDebugLog("createCharacter(), Couldn't get item ids, ERROR");
		}
			
		// Create character details
		$dml = "INSERT INTO hackcess.character_details (character_id, hp, attack, armor_class, gold, xp, strength, head_slot, chest_slot, legs_slot, shield_slot, weapon_slot, current_hp) VALUES (" . $character_id . ", 20, 5, 1, 0, 0, 20, " . $head . ", " . $chest . ", " . $legs . ", " . $shield . ", " . $sword . ", 20);";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("move(), Character details stored");
		} else {
			addToDebugLog("move(), ERROR: Character details not stored");
		}	
		
	}
	
	function getItemNameById($item_id) {
		
		// Returns the item name
	
		addToDebugLog("getItemNameById(), Function Entry - supplied parameters: Item ID: " . $item_id . ", INFO");		

		$sql = "SELECT name, ac_boost, attack_boost FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
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
	
		addToDebugLog("createEnemy(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . "; Grid ID: " . $grid_id . ", INFO");		
		
		// Create Character Name
		$name = generateEnemyName();
		
		// Get character base stats
		$character_hp = getCharacterDetailsInfo($character_id, "hp");
		$character_ac = getCharacterDetailsInfo($character_id, "armor_class");
		$character_atk = getCharacterDetailsInfo($character_id, "attack");
		addToDebugLog("createEnemy(), Character Stats: HP: " . $character_hp . "; Character AC: " . $character_ac . "; Character ATK: " . $character_atk . ", INFO");	
		
		// Get character boosts from armour / weapons
		$boosts = getCharacterBoosts($character_id);
		$total_ac_boost = $boosts[0];
		$total_attack_boost = $boosts[1];
		
		// Generate enemy stats based on character's stats
		$enemy_hp = round($character_hp *0.75);
		if ($enemy_hp <= 0) { $enemy_hp = 1;}
		$enemy_ac = $character_atk + $total_attack_boost - rand(5, 10);
		if ($enemy_ac <= 0) { $enemy_ac = 1;}
		$enemy_atk = $character_ac + $total_ac_boost - rand(8, 15);
		if ($enemy_atk <= 0) { $enemy_atk = 1;}
		addToDebugLog("createEnemy(), Enemy Stats: HP: " . $enemy_hp . "; Character AC: " . $enemy_ac . "; Character ATK: " . $enemy_atk . ", INFO");	
		
		// Create enemy record
		$dml = "INSERT INTO hackcess.enemy (enemy_name, player_id, character_id, grid_id, atk, ac, hp, status) VALUES ('" . $name . "', " . $player_id . ", " . $character_id . ", " . $grid_id . ", " . $enemy_atk . ", " . $enemy_ac . ", " . $enemy_hp . ", 'Alive');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createEnemy(), New enemy generated");
		} else {
			addToDebugLog("createEnemy(), ERROR: New enemy not generated");
		}
		
		// Get new enemy id
		$sql = "SELECT enemy_id FROM hackcess.enemy WHERE grid_id = " . $grid_id . " LIMIT 1;";
		$result = search($sql);
		$enemy_id = $result[0][0];
		
		return $enemy_id;
	
	}
	
	function getEnemyInfo($enemy_id) {

		// Returns information about the supplied enemy
	
		addToDebugLog("getEnemyInfo(), Function Entry - supplied parameters: Enemy ID: " . $enemy_id . ", INFO");	
		
		$sql = "SELECT enemy_name, atk, ac, hp FROM hackcess.enemy WHERE enemy_id = " . $enemy_id . ";";
		$result = search($sql);
		
		return $result;		
		
	}
	
	function generateEnemyName() {
		
		// Creates and returns a enemy name
	
		addToDebugLog("generateEnemyName(), Function Entry - no parameters");
		
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
		addToDebugLog("generateEnemyName(), Selected creature: " . $creature . ", INFO");
		
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
		addToDebugLog("generateEnemyName(), Name: " . $name . ", INFO");
		
		$final_name = ucfirst($name) . " the " . ucfirst($creature);
		addToDebugLog("generateEnemyName(), Final name: " . $final_name . ", INFO");
		
		return $final_name;
		
	}
	
	function displayBattleStats($character_basic_info, $character_detailed_info, $enemy_info) {
		
		// Displays table of character and enemy_details
		
		addToDebugLog("displayBattleStats(), Function Entry - 3 parameters (arrays), INFO");
		
		// Heading: $character_basic_info; 0: id, 1: player id, 2: name, 3: role, 4: level
		// Column 1: $character_detailed_info; 2: hp, 3: attack, 4: ac, 5: gold, 6: xp, 7: strength, 8: head, 9: chest, 10: legs, 11: shield, 12: weapon, 13: current_hp.
		// Column 2: Character Equipment
		// Column 3: $enemy_info; 0: enemy_name, 1: atk, 2: ac, 3: hp 
		
		// Get character AC / ATK boosts from equipment
		$boosts = getCharacterBoosts($character_basic_info[0][0]);
		$total_ac_boost = $boosts[0];
		$total_attack_boost = $boosts[1];
		addToDebugLog("displayBattleStats(), Boosts: AC: " . $total_ac_boost . "; ATK: " . $total_attack_boost . ", INFO");
		
		// Get boosts from Effects
		$effect_boosts = getEffectBoosts($character_basic_info[0][0]); // 0 AC, 1 ATK, 2 HP, 3 STR 
		
		// Get equipment names
		$head = getItemNameById($character_detailed_info[0][8]);
		$chest = getItemNameById($character_detailed_info[0][9]);
		$legs = getItemNameById($character_detailed_info[0][10]);
		$shield = getItemNameById($character_detailed_info[0][11]);
		$weapon = getItemNameById($character_detailed_info[0][12]);
		
		echo "<table cellpadding=3 cellspacing=0 border=1 style='margin-left: auto; margin-right: auto;'>";
		echo "\n<tr>\n\t<td colspan=4 align=center width=600px><h2>" . trim($character_basic_info[0][2]) . "</h2>Level " . $character_basic_info[0][4] . " " . trim($character_basic_info[0][3]) . "\n\t<td valign=center align=center><h2>VS</h2>\n\t<td colspan=2 align=center valign=top width=300px><h2>" . $enemy_info[0][0] . "</h2>\n</tr>"; // Display character / enemy names
		
		// Head / HP
		echo "\n<tr>\n\t<td align=right width=50px>Head\n\t<td width=150px>" . $head;
		if ($effect_boosts["hp"] > 0) {
			echo "\n\t<td align=center width=50px>" . $character_detailed_info[0][2] . " (+" . $effect_boosts["hp"] . ")";
			$total = $effect_boosts[2] + $character_detailed_info[0][13];
			echo "\n\t<td width=250px align=right>" . barGraph($total, 'char');
		} else {
			echo "\n\t<td align=center width=50px>" . $character_detailed_info[0][2];
			echo "\n\t<td width=250px align=right>" . barGraph($character_detailed_info[0][13], 'char');
		}
		echo "\n\t<td align=center>HP\n\t<td>" . barGraph($enemy_info[0][3], 'enemy');
		echo "<td align=center>" . $enemy_info[0][3] . "\n</tr>";
		
		// Chest / AC
		echo "\n<tr>\n\t<td align=right>Chest\n\t<td>" . $chest;
		echo "\n\t<td align=center>" . $character_detailed_info[0][4];
		if ($effect_boosts["ac"] > 0) {
			$total = $effect_boosts["ac"] + $total_ac_boost;
			echo " (+" . $total . ")";
			echo "\n\t<td align=right>" . barGraph($character_detailed_info[0][4] + $total, 'char');
		} else {
			echo " (+" . $total_ac_boost . ")";
			echo "\n\t<td align=right>" . barGraph($character_detailed_info[0][4] + $total_ac_boost, 'char');
		}
		echo "\n\t<td align=center>AC\n\t<td>" . barGraph($enemy_info[0][2], 'enemy');
		echo "\n\t<td align=center>" . $enemy_info[0][2] . "\n</tr>";
		
		// Legs / ATK
		echo "\n<tr>\n\t<td align=right>Legs\n\t<td>" . $legs;
		echo "\n\t<td align=center>" . $character_detailed_info[0][3];
		if ($effect_boosts["atk"] > 0) {
			$total = $effect_boosts["atk"] + $total_atk_boost;
			echo " (+" . $total . ")";
			echo "\n\t<td align=right>" . barGraph($character_detailed_info[0][3] + $total, 'char');
		} else {
			echo " (+" . $total_attack_boost . ")";
			echo "\n\t<td align=right>" . barGraph($character_detailed_info[0][3] + $total_attack_boost, 'char');
		}
		echo "\n\t<td align=center>ATK\n\t<td>" . barGraph($enemy_info[0][1], 'enemy');
		echo "\n\t<td align=center>" . $enemy_info[0][1] . "\n</tr>";
		
		// Shield / STR
		echo "\n<tr>\n\t<td align=right>Shield\n\t<td>" . $shield;
		if ($effect_boosts["str"] > 0) {
			$total = $character_detailed_info[0][7] + $effect_boosts["str"]; 
			echo "\n\t<td align=right colspan=2>" . $total;
		} else {
			echo "\n\t<td align=right colspan=2>" . $character_detailed_info[0][7];
		}
		
		echo "\n\t<td align=center>STR\n\t<td colspan=2>\n</tr>";
		
		// XP
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
		
		addToDebugLog("getCharacterBoosts(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		$sql = "SELECT ac_boost, attack_boost, slot, equipment_id FROM hackcess.character_equipment WHERE character_id = " . $character_id . " AND slot NOT LIKE 'potion%';";
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
		addToDebugLog("getCharacterBoosts(), AC Boost: " . $total_ac_boost . "; Attack Boost: " . $total_attack_boost . ", INFO");
		$details = array();
		$details[0] = $total_ac_boost;
		$details[1] = $total_attack_boost;
		
		return $details;
		
	}
	
	function flee($character_id, $journey_id, $grid_id, $enemy_id) {
		
		// This function handles where the character runs away from a fight
		
		addToDebugLog("getCharacterBoosts(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");	

		// Reduce character HP
		$hp = rand(1, 5);
		$dml = "UPDATE hackcess.character_details SET current_hp = current_hp - " . $hp . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("flee(), Character record updated, INFO");
		} else {
			addToDebugLog("flee(), Character record not updated, ERROR");
		}		
		
		// Get enemy name
		$enemy_info = getEnemyInfo($enemy_id);
		$name = $enemy_info[0][0];
		
		// Record entry in Journal
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", 'Ran from a fight with " . $name . ", lost " . $hp . "HP.');";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("flee(), New journal entry added, INFO");
		} else {
			addToDebugLog("flee(), ERROR: New journal entry not added, ERROR");
		}			

	}
	
	function manageEquipment($player_id, $character_id, $journey_id) {
		
		// This function lists the equipment held by each player
		
		addToDebugLog("manageEquipment(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . ", INFO");
		
		echo "<table cellpadding=3 cellspacing=0 border=1 align=center>";
		echo "<tr bgcolor=#bbb><td>Item<td align=center>Weight<td align=center>Actions</tr>";

		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . " AND slot NOT LIKE 'potion%' ORDER BY slot ASC, ac_boost, attack_boost DESC;";
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
				echo "<tr><td colspan=4 bgcolor=#ddd align=center>" . ucfirst($result[$e][5]) . "</tr>";
				$current_slot = $result[$e][5];
			}
		
			$bonus = $result[$e][2] + $result[$e][3];
			echo "<tr><td>+" . $bonus . " " . $result[$e][1]; // Bonus + Item
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

		// Display Character potions
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . " AND slot LIKE 'potion%' ORDER BY ac_boost, attack_boost DESC;";
		$result = search($sql);
		$rows = count($result);
		
		if ($rows > 0) {
		
			echo "<tr><td colspan=5 bgcolor=#ddd align=center>Potions</tr>";
			
			for ($p = 0; $p < $rows; $p++) {
					
				echo "<tr><td>" . $result[$p][1]; // Item
				echo "<td align=center>-"; // Weight
					
				// Write action: Drink
				echo "<td align=center>";
				echo "<a href='equipment.php?item_id=" . $result[$p][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&action=drink'>Drink</a>";
				echo "</tr>";
					
			}
		}
		
		echo "<tr bgcolor=#ddd><td align=right>Total Weight<td align=center>" . $weight_total . "<td></tr>";
		
		// Get character strength
		$effects = getEffectBoosts($character_id);
		$character_strength = getCharacterDetailsInfo($character_id, 'strength') + $effects["str"];
		echo "<tr bgcolor=#ddd><td align=right>Strength<td align=center>" . $character_strength . "<td></tr>";
		
		echo "</table>";
		
		if ($weight_total > $character_strength) {
			return "overweight";
		} else {
			return "ok";
		}
		
	}
	
	function isEquipped($slot, $item_id, $character_id) {

		// This function lists the equipment held by each player
		
		addToDebugLog("isEquipped(), Function Entry - supplied parameters: Slot Name: " . $slot . "; Item ID: " . $item_id . "; Character ID: " . $character_id . ", INFO");
		
		$sql = "SELECT " . $slot . "_slot FROM hackcess.character_details WHERE character_id = " . $character_id . ";";
		$result = search($sql);

		if ($result[0][0] == $item_id) {
			return 1;
		} else {
			return 0;
		}
		
	}
	
	function equip($slot, $item_id, $character_id) {
		
		// This function equips the item selected
		
		addToDebugLog("equip(), Function Entry - supplied parameters: Slot Name: " . $slot . ", Item ID: " . $item_id . "l Character ID: " . $character_id . ", INFO");	

		// Equip item
		$dml = "UPDATE hackcess.character_details SET " . $slot . "_slot = " . $item_id . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("flee(), Item equipped, INFO");
		} else {
			addToDebugLog("flee(), Item not equipped, ERROR");
		}
		
	}
	
	function createRandomItem($character_id, $character_ac_boost, $character_atk_boost) {
		
		// This function equips the item selected
		
		addToDebugLog("createRandomItem(), Function Entry - supplied parameters: Character ID: " . $character_id . "; Character AC Boost: " . $character_ac_boost . "; Character Attack Boost: " . $character_atk_boost . ", INFO");

		srand(make_seed());
		$item_choice = rand(0, 4);
		
		switch ($item_choice) {
			case 0: // Head
				$slot = "head";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Helm";
				$ac_start = intval(ceil($character_ac_boost/4));
				$ac_boost = rand($ac_start-2, $ac_start+2);
				if ($ac_boost < 1) { $ac_boost = 1; };
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 1: // Chest
				$slot = "chest";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Chestplate";
				$ac_start = intval(ceil($character_ac_boost/4));
				$ac_boost = rand($ac_start-2, $ac_start+2);
				if ($ac_boost < 1) { $ac_boost = 1; };
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 2: // Legs
				$slot = "legs";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Trousers";
				$ac_start = intval(ceil($character_ac_boost/4));
				$ac_boost = rand($ac_start-2, $ac_start+2);
				if ($ac_boost < 1) { $ac_boost = 1; };
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 3: // Shield
				$slot = "shield";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Shield";
				$ac_start = intval(ceil($character_ac_boost/4));
				$ac_boost = rand($ac_start-2, $ac_start+2);
				if ($ac_boost < 1) { $ac_boost = 1; };
				$weight = round($ac_boost/2);
				$attack_boost = 0;
				$details = "+" . $ac_boost . " " . $name;
				break;
			case 4: // Weapon
				$slot = "weapon";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Sword";
				$attack_boost = rand(intval($character_atk_boost)-2, intval($character_atk_boost)+2);
				$weight = round($attack_boost/2);
				$ac_boost = 0;
				$details = "+" . $attack_boost . " " . $name;
				break;
		}
		
		// Add weapon to character
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('" . $name . "', " . $ac_boost . ", " . $attack_boost . ", " . $weight . ", '" . $slot . "', " . $character_id  .");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createRandomItem(), New item added, INFO");
		} else {
			addToDebugLog("createRandomItem(), New item not added, ERROR");
		}
		
		return $details;
		
	}

	function drop($equipment_id) {
		
		// This function equips the item selected
		
		addToDebugLog("drop(), Function Entry - supplied parameters: Equipment ID: " . $equipment_id . ", INFO");	
		
		$dml = "DELETE FROM hackcess.character_equipment WHERE equipment_id = " . $equipment_id . ";";
		$result = delete($dml);
		if ($result == TRUE) {
			addToDebugLog("drop(), Item deleted, INFO");
		} else {
			addToDebugLog("drop(), Item not deleted, ERROR");
		}
		
	}
	
	function createPlayer($name) {
		
		// This function probably dosen't work
		
		addToDebugLog("createPlayer(), Function Entry - supplied parameters: Name: " . $name . ", INFO");
		
		$dml = "INSERT INTO hackcess.user (name) VALUES ('" . $name . "')";
		$result = delete($dml);
		if ($result == TRUE) {
			addToDebugLog("createPlayer(), Player created, INFO");
		} else {
			addToDebugLog("createPlayer(), Player not created, ERROR");
		}
		
	}
	
	function equipmentWeight($character_id) {
		
		// This function equips the item selected
		
		addToDebugLog("equipmentWeight(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");		

		$sql = "SELECT weight FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
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
		
		addToDebugLog("getBestItem(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");		
		
		$sql = "SELECT equipment_id FROM hackcess.character_equipment WHERE character_id = " . $character_id . " ORDER BY attack_boost, ac_boost DESC LIMIT 1;";
		$result = search($sql);
		$best_item_id = $result[0][0];	
		
		return $best_item_id;
		
	}
	
	function getItemSummary($item_id) {
		
		// This function returns the summary for the supplied item_id
		
		addToDebugLog("getItemSummary(), Function Entry - supplied parameters: Item ID: " . $item_id . ", INFO");
		
		$sql = "SELECT name, ac_boost, attack_boost FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
		$result = search($sql);
		
		if ($result[0][1] == 0) {
			$name = "+" . $result[0][2] . " " . $result[0][0];
		} else {
			$name = "+" . $result[0][1] . " " . $result[0][0];
		}
		addToDebugLog("getItemSummary(), Item Summary: " . $name . ", INFO");
		
		return $name;		
		
	}
	
	function showCharacterHistory($character_id) {
		
		// This function displays the combat history for the selected character
		
		addToDebugLog("getItemSummary(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
		
		$sql = "SELECT * FROM hackcess.fight WHERE character_id = " . $character_id . ";";
		$result = search($sql);	
		$rows = count($result);
		
		echo "<table cellpadding=3 cellspacing=0 border=1 align=center>";
		echo "<tr bgcolor=#ddd><td><td>Fight<td>Outcome<td>Enemy<td align=center>AC<td align=center>ATK<td>Rounds</tr>";
		
		for ($r = 0; $r < $rows; $r++) {
			
			$index = $r+1;
			echo "<td align=center>" . $index;
			
			// Fight Number
			echo "<td align=center>" . $result[$r][0];
			
			//Outcome
			if ($result[$r][5] == 1) {
				echo "<td align=center>Defeated";
			} else {
				echo "<td align=center>Beaten by";
			}
			
			// Enemy Name
			$enemy_details = getEnemyInfo($result[$r][2]);
			
			echo "<td>" . $enemy_details[0][0] . "<td align=center>+" . $enemy_details[0][2] . "<td align=center>+" . $enemy_details[0][1];
			
			// Rounds
			if ($result[$r][5] == 1) {
				echo "<td align=right>" . barGraph($result[$r][4], 'char') . "</tr>";
			} else {
				echo "<td align=left>" . barGraph($result[$r][4], 'enemy') . "</tr>";
			}
			
		}
		echo "</table>";
		
	}

	function updateTitle($character_id) {
		
		// This function wil update the title for the selected character if necessary
		
		addToDebugLog("updateTitle(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");		
		
		// check how many wins the character has
		$sql = "SELECT count(*) FROM hackcess.fight WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = $result[0][0];
		addToDebugLog("updateTitle(), Wins: " . $rows . ", INFO");
		
		$titles = array(1 => "The Well-known",
						2 => "The Renowned",
						3 => "The Notorious", 
						4 => "The Mighty",
						5 => "The Glorious",
						6 => "The Infamous",
						7 => "The Exalted",
						8 => "The Legendary",
						9 => "The Godlike",
						10 => "The Mythical"
					);
		
		// If wins is divisible by 10, update the title to the next level
		// from the array, get the previous title and strip it off, before adding the new one
		
		$title = $titles[($rows/10)];
		if ($title != "") {
			
			// Get current character name
			$character_name = getCharacterDetails($character_id, "character_name");

			$name_components = explode(" ", $character_name);
			if (count($name_components) == 5) {
				$untitled_name = $name_components[2] . " " . $name_components[3] . " " . $name_components[4];
			} else {
				$untitled_name = $character_name;
			}
			addToDebugLog("updateTitle(), Character Name with title removed: " . $untitled_name . ", INFO");
			
			$new_name = $title . " " . $untitled_name;
			
			addToDebugLog("updateTitle(), New Title: " . $title . ", INFO");
			addToDebugLog("updateTitle(), New Name: " . $new_name . ", INFO");
			
			// Update Character details
			$dml = "UPDATE hackcess.character SET character_name = '" . $new_name . "' WHERE character_id = " . $character_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("updateTitle(), Character record updated, INFO");
			} else {
				addToDebugLog("updateTitle(), Character record not updated, ERROR");
			}
		
		} else {
			addToDebugLog("updateTitle(), No need to update title, INFO");
		}
		
	}
	
	function isCharacterOverloaded($character_id) {
		
		// This function determines if the character is overencumbered
		
		addToDebugLog("manageEquipment(), Function Entry - supplied parameters: Character ID: " . $charcter_id . ", INFO");
		
		// Get total weight of player equipment
		$sql = "SELECT sum(weight) FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$equipment_weight = $result[0][0];
		
		// Get total strength, including boosts
		$effects = getEffectBoosts($character_id);
		$character_strength = getCharacterDetailsInfo($character_id, 'strength') + $effects["str"];
		
		if ($equipment_weight > $character_strength) {
			return 1; // Overencumbered
		} else {
			return 0; // OK
		}
		
	}
	
	function allTilesArray() {
		addToDebugLog("allTilesArray(), Function Entry");
		$sql = "SELECT * FROM hackcess.grid";
		$result = search($sql);
		$returnArray = []
		foreach ($result as &$i) {
			$returnArray[] = $i;
		}
		return $returnArray
	}
	
?>
