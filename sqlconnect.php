<?php

	$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');
	mysqli_query($link, "SET NAMES 'utf8'");
	mysqli_query($link, "SET CHARACTER SET 'utf8'");
	mysqli_query($link, "SET SESSION collation_connection = 'utf8_general_ci'");
	
?>