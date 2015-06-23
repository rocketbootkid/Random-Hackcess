<html>

<head>
	<title>Random Hackcess | Function Test</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	include 'functions/store_functions.php';
	include 'functions/effects_functions.php';

	$array = allTilesArray();
	
	print_r($array);
	
	outputDebugLog();
	
?>

</body>

</html>