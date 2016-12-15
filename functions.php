<?php

/**
* The funtion precent escape characters to be injected in the string presented to MySQL.
*
* @param string $var - The string from the user input.
* @param string $conn - DB connection.
* @return string $VAR - Return a safe sanitized string.
**/
//TODO: KOLLA VARFÖR INTE MYSQLI_REAL_ESCAPE_STRING FUNKAR
function sanitizeMySql($conn, $var) {
    $var = $conn->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}

/**
* The function removes unvanter slashes and HTML from user input.
*
* @param string $var The string from the user inout.
* @return string $var Rerturn a string without html, slashes and tags.
**/
function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}



/**
* The funtion prints out the blog post.
*
* @param bool $index - 1 to publish, 0 to save draft.
* @param int $num - The number of comments on the post.
**/
function printPost($index, $num) {
    global $tag;
    global $post;
    global $categoryid; ?>
    <div class="load-post">
        <article class="post">
            <div class="post-img">
                <a href="comments.php?post=<?php echo $post['id'] ?>#start"><img src="<?php echo $post['image']; ?>" alt="<?php echo $post['alt']; ?>"></a>
            </div>
            <div class="post-text">
                <div>
                    <p class="tags"><i class="fa fa-hashtag" aria-hidden="true"></i> <?php echo $post["tag"]; ?></p>
                    <h1><a href="comments.php?post=<?php echo $post['id'] ?>#start"><?php echo $post["title"]; ?></a></h1>
                    <div class="blog-content"><?php echo $post["content"]; ?></div>
                </div>
                 <div>
                    <p class="post-info"><?php echo $post["fname"]. " " .$post["lname"]. ", " .$post["createDate"]; ?></p> <?php
                    if ($index) { ?>
                    <a href="comments.php?post=<?php echo $post['id'] ?>#start"><p class="comments"><i class="fa fa-comment" aria-hidden="true"></i> Comments (<?php echo $num; ?>)</p></a><?php
                    } ?>
                </div>
            </div> <!-- POST-TEXT -->
        </article>
    </div> <!-- LOAD-POST -->
    <?php
}



/**
* The function prints out a list of the blog post for the admin users
*
* @param int $ispub - 0 for drafts, 1 for published posts.
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
            // IF ADMIN, ALSO PRESENT THE NAME OF THE AUTHOR IN THE LIST
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
                // PRINT OUT THE AUTOR OF THE POST WHEN LOGGED IN AS ADMIN
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
* @param string $time - The date for the calculation.
*
* @return int $numberOfUnits - Calculated time
* @return string $text - Returns the unit
**/
function timeElapsedString($commentTime) {
    $today = strtotime("today");
    $yesterday = strtotime("yesterday");
    $todaysHours = strtotime("now") - strtotime("today");
    $tokens = array(
        31536000 => "year",
        2592000 => "month",
        604800 => "week",
        86400 => "day",
        3600 => "hour",
        60 => "minute",
        1 => "second");

    $time = time() - $commentTime;
    $time = ($time < 1) ? 1 : $time;
    if ($commentTime >= $today || $commentTime < $yesterday) {
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            if ($text == "day") {
                $numberOfUnits = floor(($time - $todaysHours) / $unit) + 1;
            } else {
                $numberOfUnits = floor(($time)/ $unit);
            }
            return $numberOfUnits . " " . $text . (($numberOfUnits > 1) ? "s" : "") . " ago";
        }
    } else {
        return "Yesterday";
    }
}


/**
* The function is used to publish or save a draft.
*
* @param bool $isPub - 1 to publish, 0 to save
**/
function saveOrPub($isPub){
    global $conn;
    $stmt = $conn->stmt_init();

    $targetfolder = "./illustrations/";
    $filetype = ".".substr($_FILES["postImage"]["type"], 6);
    $date = date('c');
    $targetname = $targetfolder . basename ($date . $filetype); // TODO: KANSKE MÅSTE BYTA DATE, POSTID, HAR VI INTE HÄR, KAN MAN LÖSA DET?

    $alt = sanitizeMySql($conn, $_POST["alt"]);
    $title = sanitizeMySql($conn, $_POST["title"]);
    $categoryId = sanitizeMySql($conn, $_POST["tag"]);
    $userId = $_SESSION["userId"];
    $content = sanitizeMySql($conn, $_POST["content"]);

    // check the file extension
    $type = pathinfo($targetname, PATHINFO_EXTENSION);
    if ($type != "jpeg" && $type != "jpg" && $type != "png") { ?>
      <p class="error">Only JPG, JPEG and PNG files are allowed.</p> <?php
    }

    if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
            //FILLUPPLADDNINGEN HAR GÅTT BRA! ?>
			<div class="feedback fadeOut"> <?php
        if ($isPub == 1) {
            echo "The post has been published!";
        } else {
            echo "The post has been saved as a draft!";
        } ?>
		</div> <?php
            $query = "INSERT INTO posts VALUES
            (NULL, '$title', '$categoryId','$userId', '$content', '$targetname', '$alt', NULL, '{$isPub}')";

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
