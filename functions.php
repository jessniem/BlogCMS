<?php

/**
* The function print out the blog posts
*
**/
function printPost() {
  //global $conn;
  global $tag;
  global $post;
  global $categoryid; ?>
      <div class="load-post">
        <article class="post">
          <div class="post-img">
            <img src="<?php echo $post['image']; ?>" alt="">
          </div>
          <div class="post-text">
            <div>
              <p class="tags"><?php echo $post["tag"]; ?></p>
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
    $query = "SELECT id, title, createDate FROM posts WHERE isPub = '{$isPub}' AND userid = '{$userId}'";

    if ($stmt->prepare($query)) {
      $stmt->execute();

      $stmt->bind_result($id, $title, $createDate);
      $stmt->fetch();

      while (mysqli_stmt_fetch($stmt)) {

        $date = substr($createDate, 0, -9);  
        ?>
        <div class="row" style="display:flex; flex-direction: row; width:600px;">
          <div class="title" style="flex: 1.5;">
            <a style="color:black;" href="view_posts.php?edit=<?php echo $id; ?>"> <?php echo $title;?> </a>
          </div>
          <div class="createDate" style="flex: 1;"><?php echo $date; ?></div>
        </div>



      <?php }
    }
  }

/**
* The function is used to update posts with get. //TODO: SKRIV BÄTTRE FUNKTIONSKOMMENTAR! 
*
* 
**/
function editPost() {
if (isset ($_GET["edit"])) { 
        global $conn;
        $id = $_GET["edit"];  
        $stmt = $conn->stmt_init();
          $query = "SELECT title, categoryid, content, image FROM posts WHERE id = '{$id}'";

          if ($stmt->prepare($query)) {
            $stmt->execute();

            $stmt->bind_result($title, $categoryid, $content, $image);
            $stmt->fetch(); 
      ?>
    <!-- UPDATE BLOG POST -->
      <form method="post" enctype="multipart/form-data">
        <h1>Update post</h1>
        <h3>Upload Image</h3>
        <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)"><br>
        <img src="<?php echo $image ?>" style="width:300px; height:auto;" id="output"/>
        <br>
        <input value="<?php echo $title;?>" type="text" name="title" placeholder="title"> <br>
        <textarea name="content" style="width:500px; height:300px;"><?php echo $content;?></textarea> <br>
        <h3>Tags</h3>
          <input type="radio" name="tag" value="3" <?php echo ($categoryid == 3)? 'checked':''?>> Blackandwhite
          <input type="radio" name="tag" value="5"<?php echo ($categoryid == 5)? 'checked':''?>> Color
          <input type="radio" name="tag" value="1"<?php echo ($categoryid == 1)? 'checked':''?>> Illustration
          <input type="radio" name="tag" value="2"<?php echo ($categoryid == 2)? 'checked':''?>> Portrait
          <br>
        <button type="submit" name="saveDraft">Save Draft</button>
        <button type="submit" name="publish">Publish</button>
      </form>

  <?php
if (isset ($_POST["publish"])) {

  $targetfolder = "./illustrations/";
  $date = date('c');
  $targetname = $targetfolder . basename ($date.".jpg");

  $title = $_POST["title"];
  $categoryId = $_POST["tag"];
  $userId = $_SESSION["userId"];
  $content = $_POST["content"];

  if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
          //filluppladdningen har gått bra!
          echo "Filuppladdningen gick bra!";

          $query = "UPDATE posts SET title = '{$title}', categoryid = '{$categoryid}',content = '{$content}', image = '{$targetname}', createDate = '{$date}', isPub = '1'  WHERE id = '{$id}'";

          $stmt = $conn->stmt_init();

          if ($stmt->prepare($query)) {

           $stmt->execute();

          } else {

           echo mysqli_error();

          }

    } else {

      echo "Ett fel har uppstått";

    }
}


   }
  }
}



  ?>

