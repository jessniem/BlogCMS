<?php
session_start();

require_once "db_connection.php";
require_once "functions.php";
//print_r($_SESSION);

$stmt = $conn->stmt_init();

$user = $_SESSION["userId"];
// passwords sent in form:
$pw = $_POST["currentPW"];
$newPW = $_POST["newPW"];
$newPW2 = $_POST["newPW2"];

// get the pw from db
$query  = "SELECT password FROM users WHERE id = $user";
if ($stmt->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($dbPW);
    $stmt->fetch();
}

// check that the given old pw matches the one in db
if (password_verify($pw, $dbPW)) {
    // check that the new passwords matches each other
    if ($newPW == $newPW2) {
        // hash the pw
        $newPW = password_hash($newPW, CRYPT_BLOWFISH);
        // update password in db
        $query = "UPDATE users SET password = '{$newPW}' WHERE id = '{$user}'";
        if ($stmt->prepare($query)) {
            $stmt->execute();
            header("Location: profile.php?pw=ok");
        }
    }

} else {
  header("Location: profile.php?pw=error");
}





 ?>
