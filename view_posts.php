<?php session_start()?>
<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<main class="admin-main">
	<section class="admin-form">
		<h1>Edit posts</h1>
		<h2>Drafts</h2>
		<!-- LIST OF CREATED DRAFTS -->
		<?php
		listPostAdmin(0);
		?>
		<h2>Published posts</h2>
		<!-- LIST OF PUBLISHED POSTS -->
		<?php 
		listPostAdmin(1);
		?>
	</section>
	<section>
	<!-- UPDATE POST -->
	<?php 
	editPost();
	?>
	</section>
</main>
<?php
include "./includes/footer.php";
?>
