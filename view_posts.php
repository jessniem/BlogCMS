<?php session_start()?>
<?php
include "db_connection.php";
include "./includes/head.php";
include "./includes/top_admin.php";
?>
<main class="admin-main">
	<div class="edit-posts">
		<h1>Edit posts</h1>
		<h2>Drafts</h2>
		<?php
		$userId = $_SESSION["userId"];
		$stmt = $conn->stmt_init();
		$query = "SELECT title, createDate FROM posts WHERE isPub = 0 AND userid = '{$userId}'";
		// TODO: Bugg - Visar en mindre än vad som finns i db (både drafts och published)
		// TODO: Kodupprepning - skapa funktion

		if ($stmt->prepare($query)) {
			$stmt->execute();

			$stmt->bind_result($title, $createDate);
			$stmt->fetch();

			// Loop blog posts
			while (mysqli_stmt_fetch($stmt)) { 

				// Remove time from timestamp ($createDate)
				$date = substr($createDate, 0, -9); ?>
				
				<div class="row" style="display:flex; flex-direction: row; width:600px;">
					<div class="title" style="flex: 1.5;"><?php echo $title; ?></div>
					<div class="createDate" style="flex: 1;"><?php echo $date; ?></div>
				</div>
			
			<?php }
		} 
		?>
		<h2>Published posts</h2>
		<?php 
		$stmt = $conn->stmt_init();
		$query = "SELECT title, createDate FROM posts WHERE isPub = 1 AND userid = '{$userId}'";

		if ($stmt->prepare($query)) {
			$stmt->execute();

			$stmt->bind_result($title, $createDate);
			$stmt->fetch();

			while (mysqli_stmt_fetch($stmt)) { 

				$date = substr($createDate, 0, -9); ?>
				
				<div class="row" style="display:flex; flex-direction: row; width:600px;">
					<div class="title" style="flex: 1.5;"><?php echo $title; ?></div>
					<div class="createDate" style="flex: 1;"><?php echo $date; ?></div>
				</div>
			
			<?php }
		} 
		?>
	</div>
</main>
