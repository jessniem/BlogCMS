<?php
    include "./includes/head.php";
    if (isset($_SESSION["logged_in"])) {
    include "./includes/top_admin.php";
    include "./includes/top.php";

  } else {
    include_once "./includes/top.php";
  }
?>
<main>
    <?php
    include "post_feed.php";
    ?>
</main>


<?php
    include "./includes/footer.php";
?>
