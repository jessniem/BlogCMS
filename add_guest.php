<?php
session_start();
require_once "db_connection.php";
require_once "functions.php";

$email = sanitizeMySql($conn, $_POST['email']);
$pw = sanitizeMySql($conn, $_POST['password']);
$fn = sanitizeMySql($conn, $_POST['fname']);
$ln = sanitizeMySql($conn, $_POST['lname']);

// Check if email already registered
if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE email ='$email'")) != NULL  ) {
    $_SESSION["email_registered"] = true;
    header ("Location: profile.php?user=already_registered");
    exit;
}

if ($_SESSION["access"] == 2) {

  $stmt = $conn->stmt_init();
  $query = "INSERT INTO users VALUES (NULL, 3, '$email', '$pw', '$fn', '$ln',  NULL, NULL)";

  if ($stmt->prepare($query)) {
   $stmt->execute();
   header("Location: profile.php?user=success");
   $_SESSION["user_email"] = $email;
   $_SESSION["user_pw"] = $pw;
  } else {
    echo mysqli_error();
  }

}
 ?>
