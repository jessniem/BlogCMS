<?php

require_once "db_connection.php";

$stmt = $conn->stmt_init();

$query = "SELECT posts.*, users.firstName, users.lastName FROM posts LEFT JOIN users ON posts.userid = users.id ORDER BY posts.id DESC";

if ($stmt->prepare($query)) {
    $stmt->execute();

    $stmt->bind_result($id, $title, $categoryid, $userid, $content, $image, $createDate, $isPub, $fname, $lname);
}

//save blog posts in blogPosts array
while (mysqli_stmt_fetch($stmt)) {
$blogPosts[] = array ("id" => $id, "title" => $title, "categoryid" => $categoryid, "userid" => $userid, "content" => $content, "image" => $image, "createDate" => $createDate, "isPub" => $isPub, "fname" => $fname, "lname" => $lname);
}

// TODO: Fixa sorteringen så att den inte försvinner när klickar på något

// echo out the blogPosts array
foreach ($blogPosts as $post) {
  $pubmonth = substr($post["createDate"], -4, 2);
  if (!empty($_GET["month"]) && ($pubmonth == $_GET["month"])) { ?>
    <div class="load-post">
      <article class="post">
        <div class="post-img">
          <img src="<?php echo $post['image']; ?>" alt="">
        </div>
        <div class="post-text">
          <div> <?php
          // hämtar kategorinamnet ur db
          $get_cat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT category FROM categories WHERE id = '{$post["categoryid"]}'")); ?>
          <p class="tags"><?php echo $get_cat["category"]; ?></p>
          <h1><?php echo $post["title"]; ?></h1>
          <div class="blog-content"><?php echo $post["content"]; ?></div>
        </div>
        <div>
          <p class="post-info"><?php echo $post["fname"]. " " .$post["lname"]. ", " .$post["createDate"]; ?></p>
          <p class="comments">Comments (2)</p>
        </div>
      </div> <!-- post-text -->
      </article>
    </div> <!-- load-post --> <?php
    // TODO: Skapa funktion istället för kodupprepning, div load-post är samma här ovan och nedanför
  } elseif (!isset($_GET["month"])) { ?>
    <div class="load-post">
      <article class="post">
        <div class="post-img">
          <img src="<?php echo $post['image']; ?>" alt="">
        </div>
        <div class="post-text">
          <div> <?php
          // hämtar kategorinamnet ur db
          $get_cat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT category FROM categories WHERE id = '{$post["categoryid"]}'")); ?>
          <p class="tags"><?php echo $get_cat["category"]; ?></p>
          <h1><?php echo $post["title"]; ?></h1>
          <div class="blog-content"><?php echo $post["content"]; ?></div>
        </div>
        <div>
          <p class="post-info"><?php echo $post["fname"]. " " .$post["lname"]. ", " .$post["createDate"]; ?></p>
          <p class="comments">Comments (2)</p>
        </div>
      </div> <!-- post-text -->
      </article>
    </div> <!-- load-post --> <?php
  }
}  ?>

<div class="load">
  <a href="#" id="loadMore"><i class="fa fa-angle-double-down" aria-hidden="true"></i></i></a>
</div> <!-- .load-btn -->

<!-- <p class="totop">
    <a href="#top">Back to top</a>
</p> -->
<?php

// close db connection
$stmt->close();
$conn->close();

?>
