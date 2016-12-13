<?php
session_start();

$_SESSION["logged_in"] = false;
unset($_SESSION["username"]);
unset($_SESSION["userId"]);

session_destroy();

header("Location: index.php?logout=true");
?>