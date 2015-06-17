<?php

	function buyItem($store_id, $journey_id, $character_id, $player_id, $item_id) {
	
		// Buys an item from the store
	
		addToDebugLog("buyItem(), Function Entry - Store ID: " . $store_id . ", Journey ID: " . $journey_id . "; Character ID: " . $character_id . "; Player ID: " . $player_id . "; Item ID: " . $item_id . ", INFO");
	
		// Get item details from the store
		$sql = "SELECT * FROM hackcess.store_contents WHERE contents_id = " . $item_id . ";";
		$result = search($sql);
		$name = $result[0][2];
		$ac_boost = $result[0][3];
		$atk_boost = $result[0][4];
		$weight = $result[0][5];
		$slot = $result[0][6];
		$cost = $result[0][7];
	
		// Remove gold from player
		$dml = "UPDATE hackcess.character_details SET gold = gold - " . $cost . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("buyItem(), Character record updated, INFO");
		} else {
			addToDebugLog("buyItem(), Character record not updated, ERROR");
		}
		
		// If the item is a pet, determine the Level and attach to the name
		if (substr($slot, 0, 3) == "pet") {
			$level = ($cost - 5000) / 500;
			$final_name = $name . "," . $level;
		}
	
		// Add item to player equipment
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('" . $final_name . "', " . $ac_boost . ", " . $atk_boost . ", " . $weight . ", '" . $slot . "', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("buyItem(), Item added to player inventory, INFO");
			
			// Determine pet id
			$sql = "SELECT pet_id FROM hackcess.pets WHERE pet_name LIKE '" . $name . "%';";
			$result = search($sql);
			$pet_id = $result[0][0];  
			
			// Assign pet to character
			$dml = "UPDATE hackcess.pets SET character_id = " . $character_id . " WHERE pet_id = " . $pet_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("buyItem(), Character record updated, INFO");
			} else {
				addToDebugLog("buyItem(), Character record not updated, ERROR");
			}			
				
			// Remove item from store
			$dml = "DELETE FROM hackcess.store_contents WHERE contents_id = " . $item_id . ";";
			$result = delete($dml);
			if ($result == TRUE) {
				addToDebugLog("buyItem(), Item deleted from store, INFO");
			} else {
				addToDebugLog("buyItem(), Item not deleted from store, ERROR");
			}
				
		} else {
			addToDebugLog("buyItem(), Item not added to player inventory, ERROR");
		}
	
		outputDebugLog();
		
		// Redirect back to store page
		echo "<script>window.location.href = 'store.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "'</script>";
	
	}

	function characterEquipment($character_id, $player_id, $journey_id, $store_id) {
		
		// This function lists the equipment held by each player
	
		addToDebugLog("characterEquipment(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . "; Store ID: " . $store_id . ", INFO");
		
		// Get Character Name
		$character_name = getCharacterDetails($character_id, 'character_name');
	
		echo "<table cellpadding=3 cellspacing=0 border=1>";
		echo "<tr><td colspan=5 align=center><h2>" . $character_name . "</h2></tr>";
		echo "<tr bgcolor=#bbb><td>Item<td align=center>Weight<td align=center>Value<td align=center>Actions</tr>";
	
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . " AND slot NOT LIKE 'potion%' AND slot NOT LIKE 'pet%' ORDER BY slot ASC, ac_boost, attack_boost DESC;";
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
				echo "<tr><td colspan=5 bgcolor=#ddd align=center>" . ucfirst($result[$e][5]) . "</tr>";
				$current_slot = $result[$e][5];
			}
	
			$bonus = $result[$e][2] + $result[$e][3];
			echo "<tr><td>+" . $bonus . " " . $result[$e][1]; // Bonus + Item
			echo "<td align=center>" . $result[$e][4]; // Weight
			
			//Value
			$value = $bonus * 50;
			echo "<td align=center>" . $value;
	
			// Determine if the item of equipment is equipped or not
			$is_equipped = isEquipped($result[$e][5], $result[$e][0], $character_id);
			$weight_total = $weight_total + $result[$e][4];
			if ($is_equipped == 1) {
				echo "<td align=center bgcolor=#6f3>Equipped";
					
			} else {
				echo "<td align=center>";
				echo "<a href='store.php?slot=" . $result[$e][5] . "&item_id=" . $result[$e][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "&action=sell'>Sell</a>"; // $slot, $item_id, $character_id
				echo " | <a href='store.php?slot=" . $result[$e][5] . "&item_id=" . $result[$e][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "&action=equip&slot=" . $result[$e][5] . "'>Equip</a>";
			}
				
			echo "</tr>";
				
		}
	
		// Display Character potions
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . " AND slot LIKE 'potion%' ORDER BY ac_boost, attack_boost DESC;";
		$result = search($sql);
		$rows = count($result);	

		echo "<tr><td colspan=5 bgcolor=#ddd align=center>Potions</tr>";
		
		for ($p = 0; $p < $rows; $p++) {
			
			echo "<tr><td>" . $result[$p][1]; // Item
			echo "<td align=center>-"; // Weight
				
			//Value
			$potion_name = explode(' ', $result[$p][1]);
			$value = $potion_name[3] * 100;
			echo "<td align=center>" . $value;
			
			// Write actions Sell
			echo "<td align=center>";
			echo "<a href='store.php?slot=" . $result[$p][5] . "&item_id=" . $result[$p][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "&action=sell'>Sell</a>"; // $slot, $item_id, $character_id
			echo "</tr>";			
			
		}

		// Display Character pets
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . " AND slot LIKE 'pet%';";
		$result = search($sql);
		$rows = count($result);
		
		echo "<tr><td colspan=5 bgcolor=#ddd align=center>Pets</tr>";
		
		for ($p = 0; $p < $rows; $p++) {
				
			$details = explode(',', $result[$p][1]);
			$type = substr($result[$p][5], 4);
			
			echo "<tr><td>" . $details[0] . ", Lvl " . $details[1] . " " . ucfirst($type); // Item
			echo "<td align=center>-"; // Weight
		
			//Value
			$value = ($details[1] * 500) + 5000;
			echo "<td align=center>" . $value;
				
			// Write actions Sell
			echo "<td align=center>";
			echo "<a href='store.php?slot=" . $result[$p][5] . "&item_id=" . $result[$p][0] . "&character_id=" . $character_id . "&player_id=" . $player_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "&action=sell'>Sell</a>"; // $slot, $item_id, $character_id
			echo "</tr>";
				
		}
		
		// Get character strength		
		$character_strength = getCharacterDetailsInfo($character_id, 'strength');
		$effects = getEffectBoosts($character_id);
		$traits = getTraitBoosts($character_id);
		$total_strength = $character_strength + $effects["str"] + $traits["str"];
		
		echo "<tr><td align=right>Total Weight<td align=center>" . $weight_total;
		echo "<td align=center>" . $total_strength . "<td align=left>Strength</tr>";
		
		// Character Gold
		$character_gold = getCharacterDetailsInfo($character_id,'gold');
		echo "<tr><td align=right colspan=2>Character Gold<td align=center>" . $character_gold . "<td></tr>";
		
		echo "</table>";
	 
	}

	function characterEquipmentWeight($character_id) {
	
		// This function returns the weight of the equipment held by each player
	
		addToDebugLog("characterEquipmentWeight(), Function Entry - supplied parameters: Character ID: " . $character_id . ", INFO");
	
		$sql = "SELECT weight FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		$weight = 0;
		for ($r = 0; $r < $rows; $r++) {
			$weight = $weight + $result[$r][0];
		}
		addToDebugLog("characterEquipmentWeight(), Total Weight: " . $weight . ", INFO");
	
		return $weight;
	
	}
	
	function createStoreItem($slot, $store_id, $min_level) {
	
		// This function creates a store item
	
		addToDebugLog("createRandomItem(), Function Entry - supplied parameters: Store ID: " . $store_id . "; Slot: " . $slot . "; Min Level: " . $min_level . ", INFO");
	
		srand(make_seed());
		$item_choice = rand(0, 4);
	
		switch ($item_choice) {
			case 0: // Head
				$slot = "head";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Helm";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(), AC: " . $ac . ", INFO");
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 1: // Chest
				$slot = "chest";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Chestplate";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(), AC: " . $ac . ", INFO");
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 2: // Legs
				$slot = "legs";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Trousers";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(), AC: " . $ac . ", INFO");
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50 * intval($ac);
				break;
			case 3: // Shield
				$slot = "shield";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Shield";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(), AC: " . $ac . ", INFO");
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 4: // Weapon
				$slot = "weapon";
				$prefix = getAdjective();
				$name = ucfirst($prefix) . " Sword";
				srand(make_seed());
				$atk = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(), ATK: " . $atk . ", INFO");
				$weight = round($atk/2);
				$ac = 0;
				$cost = 50*$atk;
				break;
		}
	
		// Add weapon to character
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $name . "', " . $ac . ", " . $atk . ", " . $weight . ", '" . $slot  . "', " . $cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createRandomItem(), New item added, INFO");
		} else {
			addToDebugLog("createRandomItem(), New item not added, ERROR");
		}
	
		return $details;
	
	}
	
	function getAdjective() {
	
		// This function returns a random adjective for store items.
	
		addToDebugLog("getAdjective(), Function Entry - No parameters, INFO");
	
		// Determine the consonant to use for the name
		$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "y", "z");
		$consonant = $consonants[rand(0, 20)];
	
		// Choose the title based on the consonant
		$filepath = "lists/adjectives/" . $consonant . ".txt";
		$titles = file($filepath); // reads contents of select file into the array
		$titles_length = count($titles);
		$title = "";
		while ($title == "") {
			srand(make_seed());
			$title = $titles[rand(0, $titles_length)];
		}
	
		addToDebugLog("getAdjective(), Adjective: " . $title . ", INFO");
	
		return $title;
	
	}
	
	function getStoreContentsBySlot($store_id, $journey_id, $character_id, $player_id, $slot) {

		// Lists items in the store for the provided slot
		
		addToDebugLog("manageEquipment(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . "; Store ID: " . $store_id . ", INFO");

		$row_limit = 3;
		
		if ($slot == 'weapon') {
			$order_by = "item_attack_boost DESC";
		} else {
			$order_by = "item_ac_boost DESC";
		}
		
		$sql = "SELECT * FROM hackcess.store_contents WHERE store_id  = " . $store_id . " AND item_slot = '" . $slot . "' ORDER BY " . $order_by . " LIMIT " . $row_limit . ";";
		$result = search($sql);
		$rows = count($result);
		if ($rows < $row_limit) {
			$row_limit = $rows;
		}
		
		if ($rows > 0) {
		
			// Get character gold
			$character_gold = getCharacterDetailsInfo($character_id, 'gold');
	
			echo "<tr><td colspan=5 bgcolor=#ddd align=center>" . ucfirst($slot) . "</tr>";
			
			for ($i = 0; $i < $row_limit; $i++) {
		
				$boost = $result[$i][3] + $result[$i][4];
				echo "<tr><td>+" . $boost . " " . $result[$i][2]; // Item and Boost
				echo "<td align=center>" . $result[$i][5]; // Weight
				echo "<td align=center>" . $result[$i][7]; // Value
				if ($result[$i][7] <= $character_gold) { 
					echo "<td align=center><a href='store.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&store_id=" . $store_id . "&action=buy&item_id=" . $result[$i][0] . "'>Buy</a>"; // Action
				} else {
					echo "<td align=center>-";
				}
					
				echo "</tr>";
					
			}
		
		}
		
	}
	
	function getStoreContentPotions($store_id, $journey_id, $character_id, $player_id) {
		
		// Lists potions in the store
		
		addToDebugLog("getStoreContentPotions(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . ", INFO");
		
		$row_limit = 5;
		
		$sql = "SELECT * FROM hackcess.store_contents WHERE store_id  = " . $store_id . " AND item_slot LIKE 'potion%' ORDER BY item_cost DESC LIMIT " . $row_limit . ";";
		$result = search($sql);
		$rows = count($result);
		if ($rows < $row_limit) {
			$row_limit = $rows;
		}
		
		if ($rows > 0) {
		
			// Get character gold
			$character_gold = getCharacterDetailsInfo($character_id, 'gold');
		
			echo "<tr><td colspan=5 bgcolor=#ddd align=center>Potions</tr>";
				
			for ($i = 0; $i < $row_limit; $i++) {
		
				echo "<tr><td>" . $result[$i][2]; // Item and Boost
				echo "<td align=center>" . $result[$i][5]; // Weight
				echo "<td align=center>" . $result[$i][7]; // Value
				if ($result[$i][7] <= $character_gold) {
					echo "<td align=center><a href='store.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&store_id=" . $store_id . "&action=buy&item_id=" . $result[$i][0] . "'>Buy</a>"; // Action
				} else {
					echo "<td align=center>-";
				}
					
				echo "</tr>";
					
			}
		
		}
		
	}
	
	function getStoreContentPets($store_id, $journey_id, $character_id, $player_id) {
	
		// Lists potions in the store
	
		addToDebugLog("getStoreContentPets(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . ", INFO");
	
		$row_limit = 5;
	
		$sql = "SELECT * FROM hackcess.store_contents WHERE store_id  = " . $store_id . " AND item_slot LIKE 'pet%' ORDER BY item_cost DESC LIMIT " . $row_limit . ";";
		$result = search($sql);
		$rows = count($result);
		if ($rows < $row_limit) {
			$row_limit = $rows;
		}
	
		if ($rows > 0) {
	
			// Get character gold
			$character_gold = getCharacterDetailsInfo($character_id, 'gold');
	
			echo "<tr><td colspan=5 bgcolor=#ddd align=center>Pets</tr>";
	
			for ($i = 0; $i < $row_limit; $i++) {
	
				$level = ($result[$i][7] - 5000)/500;
				$type = substr($result[$i][6], 4);
				
				echo "<tr><td>" . $result[$i][2] . ", Lvl " . $level . " " . ucfirst($type); // Item Name, Level and Type
				echo "<td align=center>-"; // Weight
				echo "<td align=center>" . $result[$i][7]; // Value
				if ($result[$i][7] <= $character_gold) {
					echo "<td align=center><a href='store.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&store_id=" . $store_id . "&action=buy&item_id=" . $result[$i][0] . "'>Buy</a>"; // Action
				} else {
					echo "<td align=center>-";
				}
					
				echo "</tr>";
					
			}
	
		}
	
	}
	
	function getStoreName($store_id) {
	
		// This function returns the store name for the provided store id
	
		addToDebugLog("getStoreName(), Function Entry - supplied parameters: Store ID: " . $store_id . ", INFO");
	
		$sql = "SELECT store_name FROM hackcess.store where store_id = " . $store_id . ";";
		$result = search($sql);
	
		return $result[0][0];
	
	}
	
	function generateStore($grid_id, $journey_id, $character_id) {
		
		// Creates and returns a store name
		
		global $debug_enabled;
		
		addToDebugLog("generateStore(), Function Entry - Parameters: Grid ID: " . $grid_id . "; Journey ID: " . $journey_id . "; Character ID: " . $character_id . ", INFO");
		
		// Store will have 0-2 of each item
		// Items will be range from lowest modifier of existing items, to 5 levels above the highest modifier
		// Cost of an item will be 50 x the modifier value
		
		// Generate Store Name
		$store_name = generateStoreName();
		
		// Create store
		$dml = "INSERT INTO hackcess.store (store_name, grid_id, journey_id, character_id) VALUES ('" . $store_name . "', " . $grid_id . ", " . $journey_id . ", " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("generateStore(), New journal entry added, INFO");
		} else {
			addToDebugLog("generateStore(), New journal entry not added, ERROR");
		}
		
		// Get store ID
		$sql = "SELECT store_id FROM hackcess.store ORDER BY store_id DESC LIMIT 1;";
		$result = search($sql);
		$store_id = $result[0][0];
		
		// Populate with items
		
		// Get character lowest rank equipped item
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		$result = search($sql);
		$rows = count($result);
		
		$min_ac = 1000;
		$min_atk = 1000;
		for ($i = 0; $i < $rows; $i++) {
			$is_equipped = isEquipped($result[$i][5], $result[$i][0], $character_id);
			if ($is_equipped == 1 && $result[$i][2] > 0 && $result[$i][2] < $min_ac) { // AC
				$min_ac = $result[$i][2];
			}
			if ($is_equipped == 1 && $result[$i][3] > 0 && $result[$i][3] < $min_atk) { // ATK
				$min_atk = $result[$i][3];
			}			
		}
		addToDebugLog("generateStore(), Min AC: " . $min_ac . "; Min ATK: " . $min_atk . ", INFO");

		// Head Items
		srand(make_seed());
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('head', $store_id, $min_ac);
		}

		// Chest Items
		srand(make_seed());
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('chest', $store_id, $min_ac);
		}
		
		// Legs Items
		srand(make_seed());
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('legs', $store_id, $min_ac);
		}
		
		// Shield Items
		srand(make_seed());
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('shield', $store_id, $min_ac);
		}
		
		// Sword Items
		srand(make_seed());
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('weapon', $store_id, $min_atk);
		}

		// Potions
		srand(make_seed());
		$num_items = rand(1, 5);
		for ($item = 0; $item < $num_items; $item++) {
			createPositiveEffect($store_id);
		}

		// Pets
		srand(make_seed());
		$num_items = rand(0, 2);
		if ($debug_enabled == 1) { $num_items = 1; }
		for ($item = 0; $item < $num_items; $item++) {
			createNewPet($store_id);
		}
		
	}

	function generateStoreName() {
	
		// Creates and returns a store name
	
		addToDebugLog("generateEnemyName(), Function Entry - no parameters");
	
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
		addToDebugLog("generateStoreName(), Name: " . $name . ", INFO");
	
		$final_name = $name . "&apos;s Store";
		addToDebugLog("generateStoreName(), Final name: " . $final_name . ", INFO");
	
		return $final_name;
	
	}
	
	function isThereAStoreHere($grid_id) {
	
		// This function returns the store id (if present) for the provided grid id
	
		addToDebugLog("isThereAStoreHere(), Function Entry - supplied parameters: Grid ID: " . $grid_id . ", INFO");
	
		$sql = "SELECT store_id FROM hackcess.store where grid_id = " . $grid_id . ";";
		addToDebugLog("isThereAStoreHere(), Constructed query: " . $sql . ", INFO");
		$result = search($sql);
	
		return $result[0][0];
	
	}
	
	function sellItem($store_id, $journey_id, $character_id, $player_id, $item_id) {

		// Sells an item from the characters equipment
		
		addToDebugLog("sellItem(), Function Entry - Store ID: " . $store_id . ", Journey ID: " . $journey_id . "; Character ID: " . $character_id . "; Player ID: " . $player_id . "; Item ID: " . $item_id . ", INFO");
		
		// Get item details from the character
		$sql = "SELECT * FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
		addToDebugLog("sellItem(), Constructed query: " . $sql . ", INFO");
		$result = search($sql);
		$name = $result[0][1];
		$ac_boost = $result[0][2];
		$attack_boost = $result[0][3];
		$weight = $result[0][4];
		$slot = $result[0][5];
		if (substr($result[0][5], 0, 6) == 'potion') {
			$name_elements = explode(' ', $name);
			$cost = 100 * $name_elements[3];
		} elseif (substr($result[0][5], 0, 3) == 'pet') {
			$name_elements = explode(',', $name);
			$cost = ($name_elements[1] * 500) + 5000;
		} else {
			$cost = 50 * ($ac_boost + $attack_boost);
		}
		
		// Add value to player gold
		$dml = "UPDATE hackcess.character_details SET gold = gold + " . $cost . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("sellItem(), Character record updated, INFO");
		} else {
			addToDebugLog("sellItem(), Character record not updated, ERROR");
		}		
		
		// Add item to store
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $name_elements[0] . "', " . $ac_boost . ", " . $attack_boost . ", " . $weight . ", '" . $slot . "', " . $cost . ");";
		$result = insert($dml);		
		if ($result == TRUE) {
			addToDebugLog("sellItem(), Item added to store, INFO");

			// Determine pet id
			$sql = "SELECT pet_id FROM hackcess.pets WHERE pet_name = '" . $name_elements[0] . "';";
			$result = search($sql);
			$pet_id = $result[0][0];
				
			// Unassign pet from character
			$dml = "UPDATE hackcess.pets SET character_id = 0 WHERE pet_id = " . $pet_id . ";";
			$result = insert($dml);
			if ($result == TRUE) {
				addToDebugLog("buyItem(), Character record updated, INFO");
			} else {
				addToDebugLog("buyItem(), Character record not updated, ERROR");
			}
			
			// Remove item from store
			$dml = "DELETE FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
			$result = delete($dml);
			if ($result == TRUE) {
				addToDebugLog("sellItem(), Item deleted from character equipment, INFO");
			} else {
				addToDebugLog("sellItem(), Item not deleted from character equipment, ERROR");
			}
				
		} else {
			addToDebugLog("sellItem(), Item not added to store, ERROR");
		}
		
		outputDebugLog();
		
		// Redirect back to store page
		echo "<script>window.location.href = 'store.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "'</script>";
		
	}
	
	function storeEquipment($store_id, $journey_id, $character_id, $player_id) {
	
		// Lists the items for sale in the store
	
		addToDebugLog("manageEquipment(), Function Entry - supplied parameters: Player ID: " . $player_id . "; Journey ID: " . $journey_id . "; Character ID: " . $charcter_id . "; Store ID: " . $store_id . ", INFO");
	
		// Get store name
		$store_name = getStoreName($store_id);
	
		echo "<table cellpadding=3 cellspacing=0 border=1>";
		echo "<tr><td colspan=5 align=center><h2>For Sale</h2></tr>";
		echo "<tr bgcolor=#bbb><td>Item<td align=center>Weight<td align=center>Cost<td align=center>Buy</tr>";
	
		getStoreContentsBySlot($store_id, $journey_id, $character_id, $player_id, 'chest');
		getStoreContentsBySlot($store_id, $journey_id, $character_id, $player_id, 'head');
		getStoreContentsBySlot($store_id, $journey_id, $character_id, $player_id, 'legs');
		getStoreContentsBySlot($store_id, $journey_id, $character_id, $player_id, 'shield');
		getStoreContentsBySlot($store_id, $journey_id, $character_id, $player_id, 'weapon');
		getStoreContentPotions($store_id, $journey_id, $character_id, $player_id);
		getStoreContentPets($store_id, $journey_id, $character_id, $player_id);
	
		echo "</table>";
	
	}

	function getStoreDetails($store_id, $attribute) {
		
		// Creates and returns a store name
		
		addToDebugLog("getStoreDetails(), Function Entry - Store ID: " . $store_id . ", INFO");
		
		$sql = "SELECT " . $attribute . " FROM hackcess.store WHERE store_id = " . $store_id . ";";
		$result = search($sql);
		
		return $result[0][0];	
		
	}
	
	?>