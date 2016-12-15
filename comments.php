<?php
require_once "db_connection.php";
require_once "functions.php";
include_once "./includes/head.php";
include_once "./includes/top.php";

// USER FEEDBACK: INVALID EMAIL
if (isset($_GET["email"]) && $_GET["email"] == "invalid") {
    ?>
    <div class="error feedback fadeOut comment-feedback">Your email is not valid</div>
    <?php
}
?>
<main>
    <?php
    // GET SELECTED POST
    $stmt = $conn->stmt_init();
    if (isset($_GET["post"]) ) {
        $thisPostId = sanitizeMySql($conn, $_GET["post"]);
        $query = "SELECT posts.id, posts.title, posts.categoryid, posts.userid, posts.content, posts.image, posts.alt, DATE(posts.createDate), posts.isPub, users.firstName, users.lastName, categories.category FROM posts
        JOIN users ON (users.id = posts.userid)
        JOIN categories ON (categories.id = posts.categoryid) WHERE posts.id = $thisPostId";
    }
    if ($stmt->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($id, $title, $categoryid, $userid, $content, $image, $alt, $createDate, $isPub, $fname, $lname, $tag);
        // SAVE BLOG POST IN ARRAY
        while (mysqli_stmt_fetch($stmt)) {
            $blogPosts[] = array ("id" => $id, "title" => $title, "categoryid" => $categoryid, "userid" => $userid, "content" => $content, "image" => $image, "alt" => $alt, "createDate" => $createDate, "isPub" => $isPub, "fname" => $fname, "lname" => $lname, "tag" => $tag);
        }
        // SHOW BLOG POST
        foreach ($blogPosts as $post) {
            $index = false; ?>
            <div class="post-container" id="start">
                <?php
                printPost($index, 0);
                ?>
            </div> <!-- /post-container -->
            <?php
        }
    }

    // GET COMMENTS FOR SELECTED POST
    $query = "SELECT * FROM comments WHERE postid = $thisPostId ORDER BY date DESC";
    if ($stmt->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($id, $email, $date, $name, $postid, $commentText);
    }
    $comments = [];
    while (mysqli_stmt_fetch($stmt)) {
        $comments[] = array ("id" => $id, "email" => $email, "date" => $date, "name" => $name, "postid" => $postid, "commentText" => $commentText);
    }
    ?>

    <!-- FORM: ADD NEW COMMENT -->
    <section class="comment-form">
        <form action="comments_check.php?postId=<?php echo $thisPostId; ?>" method="post">
            <h2>Your comment:</h2>
            <textarea name="comment" value="" placeholder="Comment..."></textarea>
            <h3>Who are you?</h3>
            <input type="input" name="name" value="" placeholder="Your name">
            <input type="text" name="email" value="" placeholder="Your email">
            <button type="submit" name="submit">Post comment</button>
        </form>
        <?php
        // SHOW HEADING IF COMMENTS ARE AVAILABLE
        if ($commentText != NULL) {
            ?>
            <h2>Comments:</h2>
            <?php
        }
        ?>
    </section> <!-- /comment-form -->

    <?php
    // LIST COMMENTS
    foreach ($comments as $var) {
        $time = strtotime($var["date"]);?>
        <div class="comment" id="comments">
            <div class="comment-fa">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div> <!-- /comment-fa -->
            <div class="comment-content">
                <p><?php echo $var["commentText"]; ?></p>
                <span>By: <?php echo $var["name"] . " - " . $var["email"] . " | " . timeElapsedString($time); ?></span>
            </div> <!-- /comment-content -->
        </div> <!-- /comment -->
    <?php
    }
    ?>
</main>

<?php
include_once "./includes/footer.php";
?>
