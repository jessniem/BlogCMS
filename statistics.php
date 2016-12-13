<?php
include "./includes/head.php";
include "./includes/top_admin.php";
include "./db_connection.php";

$access = $_SESSION["access"];

$stmt = $conn->stmt_init();
if ($access == 1) {
	$user = "admin";
} else {
	$user = "notadmin";
}
$userid = $_SESSION["userId"];

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
if ($totalComments == 0) {
	$totalS = 0;
} else {
	$totalS = $totalComments / $totalPosts;
}

?>
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
		</div>
	</section>
</main>
<?php
include_once "./includes/footer.php";
?>
