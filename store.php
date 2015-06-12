<html>

<head>
	<title>Random Hackcess | Store</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	include 'functions/store_functions.php';
	include 'functions/effects_functions.php';

	addToDebugLog("store.php, page, INFO");
	
	$character_id = $_GET['character_id'];
	$enemy_id = $_GET['enemy_id'];
	$grid_id = $_GET['grid_id'];
	$player_id = $_GET['player_id'];
	$journey_id = $_GET['journey_id'];
	$action = $_GET['action'];
	$store_id = $_GET['store_id'];
	$item_id = $_GET['item_id'];
	
	if ($action == "sell") {
		// Sell item
		addToDebugLog("store.php, sell, INFO");
		sellItem($store_id, $journey_id, $character_id, $player_id, $item_id);
		
	} elseif ($action == "buy") {
		// Buy item
		addToDebugLog("store.php, buy, INFO");
		buyItem($store_id, $journey_id, $character_id, $player_id, $item_id);
		
	} elseif ($action == "equip") {	
		// Equip the item
		addToDebugLog("store.php, equip, INFO");
		equip($_GET['slot'], $_GET['item_id'], $character_id);
	
		// Redirect back to page
		echo "<script>window.location.href = 'store.php?player_id=" . $player_id . "&character_id=" . $character_id . "&journey_id=" . $journey_id . "&store_id=" . $store_id . "'</script>";
		
	
	} else { // Display Store and Character items
		
		addToDebugLog("store.php, main, INFO");
		
		// Get store name / grid id
		$store_name = getStoreName($store_id);
		$grid_id = getStoreDetails($store_id, 'grid_id');
		
		// Create journal entry
		$details = "Visited " . $store_name;
		$dml = "INSERT INTO hackcess.journal (character_id, journey_id, grid_id, journal_details) VALUES (" . $character_id . ", " . $journey_id . ", " . $grid_id . ", '" . $details . "');";
		$result_m = insert($dml);
		if ($result_m == TRUE) {
			addToDebugLog("store.php, Journal entry added, INFO");
		} else {
			addToDebugLog("store.php, Journal entry not added, ERROR");
		}
		
		echo "<h1 align=center>Welcome to " . $store_name . "</h1>";
		
		echo "<table cellpadding=5 cellspacing=1 border=0 align=center><tr><td valign=top>";	
		characterEquipment($character_id, $player_id, $journey_id, $store_id);
		echo "<td valign=top>";
		storeEquipment($store_id, $journey_id, $character_id, $player_id);
		echo "</tr>";
		
		// Get weight of character equipment
		$equipment_total_weight = characterEquipmentWeight($character_id);
		addToDebugLog("store.php, Equipment Weight: " . $equipment_total_weight . ", INFO");
		
		// Get character strength
		$character_strength = getCharacterDetailsInfo($character_id, 'strength');
		addToDebugLog("store.php, Character Strength: " . $character_strength . ", INFO");
		
		echo "<tr><td align=center colspan=2>";
		if ($equipment_total_weight < $character_strength) {
			echo "<a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'>Back to Adventure</a>";
		} else {
			echo "You are carrying too much weight. You must sell items to continue.";
		}
		echo "</tr></table>";
		
		outputDebugLog();
		
	}

	
?>

</body>

</html>