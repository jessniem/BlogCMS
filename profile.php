<?php
session_start();
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";


?>
<main class="admin-main">
  <section>

    <h1>Din Profil</h1> <?php
    if (isset($_GET["pw"])) {
        if ($_GET["pw"] == "ok") {
            echo "Your password has been updated!";
        } elseif ($_GET["pw"] == "error") {
          echo "Somthing went wrong, your password has not been changed!";
        }
    } ?>


    <h2>Dina uppgifter</h2>

    <h2>Byt lösenord</h2>
    <form class="" action="changepw.php" method="post">
      <input type="password" name="currentPW" value="" placeholder="Current password">
      <input type="password" name="newPW" value="" placeholder="New password">
      <input type="password" name="newPW2" value="" placeholder="New password again">
      <input type="submit" name="submit" value="Change password">
    </form>
  </section>
</main>

<?php
 ?>
