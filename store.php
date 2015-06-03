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

	$character_id = $_GET['character_id'];
	$enemy_id = $_GET['enemy_id'];
	$grid_id = $_GET['grid_id'];
	$player_id = $_GET['player_id'];
	$journey_id = $_GET['journey_id'];
	$action = $_GET['action'];
	
	if ($action == "generate") {
		// Generate new store
	
	
	} elseif ($action == "sell") {
		// Sell item
		
		
	} elseif ($action == "buy") {
		// Buy item
		
		
	} else { // Display Store and Character items
		
		echo "<table cellpadding=1 cellspacing=1 border=0><tr><td>";	
		characterEquipment();
		echo "<td>";
		storeEquipment();
		echo "</tr></table>";
		
	}
	
	outputDebugLog();
	
?>

</body>

</html>