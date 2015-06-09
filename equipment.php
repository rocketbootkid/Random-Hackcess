<html>

<head>
	<title>Random Hackcess | Equipment</title>
</head>

<body>

<h1 align=center>Equipment</h1>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	
	addToDebugLog("equipment.php, page, INFO");
	
	$player_id = $_GET['player_id'];
	$character_id = $_GET['character_id'];
	$journey_id = $_GET['journey_id'];

	if ($_GET['action'] == "equip") {
		
		// Equip the item		
		equip($_GET['slot'], $_GET['item_id'], $character_id);
		
		// Redirect to this page
		echo "<script>window.location.href = 'equipment.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";

	} elseif ($_GET['action'] == "drop") {
		
		// Drop the item		
		drop($_GET['item_id']);
		
		// Redirect to this page
		echo "<script>window.location.href = 'equipment.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";
		
	} else {

		if ($character_id > 0 && $player_id > 0 && $journey_id > 0) {
			// Display List of character equipment
			$value = manageEquipment($player_id, $character_id, $journey_id);
			
			if ($value == "ok") {
				echo "<p><div style='text-align: center;'><a href='adventure.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'>Back to Adventure</a></div>";
			} else {
				echo "<p><div style='text-align: center;'>You can't move. You need to drop something.</div>";
			}
			
			outputDebugLog();
		} else {
			echo "You fumble with the straps on your pack, but cannot open it. Guess the contents will have to remain a mystery.";
		}
	}
	
?>

</body>

</html>