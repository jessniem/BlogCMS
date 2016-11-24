<?php
include_once "./includes/head.php";
require_once "db_connection.php";
require_once "functions.php";
$stmt = $conn->stmt_init();

if (isset($_GET["post"]) ) {
    // the id of the selected post:
    $thisPostId = $_GET["post"];

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
          printPost();
      }
}

// fetch comments on this post from db
$query = "SELECT * FROM comments WHERE postid = $thisPostId ORDER BY id DESC";

if ($stmt->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($id, $email, $date, $name, $postid, $commentText);
  }

    while (mysqli_stmt_fetch($stmt)) {
      $comments[] = array ("id" => $id, "email" => $email, "date" => $date, "name" => $name, "postid" => $postid, "commentText" => $commentText);
    }

    foreach ($comments as $var) {

      $time = strtotime($var["date"]);
       ?>

      <div class="comment">
        <p><?php echo $var["commentText"]; ?></p>
        <span>By: <?php echo $var["name"] . " - " . $var["email"] . " | " . timeAgo($time) . " ago"; ?></span>

      </div> <?php





    }





?>
