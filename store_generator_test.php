<html>

<head>
	<title>Random Hackcess | Store</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	//include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	include 'functions/store_functions.php';

	generateStore(1, 1, 1);
	
	outputDebugLog();
	
?>

</body>

</html>