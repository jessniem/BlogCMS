<?php
require_once "db_connection.php";
require_once "functions.php";
$stmt = $conn->stmt_init();

if (isset($_GET["post"]) ) {
    $thisPostId = $_GET["post"];

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
      // TODO: Fixa sorteringen så att den inte försvinner när klickar på något

      // echo out the blogPosts array
      foreach ($blogPosts as $post) {
          printPost();
      }
}

$query = "SELECT * FROM comments WHERE postid = $thisPostId";

if ($stmt->prepare($query)) {
    $stmt->execute();

    $stmt->bind_result($id, $email, $date, $name, $postid, $commentText);

    while (mysqli_stmt_fetch($stmt)) {
      $comments[] = array ("id" => $id, "email" => $email, "date" => $date, "name" => $name, "postid" => $postid, "commentText" => $commentText);
    }

    foreach ($comments as $post) {
        echo $post["commentText"];
    }

}




 ?>
