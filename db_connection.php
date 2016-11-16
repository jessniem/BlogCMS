<?php

$conn = new mysqli ("localhost", "root", "root", "echo"); 

	if ($conn->connect_errno) { 
	echo "connection failed."; die(); 

?>