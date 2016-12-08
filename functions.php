<?php

/**
* The function prevent escape characters to be injected in the strings presented to MySQL.
*
* @param string $var The string from the user input.
* @param string $conn DB connection
* @return string $var Return a safe sanitized string.
**/
//TODO: kolla varför inte mysqli_real_escape_string funkar
function sanitizeMySql($conn, $var) {
  $var = $conn->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}

/**
* The function removes unwanted slashes and HTML from user input.
*
* @param string $var The string from the user input.
* @return string $var Return a safe sanitized string.
**/
function sanitizeString($var) {
  $var = stripslashes($var);
  $var = strip_tags($var);
  $var = htmlentities($var);
  return $var;
}



/**
* The function print out the blog posts
*
**/
function printPost($index, $num) {
  global $tag;
  global $post;
  global $categoryid; ?>
      <div class="load-post">
        <article class="post">
          <div class="post-img">
            <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['alt']; ?>">
          </div>
          <div class="post-text">
            <div>
              <p class="tags"><?php echo $post["tag"]; ?></p>
              <h1><?php echo $post["title"]; ?></h1>
              <div class="blog-content"><?php echo $post["content"]; ?></div>
            </div>
            <div>
              <p class="post-info"><?php echo $post["fname"]. " " .$post["lname"]. ", " .$post["createDate"]; ?></p> <?php
              if ($index) { ?>
                  <a href="comments.php?post=<?php echo $post['id'] ?>#start"><p class="comments">Comments (<?php echo $num; ?>)</p></a><?php
              } ?>
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
        <div class="flex-list row">
          <div class="title">
            <a href="edit_posts.php?delete=<?php echo $id; ?>" class="trash"><i class="fa fa-trash-o"></i></a>
            <a href="edit_posts.php?edit=<?php echo $id; ?>"><i class="fa fa-pencil"></i></a>
            <a href="edit_posts.php?edit=<?php echo $id; ?>"> <?php echo $title;?> </a>
          </div>
          <div class="create-date">
            <?php echo $date; ?>
          </div>
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
    $query = "SELECT title, categoryid, content, image, alt FROM posts WHERE id = '{$id}'";

    if ($stmt->prepare($query)) {
      $stmt->execute();

      $stmt->bind_result($title, $categoryid, $content, $image, $alt);
      $stmt->fetch();
?>
      <!-- ECHO OUT BLOG POST -->
      <section class="edit-form">
        <h1>Update post</h1>
        <form method="post" enctype="multipart/form-data">
          <div class="flex-container">
            <!-- UPLOAD IMAGE -->
            <div class="upload-image">
              <h2>Upload image</h2>
              <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)">
              <label>Text about the image (for SEO and accessibility):</label>
              <input type="text" name="alt" value="<?php echo $alt; ?>">
            </div> <!-- .upload-image -->
            <!-- IMAGE PREVIEW -->
            <div class="img-preview">
              <img src="<?php echo $image ?>" id="output" alt="Preview of uploaded image"/>
            </div> <!-- .img-preview -->
          </div> <!-- .flex-container -->
          <!-- POST CONTENT -->
          <div class="post-content">
            <input type="text" value="<?php echo $title;?>" name="title" placeholder="Title" required="required">
            <textarea name="content" placeholder="Content"><?php echo $content;?></textarea>
            <!-- SELECT TAGS -->
            <div class="select-tag">
              <fieldset>
                <h3>Select tag</h3>
                <input type="radio" id="blackandwhite" name="tag" value="3" <?php echo ($categoryid == 3)? 'checked':''?>>
                <label for="blackandwhite">Blackandwhite</label>
                <input type="radio" id="color" name="tag" value="5" <?php echo ($categoryid == 5)? 'checked':''?>>
                <label for="color">Color</label>
                <input type="radio" id="illustration" name="tag" value="1" <?php echo ($categoryid == 1)? 'checked':''?>>
                <label for="illustration">Illustration</label>
                <input type="radio" id="portrait" name="tag" value="2" <?php echo ($categoryid == 2)? 'checked':''?>>
                <label for="portrait">Portrait</label>
              </fieldset>
            </div>
            <div class="flex-container">
              <button type="submit" name="saveDraft">Save Draft</button>
              <button type="submit" name="publish">Publish</button>
            </div>
          </div>
        </form>
      </section>
      <?php
      if(isset ($_POST["publish"]) || isset($_POST["saveDraft"])) {

          if(isset($_POST["publish"])) {
            $isPub = 1;
          } else {
            $isPub = 0;
          }

          $targetfolder = "./illustrations/";
          $date = date('c');
          $targetname = $targetfolder . basename ($date.".jpg");

          $title = sanitizeMySql($conn, $_POST["title"]);
          $categoryId = sanitizeMySql($conn, $_POST["tag"]);
          $userId = sanitizeMySql($conn, $_SESSION["userId"]);
          $content = sanitizeMySql($conn, $_POST["content"]);

          //echo $image;

          if($image != $targetname) {

            if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
                echo "Filuppladdningen gick bra!";
            }
            $query = "UPDATE posts SET title = '{$title}', categoryid = '{$categoryid}',content = '{$content}', image = '{$targetname}', alt = '{$alt}', createDate = '{$date}', isPub = '{$isPub}'  WHERE id = '{$id}'";
          } else {
            $query = "UPDATE posts SET title = '{$title}', categoryid = '{$categoryid}',content = '{$content}', image = '{$targetname}', alt = '{$alt}', createDate = '{$date}', isPub = '{$isPub}'  WHERE id = '{$id}'";
          }
      }
      $stmt = $conn->stmt_init();

      if ($stmt->prepare($query)) {
       $stmt->execute();
      } else {
       echo mysqli_error();
      }
      }
    }
  }



/**
* The function takes the date sent in and returns how long ago that was in units of: years, months, weeks, days, hours, minutes or seconds.
*
* @param string $time - Takes the date for the calculation
*
* @return int $numberOfUnits - calculated time
* @return string $text - Return the unit
**/
function timeElapsedString($commentTime) {
  $today = strtotime('today');
  $yesterday = strtotime('yesterday');
  $todaysHours = strtotime('now') - strtotime('today');
  $tokens = array(
      31536000 => 'year',
      2592000 => 'month',
      604800 => 'week',
      86400 => 'day',
      3600 => 'hour',
      60 => 'minute',
      1 => 'second');

  $time = time() - $commentTime;
  $time = ($time < 1) ? 1 : $time;
  if ($commentTime >= $today || $commentTime < $yesterday) {
      foreach ($tokens as $unit => $text) {
          if ($time < $unit) {
              continue;
          }
          if ($text == 'day') {
              $numberOfUnits = floor(($time - $todaysHours) / $unit) + 1;
          } else {
              $numberOfUnits = floor(($time)/ $unit);
          }
          return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
      }
  } else {
      return 'Yesterday';
  }
}


/**
* The function is used to publish or save a draft
*
* @param bool $isPub - 1 for publish, 0 for save
**/
function saveOrPub($isPub){
  global $conn;
  $stmt = $conn->stmt_init();

  $targetfolder = "./illustrations/";
  $filetype = ".".substr($_FILES["postImage"]["type"], 6);
  $date = date('c');
  $targetname = $targetfolder . basename ($date . $filetype); // TODO: kanske måste byta date, postid, har vi inte här, kan man lösa det?

  $alt = sanitizeMySql($conn, $_POST["alt"]);
  $title = sanitizeMySql($conn, $_POST["title"]);
  $categoryId = sanitizeMySql($conn, $_POST["tag"]);
  $userId = $_SESSION["userId"];
  $content = sanitizeMySql($conn, $_POST["content"]);

  // check the file extension
  $type = pathinfo($targetname, PATHINFO_EXTENSION);
  if ($type != "jpeg" && $type != "jpg" && $type != "png") { ?>
      <p class="error">Endast JPG, JPEG och PNG-filer är tillåtna.</p> <?php
  }

  if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
			//filluppladdningen har gått bra! ?>
			<div class="feedback fadeOut"> <?php
      if ($isPub == 1) {
        echo "The post has been published!";
      } else {
        echo "The post has been saved as a draft!";
      } ?>
			</div> <?php

			$query = "INSERT INTO posts VALUES
      (NULL, '$title', '$categoryId','$userId', '$content', '$targetname', '$alt', NULL, '{$isPub}')";

			//$stmt = $conn->stmt_init();

      if ($stmt->prepare($query)) {
       $stmt->execute();
      } else {
        echo mysqli_error();
      }
    } else { ?>
      <div class="error feedback fadeOut">
        Something went wrong, the post is not created.
      </div> <?php
    }
}

?>
