<?php
session_start();
require_once "db_connection.php";
require_once "functions.php";

// ADD NEW GUEST USER

if (!empty($_POST)) {
  $email = sanitizeMySql($conn, $_POST['email']);
  $password = $_POST['password'];
  $pw = password_hash($password, CRYPT_BLOWFISH);
  $fn = sanitizeMySql($conn, $_POST['fname']);
  $ln = sanitizeMySql($conn, $_POST['lname']);

  // check the format of the email
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // Check if email already registered
    if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE email ='$email'")) != NULL  ) {
        $_SESSION["email_registered"] = true;
        header ("Location: profile.php?user=already_registered");
        exit;
    }

    if ($_SESSION["access"] <= 2) {

      $stmt = $conn->stmt_init();
      $query = "INSERT INTO users VALUES (NULL, 3, '$email', '$pw', '$fn', '$ln',  NULL, NULL)";

      if ($stmt->prepare($query)) {
       $stmt->execute();
       header("Location: profile.php?user=success");
       $_SESSION["user_email"] = $email;
       $_SESSION["user_pw"] = $password;
      } else {
        echo mysqli_error();
      }
    }

  } else {
    header("Location: profile.php?user=invalid_email");
  }
}


// DELETE GUEST USER

if (isset($_GET["delete"])) {
  $id = sanitizeMySql($conn, $_GET["delete"]);
  $stmt = $conn->stmt_init();
  $query = "DELETE FROM users where id = $id";
  if ($stmt->prepare($query)) {
    $stmt->execute();
    header("Location: profile.php?user=deleted");
  } else {
    echo mysqli_error();
  }
}



 ?>
