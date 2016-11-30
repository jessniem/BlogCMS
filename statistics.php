<?php 
include "./includes/head.php";
include "./includes/top_admin.php"; 
include "./db_connection.php"; 

$stmt = $conn->stmt_init();

$totalP = "SELECT count(*) as total FROM posts WHERE isPub = 1"; 

if ($stmt->prepare ($totalP)) {
	$stmt->execute(); 
	$stmt->bind_result ($totalPosts); 
	$stmt->fetch(); 
} 

$totalC = "SELECT count(*) as total FROM comments"; 

if ($stmt->prepare ($totalC)) {
	$stmt->execute(); 
	$stmt->bind_result ($totalComments); 
	$stmt->fetch(); 
} 

$totalS = $totalComments / $totalPosts; 

?>
	<div>
		<div class="box"><?php echo $totalPosts;?></div>
		<h3>Number of Posts in total</h3>
	</div>

	<div>
		<div class="box"><?php echo $totalComments;?></div>
		<h3>Number of comments in total</h3>
	</div>

	<div>
		<div class="box"><?php echo $totalS;?></div>
		<h3>Average number of comments on posts</h3>
	</div>			

<?php

 


?> 