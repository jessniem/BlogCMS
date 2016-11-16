<?php
    include "head.php";
    include "top.php";
?>
<main>

  <?php

  require_once "functions.php";

  connectDB();
  $stmt = $conn->stmt_init();

  $query = "SELECT posts.*, users.firstName, users.lastName FROM posts LEFT JOIN users ON posts.userid = users.id ORDER BY posts.id DESC";

  if ($stmt->prepare($query)) {
      $stmt->execute();

      $stmt->bind_result($id, $title, $categoryid, $userid, $content, $image, $createDate, $isPub, $fname, $lname);
  }

  // $query2 = "SELECT category FROM categories WHERE id = $categoryid";
  //
  // if ($stmt->prepare($query2)) {
  //     $stmt->execute();
  //
  //     $stmt->bind_result($categoryname);
  // }

  // $catid = "";
  // if ($categoryid == 6) {
  //   $catid = "art";
  // }


  // present the posts from db
  $count = 0;
  $older = 5;
  while (mysqli_stmt_fetch($stmt)) {
    // visa bara de fem senaste inläggen

      //$blogPosts[] = array ("id" => $id, "title" => $title, "categoryid" => $categoryid, "userid" => $userid, "content" => $content, "image" => $image, "createDate" => $createDate, "isPub" => $isPub, "fname" => $fname, "lname" => $lname);

     ?>
        <div class="load-post">
          <article class="post">
            <div class="post-img">
              <img src="<?php echo $image; ?>" alt="">
            </div> <!-- .post-img -->
            <div class="post-text">
              <!-- Top content -->
              <div>
                <p class="tags">Illustration</p>
                <h1><?php echo "$title"; ?></h1>
                <div class="blog-content"><?php echo "$content"; ?></div>
              </div>
              <!-- Bottom content -->
              <div>
                <p class="post-info"><?php echo "$fname $lname"; echo ", "; echo "$createDate"; ?></p>
                <p class="comments">Comments (2)</p>
              </div>
            </div> <!-- .post-text -->
          </article> <!-- .post -->
        </div> <!-- .load-post --> <?php
      $count++;

  } ?>

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

</main>


<?php
    include "footer.php";
?>
