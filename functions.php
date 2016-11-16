<?php

/**
* The function create utf8_encoded connection to db.
*
* @return string Returns error message if connection not ok.
**/
function connectDB() {
  global $conn;
  $conn = new mysqli("localhost", "root", "root", "echo");
  $conn->set_charset("utf8");

  if ($conn->connect_errno) {
      return "<p>Failed to connect to database";
      die();
  }
}




 ?>
