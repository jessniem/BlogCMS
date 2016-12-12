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
function listPostAdmin($isPub, $access) {

    global $conn;
    $userId = $_SESSION["userId"];
    $stmt = $conn->stmt_init();
    if ($access == 1) {
      $query = "SELECT posts.id, posts.title, posts.createDate, users.firstName, users.lastName FROM posts
                JOIN users ON (posts.userid = users.id)
                WHERE isPub = '{$isPub}' ORDER BY createDate DESC";
    } else {
      $query = "SELECT id, title, createDate FROM posts WHERE isPub = '{$isPub}' AND userid = '{$userId}' ORDER BY createDate DESC";
    }

    if ($stmt->prepare($query)) {
      $stmt->execute();
      if ($access == 1) {
        // if admin, also present the name of the author in the list
        $stmt->bind_result($id, $title, $createDate, $fn, $ln);
      } else {
        $stmt->bind_result($id, $title, $createDate);
      }

      while (mysqli_stmt_fetch($stmt)) {
        $date = substr($createDate, 0, -9);
        ?>
        <div class="flex-list row">
          <div class="title">
            <a href="edit_posts.php?delete=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this post?');" class="trash"><i class="fa fa-trash-o"></i></a>
            <a href="edit_posts.php?edit=<?php echo $id; ?>#update"><i class="fa fa-pencil"></i></a>
            <a href="edit_posts.php?edit=<?php echo $id; ?>#update"> <?php echo $title;?> </a>
          </div>
          <div class="create-date"> <?php
          // Print out the autor of the post when logged in as admin
            if ($access == 1) {
              echo "$fn $ln | ";
            }?>
            <?php echo $date; ?>
          </div>
        </div>
      <?php }
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
