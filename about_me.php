<?php
require_once "db_connection.php";
include_once "./includes/head.php";
require_once "functions.php";
include_once "./includes/top.php";
?>

<main class="about-main">
    <section>
    	<h1>About me</h1>
        <?php
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
        echo "<p>" . $description . "</p>";
        ?>
        <div class="email">
            <h2>Contact and inquiries</h2>
            <a href="mailto: <?php echo "$email"; ?>">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <?php echo $email; ?>
            </a>
        </div> <!-- /email -->
	</section>
</main> <!-- /about-main -->

<?php 
include_once "./includes/footer.php"; 
?>
