<?php

$conn = new mysqli ("localhost", "root", "root", "echo");
$conn->set_charset("utf8");

	if ($conn->connect_errno) {
			echo "Connection failed.";
			die(); 
	}

?>
