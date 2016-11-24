<?php session_start()?>
<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<main class="admin-main">
	<div class="edit-posts">
		<h1>Edit posts</h1>
		<h2>Drafts</h2>
		<?php
		listPostAdmin(0);
		?>
		<h2>Published posts</h2>
		<?php 
		listPostAdmin(1);
		?>
	</div>
	<?php 
	editPost();
	?>	
</main>
