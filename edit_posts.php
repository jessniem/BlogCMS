<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top.php";
require_once "functions.php";

$stmt = $conn->stmt_init();

// DELETE POST
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $query = "DELETE FROM posts WHERE id = $id";
    if ($stmt->prepare($query)) {
        $stmt->execute();
        ?>
        <div class="feedback fadeOut">
        	The post is deleted!
        </div> <!-- /feedback fadeOut -->
        <?php
    }
}

// DELETE COMMENT
if (isset($_GET["deleteComment"])) {
    $commentid = $_GET["deleteComment"];
    $query = "DELETE FROM comments WHERE id = $commentid";
    if ($stmt->prepare($query)) {
        $stmt->execute();
        ?>
        <div class="feedback fadeOut">
            The comment is deleted!
        </div> <!-- /feedback fadeOut -->
        <?php
    }
}

$userid = $_SESSION['userId'];
$access = $_SESSION["access"];

// COUNT POSTS (DRAFTS & PUBLISHED)
if ($access == 1) { // IF ADMIN, COUNT ALL POSTS
    $published = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 1");
    $drafts = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 0");
} else { // IF USER OR GUEST, COUNT OWN POSTS ONLY
    $published = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 1 AND userid = $userid");
    $drafts = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 0 AND userid = $userid");
}
$data = mysqli_fetch_assoc($published);
$totalPub = $data['total'];
$data = mysqli_fetch_assoc($drafts);
$totalDrafts = $data['total'];
?>

<main class="admin-main">
    <!-- LIST POSTS -->
    <section class="list">
        <h1>Edit posts</h1>
        <?php
        // LIST CREATED DRAFTS
        if ($totalDrafts > 0) {
            ?>
            <h2>Drafts (<?php echo $totalDrafts; ?>)</h2>
            <?php
            listPostAdmin(0, $access);
        }
        // LIST PUBLISHED POSTS
        if ($totalPub > 0) {
            ?>
            <h2>Published posts (<?php echo $totalPub; ?>)</h2>
            <?php
            listPostAdmin(1, $access);
        }
        ?>
    </section> <!-- /list -->

    <!-- UPDATE SELECTED POST -->
    <div id="update">
        <?php
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
                <!-- SHOW SELECTED BLOG POST -->
                <section class="border-top">
                    <h1>Update post</h1>
                    <form method="post" enctype="multipart/form-data">

                        <!-- UPLOAD IMAGE -->
                        <div class="flex-container">
                            <div class="upload-image">
                                <h2>Upload image</h2>
                                <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)">
                                <label>Text about the image (for SEO and accessibility):</label>
                                <input type="text" name="alt" value="<?php echo $alt; ?>">
                            </div> <!-- /upload-image -->
                            <div class="img-preview">
                                    <img src="<?php echo $image ?>" id="output" alt="Preview of uploaded image"/>
                            </div> <!-- /img-preview -->
                        </div> <!-- /flex-container -->

                        <!-- POST CONTENT -->
                        <div class="post-content">
                            <input type="text" value="<?php echo $title;?>" name="title" placeholder="Title" required="required">
                            <textarea name="content" placeholder="Content"><?php echo $content;?></textarea>
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
                            </div> <!-- /select-tag -->
                            <div class="flex-container">
                                <button type="submit" name="saveDraft">Save Draft</button>
                                <button type="submit" name="publish">Publish</button>
                            </div> <!-- /flex-container -->
                        </div> <!-- /post-content -->
                    </form>
                </section> <!-- /border-top -->

                <?php
                //SAVE OR PUBLISH POST
                if(isset ($_POST["publish"]) || isset($_POST["saveDraft"])) {
                    if(isset($_POST["publish"])) {
                        $isPub = 1;
                    } else {
                        $isPub = 0;
                    }
                    $targetfolder = "./illustrations/";
                    $date = date('c');
                    $targetname = $targetfolder . basename ($date.".jpg");
                    $alt = sanitizeMySql($conn, $_POST["alt"]);
                    $title = sanitizeMySql($conn, $_POST["title"]);
                    $categoryid = $_POST["tag"];
                    $userId = sanitizeMySql($conn, $_SESSION["userId"]);
                    $content = sanitizeMySql($conn, $_POST["content"]);
                    $query = "UPDATE posts SET title = '{$title}', categoryid = '{$categoryid}', content = '{$content}', alt = '{$alt}', createDate = '{$date}', isPub = '{$isPub}'";
                    if(move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
                        $query .= ", image = '{$targetname}'";
                    }
                    $query .= " WHERE id = '{$id}'";
                    $stmt = $conn->stmt_init();
                    if ($stmt->prepare($query)) {
                        $stmt->execute();
                    } else {
                        echo mysqli_error();
                    }
                }
            }
        }
        ?>
    </div> <!-- /update -->

    <?php
    //GET COMMENTS
    if (isset ($_GET["edit"])) {
        $thisPostId = sanitizeMySql($conn, $_GET["edit"]);
        $query = "SELECT * FROM comments WHERE postid = $thisPostId ORDER BY date DESC";
        if ($stmt->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($commentid, $email, $date, $name, $postid, $commentText);
            ?>
            <!-- LIST CURRENT COMMENTS -->
            <section class="list border-top">
                <h2 class="comment-h2">Comments</h2>
                <?php
                while (mysqli_stmt_fetch($stmt)) {
                    ?>
                    <div class="flex-list row">
                        <div class="comment-row">
                            <a href="edit_posts.php?deleteComment=<?php echo $commentid; ?>" onclick="return confirm('Are you sure you want to delete this post?');" class="trash"><i class="fa fa-trash-o"></i></a>
                            <?php echo $commentText;?>
                        </div> <!-- /comment-row -->
                        <div class="create-date">
                            <?php echo $email; ?>
                        </div> <!-- /create-date -->
                    </div> <!-- /flex-list row -->
                    <?php
                }
                //IF NO COMMENT AVAILABLE
                if ( empty($commentid) ) {
                    echo "<p>This post does not contain any comments</p>";
                }
                ?>
            </section> <!-- /list border-top -->
            <?php
        }
    }
    ?>
</main> <!-- /admin-main -->

<?php
include "./includes/footer.php";
?>
