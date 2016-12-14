<?php
require_once "db_connection.php";
require_once "functions.php";

// SAVES THE CURRENT SELECTION OF TAG WHEN USING THE PAGING-LINKS
$get = "";
if (isset($_GET["month"]) || isset($_GET["tag"])) {
    if (isset($_GET["month"])) {
        $get = "month=".$_GET["month"];
    } elseif ($_GET["tag"]) {
        $get = "tag=".$_GET["tag"];
    }
}

// PAGINATION

$perPage = 5;
$stmt = $conn->stmt_init();

// SET $RESULT TO THE NUMBER OF POSTS
if (isset($_GET["month"]) ) {
    $month = $_GET["month"];
    $result = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTH(createDate) = $month");
} elseif (isset($_GET["tag"])) {
    $tagid = $_GET["tag"];
    $result = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND categoryid = $tagid");
} else {
    $result = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 1");
}

if($result == false) {
  echo mysqli_error($conn);
  exit;
}

// CHECK NUMBER OF POSTS IN DB
$data = mysqli_fetch_assoc($result);
$totPosts = $data['total'];

// NUMBER OF PAGES
$totPages = ceil($totPosts / $perPage);

// GET THE CURRENT PAGE OR SET A DEFAULT
if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $currPage = (int) $_GET["page"];
} else {
    $currPage = 1;
}

// IF CURRENT PAGE IS GREATER THAN TOTAL PAGES
if ($currPage > $totPages) {
   // SET CURRENT PAGE TO LAST PAGE
   $currPage = $totPages;
}

// IF CURRENT PAGE IS LESS THAN FIRST PAGE...
if ($currPage < 1) {
    // SET CURRENT PAGE TO FIRST PAGE
    $currPage = 1;
}

// THE OFFSET OF THE LIST, BASED ON CURRENT PAGE
$offset = ($currPage - 1) * $perPage;

// END OF PAGINATION


// GET POSTS FROM DB
$query = "SELECT posts.id, posts.title, posts.categoryid, posts.userid, posts.content, posts.image, posts.alt, DATE(posts.createDate), posts.isPub, users.firstName, users.lastName, categories.category FROM posts
JOIN users ON (users.id = posts.userid)
JOIN categories ON (categories.id = posts.categoryid)
WHERE isPub = 1";

if (!isset($_GET["tag"]) && (!isset($_GET["month"]))) {
    $query .= " ORDER BY createDate DESC LIMIT $offset, $perPage";

} elseif (isset($_GET["month"]) ) {
  $month = $_GET["month"];
  $query .= " AND MONTH(createDate) = $month ORDER BY createDate DESC";

} elseif (isset($_GET["tag"])) {
    $get2 = "?tag=".$_GET["tag"];
    $and = "&";
    $sortBy = "DESC";
      ?>
    <div class="sortby">
        <a href="index.php<?php echo $get2; ?>">Newest first<i class="fa fa-sort-desc" aria-hidden="true"></i></a>
        <a href="<?php echo $get2 . $and; ?>asc=true">Oldest first<i class="fa fa-sort-asc" aria-hidden="true"></i></a>
    </div>
    <?php
    if(isset($_GET["asc"]) && $_GET["asc"]==true) {
    $sortBy = "ASC";
    }

  $tagid = $_GET["tag"];
  $query .= " AND categoryid = $tagid ORDER BY createDate $sortBy";
}

$blogPosts = [];
if ($stmt->prepare($query)) {
    $stmt->execute();

    $stmt->bind_result($id, $title, $categoryid, $userid, $content, $image, $alt, $createDate, $isPub, $fname, $lname, $tag);

    //SAVE BLOG POSTS IN ARRAY
    while (mysqli_stmt_fetch($stmt)) {
      $blogPosts[] = array ("id" => $id, "title" => $title, "categoryid" => $categoryid, "userid" => $userid, "content" => $content, "image" => $image, "alt" => $alt, "createDate" => $createDate, "isPub" => $isPub, "fname" => $fname, "lname" => $lname, "tag" => $tag);
    }

      // ECHO OUT ARRAY OF BLOG POSTS
      foreach ($blogPosts as $post) {
        $thisPost = $post["id"];
        // count the comments
        $countComments = "SELECT postid FROM comments WHERE postid = $thisPost";
        if ($result = mysqli_query($conn, $countComments)) {
          $num = mysqli_num_rows($result);
        }

          // WHEN $INDEX IS TRUE THE LINK FOR COMMENTS IS PRINTED IN THE POST
          $index = true;
          printPost($index, $num);
      }
}
// PAGINATION LINKS
?>
<div class="pagination">
    <?php
    $pageRange = 3;

    // IF NOT ON PAGE 1, DON'T SHOW BACK LINKS
    if ($currPage > 1) {
        // SHOW << LINK TO GO BACK TO PAGE 1      TODO HUR KAN MAN TA BORT & OCH SÄTTA DET I $GET ISTÄLLET? NU HAMNAR DET FRAMFÖR PAGE OM $GET ÄR TOM
        echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=1'><i class='fa fa-caret-left' aria-hidden='true'></i><i class='fa fa-caret-left' aria-hidden='true'></i></a> ";
        // GET PREVIOUS PAGE NUM
        $prevPage = $currPage - 1;
        // SHOW < LINK TO GO BACK TO 1 PAGE
        echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$prevPage'><i class='fa fa-caret-left' aria-hidden='true'></i></a> ";
  }

// LOOP TO SHOW LINKS TO RANGE OF PAGES AROUND CURRENT PAGE
for ($i = ($currPage - $pageRange); $i < (($currPage + $pageRange) + 1); $i++) {
    // IF IT'S A VALID PAGE NUMBER...
    if (($i > 0) && ($i <= $totPages)) {
        // CURRENT PAGE
        if ($i == $currPage) {
           // MAKE CURRENT PAGE BOLD
           echo "<span>$i</span> ";
        // MAKE LINKS OF THE OTHER PAGES
        } else {
           // MAKE IT A LINK
           echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$i'>$i</a> ";
        }
    }
}

// SHOW FORWARD AND LAST PAGE LINKS IF NOT ON LAST PAGE
if ($currPage != $totPages) {
    // GET NEXT PAGE
    $nextPage = $currPage + 1;
      // LINK TO NEXT PAGE
     echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$nextPage'<i class='fa fa-caret-right' aria-hidden='true'></i></a> ";
     // LINK TO THE LAST PAGE
     echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$totPages'<i class='fa fa-caret-right' aria-hidden='true'></i><i class='fa fa-caret-right' aria-hidden='true'></i></a> ";
  } // END OF PAGINATION LINKS ?>

</div> <!-- PAGINATION -->

<?php
$conn->close();
?>
