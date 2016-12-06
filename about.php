<?php
session_start();
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<main>
	<section>
	<h1>About me</h1>
    <?php
    $id = $_SESSION["userId"];
    $stmt = $conn->stmt_init();
    $query = "SELECT * FROM users WHERE id = 2";

    if($stmt->prepare($query)) {
      $stmt->execute();

      $stmt->bind_result($id, $accesslevel, $email, $password, $firstname, $lastname, $profilepic, $description);
      $stmt->fetch();
    }
    ?>
    <img src="<?php echo $profilepic; ?>" alt="<?php echo $profilepic; ?>">
    <?php
    echo "$description";
    echo "$email";
    ?>
	</section>
</main>
