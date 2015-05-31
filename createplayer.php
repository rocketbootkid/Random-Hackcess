<?php
	include 'functions/debug_functions.php';
	include 'functions/mysql_functions.php';
	include 'functions/grid_functions.php';
	include 'functions/player_functions.php';
  
	createPlayer($_GET['name']);
	#echo "<script>window.close();</script>";
	
	outputDebugLog();
  
?>
