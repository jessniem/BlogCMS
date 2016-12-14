<?php

/**
* THE FUNCTION PREVENT ESCAPE CHARACTERS TO BE INJECTED IN THE STRINGS PRESENTED TO MYSQL.
*
* @param string $var - THE STRING FROM THE USER INPUT.
* @param string $conn DB CONNECTION
* @return string $VAR RETURN A SAFE SANITIZED STRING.
**/
//TODO: KOLLA VARFÖR INTE MYSQLI_REAL_ESCAPE_STRING FUNKAR
function sanitizeMySql($conn, $var) {
    $var = $conn->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}

/**
* THE FUNCTION REMOVES UNWANTED SLASHES AND HTML FROM USER INPUT.
*
* @param string $var THE STRING FROM THE USER INPUT.
* @return string $var RETURN A SAFE SANITIZED STRING.
**/
function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}



/**
* THE FUNCTION PRINTS OUT THE BLOG POSTS
*
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
                    <p class="tags"><i class="fa fa-hashtag" aria-hidden="true"></i> <?php echo $post["category"]; ?></p>
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
* THE FUNCTION PREVENT ESCAPE CHARACTERS TO BE INJECTED IN THE STRINGS PRESENTED TO MYSQL.
*
* @param int $ispub - SEND IN 0 TO PRINT OUT UNPUBLISHED, 1 FOR PUBLISHED POSTS
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
* THE FUNCTION TAKES THE DATE SENT IN AND RETURNS HOW LONG AGO THAT WAS IN UNITS OF: YEARS, MONTHS, WEEKS, DAYS, HOURS, MINUTES OR SECONDS.
*
* @param string $time - TAKES THE DATE FOR THE CALCULATION
*
* @return int $numberOfUnits - CALCULATED TIME
* @return string $text - RETURN THE UNIT
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
* THE FUNCTION IS USED TO PUBLISH OR SAVE A DRAFT
*
* @param bool $isPub - 1 FOR PUBLISH, 0 FOR SAVE
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
