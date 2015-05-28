<html>

<head>
	<title>Random Hackcess | Equipment</title>
	<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
</head>

<body>

<h1>Equipment</h1>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	
	$player_id = $_GET['player_id'];
	$character_id = $_GET['character_id'];
	$journey_id = $_GET['journey_id'];

	if ($_GET['action'] == "equip") {
		
		// Equip the item		
		equip($_GET['slot'], $_GET['item_id'], $character_id);
		
		// Redirect to this page
		echo "<script>window.location.href = 'equipment.php?journey_id=" . $journey_id . "&character_id=" . $character_id . "&player_id=" . $player_id . "'</script>";

	} else {
	
		// Display List of character equipment
		manageEquipment($player_id, $character_id, $journey_id);
		
		outputDebugLog();
	}
	
?>

</body>

</html>