<?php
include "./includes/head.php";
include "./includes/top_admin.php";
include "./db_connection.php";

$stmt = $conn->stmt_init();
$id = $_SESSION["userId"];

$totalP = "SELECT count(*) as total FROM posts WHERE isPub = 1 AND userid = $id";

if ($stmt->prepare ($totalP)) {
	$stmt->execute();
	$stmt->bind_result($totalPosts);
	$stmt->fetch();
}

$totalC = "SELECT count(*) as total FROM comments"; // TODO Lös det så att vi bara räknar kommentarer på de inlägg som är gjorda av den inloggade usern, nu räknar vi alla...

if ($stmt->prepare ($totalC)) {
	$stmt->execute();
	$stmt->bind_result($totalComments);
	$stmt->fetch();
}

$totalS = $totalComments / $totalPosts;

?>
<main class="admin-main">
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
