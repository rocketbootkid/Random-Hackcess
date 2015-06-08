<html>

<head>
	<title>Random Hackcess | Select Player</title>
</head>

<body>

<?php

	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';

	playerSelect();
	
	echo "<p><div style='text-align: center;'><a href='docs/doc_generator.php' target='_blank'>Generate Docs</a> | <a href='docs/api.html' target='_blank'>View Docs</a></div>";

	outputDebugLog();
	
?>

</body>

</html>