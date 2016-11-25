<?php
require_once "db_connection.php";

//$postid = "";
if (isset($_GET["postId"])) {
  $postid = $_GET["postId"];
}

$stmt = $conn->stmt_init();

$comment = mysqli_real_escape_string($conn, $_POST["comment"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$name = mysqli_real_escape_string($conn, $_POST["name"]);

$query = "INSERT INTO comments VALUES (NULL, '$email', NULL, '$name', '$postid', '$comment')";
if ($stmt->prepare($query)) {
    $stmt->execute();
} else {
  echo mysqli_error();
}

header ("Location: comments.php?post=$postid");

  ?>
