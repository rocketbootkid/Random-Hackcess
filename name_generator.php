<html>

<head>
	<title>Random Hackcess - Name Generation Test</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
	include 'functions/pet_functions.php';
	include 'functions/store_functions.php';

	echo "<h2>Name Generators</h2>";
	
	echo "Character Name: " . generateCharacterName();
	
	echo "<P>Enemy Name: " . generateEnemyName();
	
	echo "<P>Pet Name: " . generatePetName();
	
	echo "<P>Store Name: " . generateStoreName();
	
?>

</body>

</html>