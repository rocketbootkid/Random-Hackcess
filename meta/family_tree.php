<html>

<head>
	<title>Random Hackcess | Family Tree</title>
</head>

<body>

<?php

	include '../functions/debug_functions.php';
	include '../functions/mysql_functions.php';
	include '../functions/grid_functions.php';
	include '../functions/player_functions.php';
	include '../functions/tree_functions.php';
	
	addToDebugLog("family_tree.php, page, INFO");
	
	echo "<h2 align=center>Family Tree</h2>";
	
	getZerothGen();
	
	outputDebugLog();
	
?>

</body>

</html>