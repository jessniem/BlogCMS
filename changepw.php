<?php
session_start();
require_once "db_connection.php";
require_once "functions.php";
$stmt = $conn->stmt_init();
$user = $_SESSION["userId"];

// PASSWORDS SENT IN FORM
$pw = $_POST["currentPW"];
$newPW = $_POST["newPW"];
$newPW2 = $_POST["newPW2"];

// GET PASSWORD FROM DB
$query  = "SELECT password FROM users WHERE id = $user";
if ($stmt->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($dbPW);
    $stmt->fetch();
}

// CHECK THAH GIVEN OLD PW MATCHES THE ONE IN DB
if (password_verify($pw, $dbPW)) {
    if ($newPW == $newPW2) {
        $newPW = password_hash($newPW, CRYPT_BLOWFISH);
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
