<?php

	function characterEquipment($character_id, $player_id, $journey_id, $store_id) {
		
		// This function lists the equipment held by each player
	
		addToDebugLog("manageEquipment(): Function Entry - supplied parameters: Player ID: " . $player_id . ", Journey ID: " . $journey_id . ", Character ID: " . $charcter_id . ", Store ID: " . $store_id);
		
		// Get Character Name
		$character_name = getCharacterDetails($character_id, 'character_name');
	
		echo "<table cellpadding=3 cellspacing=0 border=1>";
		echo "<tr><td colspan=5 align=center><h2>" . $character_name . "</h2></tr>";
		echo "<tr bgcolor=#ddd><td>Item<td>Slot<td align=center>Weight<td align=center>Value<td align=center>Actions</tr>";
	
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
				echo "<tr><td colspan=5 bgcolor=#eee align=center>" . ucfirst($result[$e][5]) . "</tr>";
				$current_slot = $result[$e][5];
			}
	
			$bonus = $result[$e][2] + $result[$e][3];
			echo "<tr><td>+" . $bonus . " " . $result[$e][1]; // Bonus + Item
			echo "<td>" . ucfirst($result[$e][5]); // Slot
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
	
		echo "<tr><td colspan=2 align=right>Total Weight<td align=center>" . $weight_total . "<td colspan=2></tr>";
	
		// Get character strength
		$character_strength = getCharacterDetailsInfo($character_id, 'strength');
		echo "<tr><td colspan=2 align=right>Character Strength<td align=center>" . $character_strength . "<td colspan=2></tr>";
		
		// Character Gold
		$character_gold = getCharacterDetailsInfo($character_id,'gold');
		echo "<tr><td colspan=2 align=right>Character Gold<td align=center>" . $character_gold . "<td colspan=2></tr>";
		
		echo "</table>";
	 
	}

	function storeEquipment($store_id, $journey_id, $character_id, $player_id) {
		
		// Creates and returns a store name
		
		// Get store name
		$store_name = getStoreName($store_id);
		
		// Get Store items
		addToDebugLog("storeEquipment(): Function Entry - Parameters: Store ID: " . $store_id);
		$sql = "SELECT * FROM hackcess.store_contents WHERE store_id  = " . $store_id . " ORDER BY item_slot ASC, item_ac_boost+item_attack_boost DESC;";
		addToDebugLog("storeEquipment(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);	
		
		echo "<table cellpadding=3 cellspacing=0 border=1>";
		echo "<tr><td colspan=5 align=center><h2>" . $store_name . "</h2></tr>";
		echo "<tr bgcolor=#ddd><td>Item<td>Slot<td align=center>Weight<td align=center>Cost<td align=center>Buy</tr>";		
		
		$current_slot = "";
		
		for ($i = 0; $i < $rows; $i++) {
			if ($result[$i][6] != $current_slot) {
				echo "<tr><td colspan=5 bgcolor=#eee align=center>" . ucfirst($result[$i][6]) . "</tr>"; 
				$current_slot = $result[$i][6];
			}
			$boost = $result[$i][3] + $result[$i][4];
			echo "<tr><td>+" . $boost . " " . $result[$i][2]; // Item and Boost
			echo "<td>" . ucfirst($result[$i][6]); // Slot
			echo "<td align=center>" . $result[$i][5]; // Weight
			echo "<td align=center>" . $result[$i][7]; // Value
			echo "<td align=center><a href='store.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "&store_id=" . $store_id . "&action=buy&item_id=" . $result[$i][0] . "'>Buy</a>"; // Action
			
			echo "</tr>";
			
		}
		echo "</table>";
		
	}
	
	function generateStore($grid_id, $journey_id, $character_id) {
		
		// Creates and returns a store name
		
		addToDebugLog("generateStore(): Function Entry - Parameters: Grid ID: " . $grid_id . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id);
		
		// Store will have 0-2 of each item
		// Items will be range from lowest modifier of existing items, to 5 levels above the highest modifier
		// Cost of an item will be 50 x the modifier value
		
		// Generate Store Name
		$store_name = generateStoreName();
		
		// Create store
		$dml = "INSERT INTO hackcess.store (store_name, grid_id, journey_id, character_id) VALUES ('" . $store_name . "', " . $grid_id . ", " . $journey_id . ", " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("generateStore(): New journal entry added");
		} else {
			addToDebugLog("generateStore(): ERROR: New journal entry not added");
		}
		
		// Get store ID
		$sql = "SELECT store_id FROM hackcess.store ORDER BY store_id DESC LIMIT 1;";
		addToDebugLog("generateStore(): Constructed query: " . $sql);
		$result = search($sql);
		$store_id = $result[0][0];
		
		// Populate with items
		
		// Get character lowest rank equipped item
		$sql = "SELECT * FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		addToDebugLog("generateStore(): Constructed query: " . $sql);
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
		addToDebugLog("generateStore(): Min AC: " . $min_ac . ", Min ATK: " . $min_atk);

		// Head Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('head', $store_id, $min_ac);
		}

		// Chest Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('chest', $store_id, $min_ac);
		}
		
		// Legs Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('legs', $store_id, $min_ac);
		}
		
		// Shield Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('shield', $store_id, $min_ac);
		}
		
		// Sword Items
		$num_items = rand(1, 3);
		for ($item = 0; $item < $num_items; $item++) {
			createStoreItem('weapon', $store_id, $min_atk);
		}
		
	}
	
	function sellItem($store_id, $journey_id, $character_id, $player_id, $item_id) {

		// Sells an item from the characters equipment
		
		addToDebugLog("sellItem(): Function Entry - Store ID: " . $store_id . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id . ", Player ID: " . $player_id . ", Item ID: " . $item_id);
		
		// Get item details from the character
		$sql = "SELECT * FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
		addToDebugLog("sellItem(): Constructed query: " . $sql);
		$result = search($sql);
		$name = $result[0][1];
		$ac_boost = $result[0][2];
		$attack_boost = $result[0][3];
		$weight = $result[0][4];
		$slot = $result[0][5];
		$cost = 50 * ($ac_boost + $attack_boost);
		
		// Add value to player gold
		$dml = "UPDATE hackcess.character_details SET gold = gold + " . $cost . " WHERE character_id = " . $character_id . ";";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("sellItem(): Character record updated");
		} else {
			addToDebugLog("sellItem(): Character record not updated");
		}		
		
		// Add item to store
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $name . "', " . $ac_boost . ", " . $attack_boost . ", " . $weight . ", '" . $slot . "', " . $cost . ");";
		$result = insert($dml);		
		if ($result == TRUE) {
			addToDebugLog("sellItem(): Item added to store");
				
			// Remove item from store
			$dml = "DELETE FROM hackcess.character_equipment WHERE equipment_id = " . $item_id . ";";
			$result = delete($dml);
			if ($result == TRUE) {
				addToDebugLog("sellItem(): Item deleted from character equipment");
			} else {
				addToDebugLog("sellItem(): ERROR: Item not deleted from character equipment");
			}
				
		} else {
			addToDebugLog("sellItem(): ERROR: Item not added to store");
		}
		
		// Redirect back to store page
		echo "<script>window.location.href = 'store.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "'</script>";
		
		//outputDebugLog();
		
		
	}
	
	function buyItem($store_id, $journey_id, $character_id, $player_id, $item_id) {

		// Buys an item from the store
		
		addToDebugLog("buyItem(): Function Entry - Store ID: " . $store_id . ", Journey ID: " . $journey_id . ", Character ID: " . $character_id . ", Player ID: " . $player_id . ", Item ID: " . $item_id);
		
		// Get item details from the store
		$sql = "SELECT * FROM hackcess.store_contents WHERE contents_id = " . $item_id . ";";
		addToDebugLog("buyItem(): Constructed query: " . $sql);
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
			addToDebugLog("buyItem(): Character record updated");
		} else {
			addToDebugLog("buyItem(): Character record not updated");
		}
		
		// Add item to player equipment
		$dml = "INSERT INTO hackcess.character_equipment (name, ac_boost, attack_boost, weight, slot, character_id) VALUES ('" . $name . "', " . $ac_boost . ", " . $atk_boost . ", " . $weight . ", '" . $slot . "', " . $character_id . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("buyItem(): Item added to player inventory");
			
			// Remove item from store
			$dml = "DELETE FROM hackcess.store_contents WHERE contents_id = " . $item_id . ";";
			$result = delete($dml);
			if ($result == TRUE) {
				addToDebugLog("buyItem(): Item deleted from store");
			} else {
				addToDebugLog("buyItem(): ERROR: Item not deleted from store");
			}
			
		} else {
			addToDebugLog("buyItem(): ERROR: Item not added to player inventory");
		}
		
		// Redirect back to store page
		echo "<script>window.location.href = 'store.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "'</script>";
		
		//outputDebugLog();
		
	}
	
	function generateStoreName() {

		// Creates and returns a store name
	
		addToDebugLog("generateEnemyName(): Function Entry - no parameters");
	
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
		addToDebugLog("generateStoreName(): Name: " . $name);
	
		$final_name = $name . "&apos;s Store";
		addToDebugLog("generateStoreName(): Final name: " . $final_name);
	
		return $final_name;
		
	}
	
	function createStoreItem($slot, $store_id, $min_level) {
	
		// This function creates a store item
	
		addToDebugLog("createRandomItem(): Function Entry - supplied parameters: Store ID: " . $store_id . ", Slot: " . $slot . ", Min Level: " . $min_level);
	
		srand(make_seed());
		$item_choice = rand(0, 4);
	
		switch ($item_choice) {
			case 0: // Head
				$slot = "head";
				$name = "Helm";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(): AC: " . $ac);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 1: // Chest
				$slot = "chest";
				$name = "Chestplate";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(): AC: " . $ac);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 2: // Legs
				$slot = "legs";
				$name = "Trousers";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(): AC: " . $ac);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50 * intval($ac);
				break;
			case 3: // Shield
				$slot = "shield";
				$name = "Shield";
				srand(make_seed());
				$ac = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(): AC: " . $ac);
				$weight = round($ac/2);
				$atk = 0;
				$cost = 50*$ac;
				break;
			case 4: // Weapon
				$slot = "weapon";
				$name = "Sword";
				srand(make_seed());
				$atk = rand($min_level, $min_level + 5);
				addToDebugLog("generateStoreName(): ATK: " . $atk);
				$weight = round($atk/2);
				$ac = 0;
				$cost = 50*$atk;
				break;
		}
	
		// Add weapon to character
		$dml = "INSERT INTO hackcess.store_contents (store_id, item_name, item_ac_boost, item_attack_boost, item_weight, item_slot, item_cost) VALUES (" . $store_id . ", '" . $name . "', " . $ac . ", " . $atk . ", " . $weight . ", '" . $slot  . "', " . $cost . ");";
		$result = insert($dml);
		if ($result == TRUE) {
			addToDebugLog("createRandomItem(): New item added");
		} else {
			addToDebugLog("createRandomItem(): ERROR: New item not added");
		}
	
		return $details;
	
	}
	
	function isThereAStoreHere($grid_id) {
		
		// This function returns the store id (if present) for the provided grid id
		
		addToDebugLog("isThereAStoreHere(): Function Entry - supplied parameters: Grid ID: " . $grid_id);		
		
		$sql = "SELECT store_id FROM hackcess.store where grid_id = " . $grid_id . ";";
		addToDebugLog("isThereAStoreHere(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result[0][0];		
		
	}
	
	function getStoreName($store_id) {
		
		// This function returns the store name for the provided store id
		
		addToDebugLog("getStoreName(): Function Entry - supplied parameters: Store ID: " . $store_id);
		
		$sql = "SELECT store_name FROM hackcess.store where store_id = " . $store_id . ";";
		addToDebugLog("getStoreName(): Constructed query: " . $sql);
		$result = search($sql);
		
		return $result[0][0];		
		
	}
	
	function characterEquipmentWeight($character_id) {

		// This function returns the weight of the equipment held by each player
	
		addToDebugLog("characterEquipmentWeight(): Function Entry - supplied parameters: Character ID: " . $charcter_id);
	
		$sql = "SELECT weight FROM hackcess.character_equipment WHERE character_id = " . $character_id . ";";
		addToDebugLog("characterEquipmentWeight(): Constructed query: " . $sql);
		$result = search($sql);
		$rows = count($result);
		$weight = 0;
		for ($r = 0; $r < $rows; $r++) {
			$weight = $weight + $result[$r][0];
		}
		addToDebugLog("characterEquipmentWeight(): Total Weight: " . $weight);
		
		return weight;
		
	}
	
?>