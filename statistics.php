<?php
include_once "./includes/head.php";
include_once "./includes/top.php";
require_once "./db_connection.php";

$access = $_SESSION["access"];
$stmt = $conn->stmt_init();
$user = ($access == 1) ? "admin" : "notadmin";
$userid = $_SESSION["userId"];

//COUNTS POSTS IN TOTAL
switch ($user) {
    case 'admin':
        $totalP = "SELECT count(*) as total FROM posts WHERE isPub = 1";
        break;
    default:
        $totalP = "SELECT count(*) as total FROM posts WHERE isPub = 1 AND userid = $userid";
        break;
}
if ($stmt->prepare ($totalP)) {
    $stmt->execute();
    $stmt->bind_result($totalPosts);
    $stmt->fetch();
}

//COUNTS COMMENTS IN TOTAL
switch ($user) {
    case 'admin':
        $totalC = "SELECT count(*) as total FROM comments";
        break;
    default:
        $totalC = "SELECT count(*) as total FROM comments JOIN posts ON (comments.postid = posts.id) WHERE userid = $userid;";
        break;
}
if ($stmt->prepare ($totalC)) {
    $stmt->execute();
    $stmt->bind_result($totalComments);
    $stmt->fetch();
}

//COUNTS AVERAGE COMMENTS PER POST
$totalS = ($totalComments == 0) ? 0 : $totalComments / $totalPosts;
?>

<!-- SHOW STATISTICS -->
<main class="admin-main margin">
    <section>
        <div class="statistics">
            <div>
                <div class="box"><?php echo $totalPosts;?></div>
                <h3>Number of Posts</h3>
            </div>
            <div>
                <div class="box"><?php echo $totalComments;?></div>
                <h3>Number of comments</h3>
            </div>
            <div>
                <div class="box"><?php echo round($totalS, 2);?></div>
                <h3>Average comment/post</h3>
            </div>
        </div> <!-- /statistics -->
    </section>
</main> <!-- /admin-main margin -->

<?php
include_once "./includes/footer.php";
?>
