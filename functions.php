<?php

/**
* The function print out the blog posts
*
**/
function printPost() {
  global $conn;
  global $post; ?>
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


/**
* The function prevent escape characters to be injected in the strings presented to MySQL.
*
* @param int $isPub Send in 0 to print out unpublished, 1 for published posts
**/
function listPostAdmin($isPub) {
    global $conn;
    $userId = $_SESSION["userId"];
    $stmt = $conn->stmt_init();
    $query = "SELECT title, createDate FROM posts WHERE isPub = '{$isPub}' AND userid = '{$userId}'";

    if ($stmt->prepare($query)) {
      $stmt->execute();

      $stmt->bind_result($title, $createDate);
      $stmt->fetch();

      while (mysqli_stmt_fetch($stmt)) {

        $date = substr($createDate, 0, -9); ?>

        <div class="row" style="display:flex; flex-direction: row; width:600px;">
          <div class="title" style="flex: 1.5;"><?php echo $title; ?></div>
          <div class="createDate" style="flex: 1;"><?php echo $date; ?></div>
        </div>

      <?php }
    }
  }


 ?>
