<?php
require_once "db_connection.php";
require_once "functions.php";
include_once "./includes/head.php";
include_once "./includes/top.php";
?>
<main>
  <?php
  $stmt = $conn->stmt_init();

  if (isset($_GET["post"]) ) {
      // the id of the selected post:
      $thisPostId = sanitizeMySql($conn, $_GET["post"]);
      //echo "$thisPostId";

      // fetch the selected post from db
      $query = "SELECT posts.id, posts.title, posts.categoryid, posts.userid, posts.content, posts.image, DATE(posts.createDate), posts.isPub, users.firstName, users.lastName, categories.category FROM posts
        JOIN users ON (users.id = posts.userid)
        JOIN categories ON (categories.id = posts.categoryid) WHERE posts.id = $thisPostId";
  }
  if ($stmt->prepare($query)) {
      $stmt->execute();
      $stmt->bind_result($id, $title, $categoryid, $userid, $content, $image, $createDate, $isPub, $fname, $lname, $tag);

      //save blog posts in blogPosts array
      while (mysqli_stmt_fetch($stmt)) {
        $blogPosts[] = array ("id" => $id, "title" => $title, "categoryid" => $categoryid, "userid" => $userid, "content" => $content, "image" => $image, "createDate" => $createDate, "isPub" => $isPub, "fname" => $fname, "lname" => $lname, "tag" => $tag);
      }

      // echo out the blogPosts array
      foreach ($blogPosts as $post) {
          $index = false;
          ?>
          <div class="post-container">
            <?php
            printPost($index, 0);
            ?>
          </div>
          <?php
      }
  }

  // fetch comments on this post from db
  $query = "SELECT * FROM comments WHERE postid = $thisPostId ORDER BY id DESC";

  if ($stmt->prepare($query)) {
      $stmt->execute();
      $stmt->bind_result($id, $email, $date, $name, $postid, $commentText);
    }


  $comments = [];
  while (mysqli_stmt_fetch($stmt)) {
    $comments[] = array ("id" => $id, "email" => $email, "date" => $date, "name" => $name, "postid" => $postid, "commentText" => $commentText);
  }

  // Form for new comments
  ?>
  <section class="comment-form">
    <form action="comments_check.php?postId=<?php echo $thisPostId; ?>" method="post">
      <h2>Your comment:</h2>
      <textarea name="comment" value="" placeholder="Comment..."></textarea>
      <h3>Who are you?</h3>
      <input type="input" name="name" value="" placeholder="Your name">
      <input type="email" name="email" value="" placeholder="Your email">
      <button type="submit" name="submit">Post comment</button>
    </form> <?php
    // lägg till "Comments:" om det finns kommentarer
    if ($commentText != NULL) { ?>
      <h2>Comments:</h2> <?php
    } ?>

  </section>

  <?php


  //print out the comments
  foreach ($comments as $var) {
    $time = strtotime($var["date"]);?>
    <div class="comment">
      <div class="comment-fa">
        <i class="fa fa-user-circle" aria-hidden="true"></i>
      </div>
      <div class="comment-content">
        <p><?php echo $var["commentText"]; ?></p>
        <span>By: <?php echo $var["name"] . " - " . $var["email"] . " | " . timeElapsedString($time); ?></span>
      </div>
    </div> <?php
  }
  ?>
</main>
<?php
include_once "./includes/footer.php";
?>
