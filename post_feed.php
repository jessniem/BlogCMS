<?php

// saves the current selection of tag when using the paging-links
$get = "";
if (isset($_GET["month"]) || isset($_GET["tag"])) {
    if (isset($_GET["month"])) {
      $get = "month=".$_GET["month"];
    } elseif ($_GET["tag"]) {
      $get = "tag=".$_GET["tag"];
    }
}
echo $get;

require_once "db_connection.php";
require_once "functions.php";

$stmt = $conn->stmt_init();

// PAGINATION

// posts per page
$perPage = 5;

// set $result to the number of posts
if (isset($_GET["month"]) ) {
  $month = $_GET["month"];
  $result = mysqli_query($conn, "SELECT count(*) as total from posts WHERE MONTH(createDate) = $month");
} elseif (isset($_GET["tag"])) {
  $tagid = $_GET["tag"];
  $result = mysqli_query($conn, "SELECT count(*) as total from posts WHERE categoryid = $tagid");
} else {
  $result = mysqli_query($conn, "SELECT count(*) as total from posts");
}

// check number of posts in db

$data = mysqli_fetch_assoc($result);
$totPosts = $data['total'];

// number of pages
$totPages = ceil($totPosts / $perPage);

// get the current page or set a default
if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
   $currPage = (int) $_GET["page"];
} else {
   // default page
   $currPage = 1;
}

// if current page is greater than total pages
if ($currPage > $totPages) {
   // set current page to last page
   $currPage = $totPages;
}

// if current page is less than first page...
if ($currPage < 1) {
   // set current page to first page
   $currPage = 1;
}

// the offset of the list, based on current page
$offset = ($currPage - 1) * $perPage;

// END OF PAGINATION

// get posts from db
if (!isset($_GET["tag"]) && (!isset($_GET["month"]))) {
    $query = "SELECT posts.*, users.firstName, users.lastName, categories.category FROM posts
   JOIN users ON (users.id = posts.userid)
   JOIN categories ON (categories.id = posts.categoryid)
   ORDER BY createDate DESC LIMIT $offset, $perPage";

} elseif (isset($_GET["month"]) ) {
  $month = $_GET["month"];
  $query = "SELECT posts.*, users.firstName, users.lastName, categories.category FROM posts
   JOIN users ON (users.id = posts.userid)
   JOIN categories ON (categories.id = posts.categoryid) WHERE MONTH(createDate) = $month
   ORDER BY createDate DESC";

} elseif (isset($_GET["tag"])) {
  $tagid = $_GET["tag"];
  $query = "SELECT posts.*, users.firstName, users.lastName, categories.category FROM posts
   JOIN users ON (users.id = posts.userid)
   JOIN categories ON (categories.id = posts.categoryid) WHERE categoryid = $tagid
   ORDER BY createDate DESC";
}

//var_dump($stmt->prepare($query));
//var_dump($stmt->error_list);

if ($stmt->prepare($query)) {
    $stmt->execute();

    $stmt->bind_result($id, $title, $categoryid, $userid, $content, $image, $createDate, $isPub, $fname, $lname, $tag);

    //save blog posts in blogPosts array
    while (mysqli_stmt_fetch($stmt)) {
      $blogPosts[] = array ("id" => $id, "title" => $title, "categoryid" => $categoryid, "userid" => $userid, "content" => $content, "image" => $image, "createDate" => $createDate, "isPub" => $isPub, "fname" => $fname, "lname" => $lname, "tag" => $tag);
    }
      // TODO: Fixa sorteringen så att den inte försvinner när klickar på något

      // echo out the blogPosts array
      foreach ($blogPosts as $post) {
          printPost();
      }
}



// PAGINATION LINKS
// how many links to show
?>
<div class="pagination"> <?php
  $pageRange = 3;

  // if not on page 1, don't show back links
  if ($currPage > 1) {
     // show << link to go back to page 1
     echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=1'><i class='fa fa-caret-left' aria-hidden='true'></i><i class='fa fa-caret-left' aria-hidden='true'></i></a> ";
     // get previous page num
     $prevPage = $currPage - 1;
     // show < link to go back to 1 page
     echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$prevPage'><i class='fa fa-caret-left' aria-hidden='true'></i></a> ";
  }

  // loop to show links to range of pages around current page
  for ($i = ($currPage - $pageRange); $i < (($currPage + $pageRange) + 1); $i++) {
     // if it's a valid page number...
     if (($i > 0) && ($i <= $totPages)) {
        // current page
        if ($i == $currPage) {
           // make current page bold
           echo " [<b>$i</b>] ";
        // make links of the other pages
        } else {
           // make it a link
           echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$i'>$i</a> ";
        }
     }
  }

  // show forward and last page links if not on last page
  if ($currPage != $totPages) {
     // get next page
     $nextPage = $currPage + 1;
      // link to next page
     echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$nextPage'<i class='fa fa-caret-right' aria-hidden='true'></i></a> ";
     // link to the last page
     echo " <a href='{$_SERVER['PHP_SELF']}?$get&page=$totPages'<i class='fa fa-caret-right' aria-hidden='true'></i><i class='fa fa-caret-right' aria-hidden='true'></i></a> ";
  } // END OF PAGINATION LINKS ?>

</div> <!-- pagination -->


<!--  <div class="load">
  <a href="#" id="loadMore"><i class="fa fa-angle-double-down" aria-hidden="true"></i></i></a>
</div> --> <!-- .load-btn -->

<!-- <p class="totop">
    <a href="#top">Back to top</a>
</p> -->
<?php

// close db connection
//$stmt->close();
$conn->close();

?>
