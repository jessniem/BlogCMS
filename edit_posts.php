<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";

$stmt = $conn->stmt_init();

// Delete post
if (isset($_GET["delete"])) {
		$id = $_GET["delete"];
		$query = "DELETE FROM posts WHERE id = $id";
		if ($stmt->prepare($query)) {
      $stmt->execute(); ?>
			<div class="feedback fadeOut">
				The post is deleted!
			</div> <?php
		}
}
$userid = $_SESSION['userId'];
$access = $_SESSION["access"];

// Count drafts & published posts
if ($access == 1) { //if admin
	// count number of all published posts for admin
	$published = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 1");
	$drafts = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 0");
} else { // if user or guest
	// count number of published posts by user
	$published = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 1 AND userid = $userid");
	$drafts = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 0 AND userid = $userid");
}
$data = mysqli_fetch_assoc($published);
$totalPub = $data['total'];

$data = mysqli_fetch_assoc($drafts);
$totalDrafts = $data['total'];

?>
<main class="admin-main">
	<section class="post-list">
		<h1>Edit posts</h1> <?php
		 	if ($totalDrafts > 0) { ?>
				<h2>Drafts (<?php echo $totalDrafts; ?>)</h2> <?php
				// LIST OF CREATED DRAFTS
				listPostAdmin(0, $access);
		 	}
			if ($totalPub > 0) { ?>
				<h2>Published posts (<?php echo $totalPub; ?>)</h2> <?php
				// LIST OF CREATED DRAFTS
				listPostAdmin(1, $access);
			} ?>
	</section>

	<!-- UPDATE POST -->
	<div id="update">
		<?php
		editPost();
		?>
	</div>
</main>

<?php
include "./includes/footer.php";
?>
