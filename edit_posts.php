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
	</section> <?php


	/**
	* The function is used to update posts with get. //TODO: SKRIV BÄTTRE FUNKTIONSKOMMENTAR!
	*
	*
	**/
	function editPost() {
	  if (isset ($_GET["edit"])) {
	    global $conn;
	    $id = $_GET["edit"];
	    $stmt = $conn->stmt_init();
	    $query = "SELECT title, categoryid, content, image, alt FROM posts WHERE id = '{$id}'";

	    if ($stmt->prepare($query)) {
	      $stmt->execute();

	      $stmt->bind_result($title, $categoryid, $content, $image, $alt);
	      $stmt->fetch();
	?>
	      <!-- ECHO OUT BLOG POST -->
	      <section class="edit-form">
	        <h1>Update post</h1>
	        <form method="post" enctype="multipart/form-data">
	          <div class="flex-container">
	            <!-- UPLOAD IMAGE -->
	            <div class="upload-image">
	              <h2>Upload image</h2>
	              <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)">
	              <label>Text about the image (for SEO and accessibility):</label>
	              <input type="text" name="alt" value="<?php echo $alt; ?>">
	            </div> <!-- .upload-image -->
	            <!-- IMAGE PREVIEW -->
	            <div class="img-preview">
	              <img src="<?php echo $image ?>" id="output" alt="Preview of uploaded image"/>
	            </div> <!-- .img-preview -->
	          </div> <!-- .flex-container -->
	          <!-- POST CONTENT -->
	          <div class="post-content">
	            <input type="text" value="<?php echo $title;?>" name="title" placeholder="Title" required="required">
	            <textarea name="content" placeholder="Content"><?php echo $content;?></textarea>
	            <!-- SELECT TAGS -->
	            <div class="select-tag">
	              <fieldset>
	                <h3>Select tag</h3>
	                <input type="radio" id="blackandwhite" name="tag" value="3" <?php echo ($categoryid == 3)? 'checked':''?>>
	                <label for="blackandwhite">Blackandwhite</label>
	                <input type="radio" id="color" name="tag" value="5" <?php echo ($categoryid == 5)? 'checked':''?>>
	                <label for="color">Color</label>
	                <input type="radio" id="illustration" name="tag" value="1" <?php echo ($categoryid == 1)? 'checked':''?>>
	                <label for="illustration">Illustration</label>
	                <input type="radio" id="portrait" name="tag" value="2" <?php echo ($categoryid == 2)? 'checked':''?>>
	                <label for="portrait">Portrait</label>
	              </fieldset>
	            </div>
	            <div class="flex-container">
	              <button type="submit" name="saveDraft">Save Draft</button>
	              <button type="submit" name="publish">Publish</button>
	            </div>
	          </div>
	        </form>
	      </section>
	      <?php
	      if(isset ($_POST["publish"]) || isset($_POST["saveDraft"])) {

	          if(isset($_POST["publish"])) {
	            $isPub = 1;
	          } else {
	            $isPub = 0;
	          }

	          $targetfolder = "./illustrations/";
	          $date = date('c');
	          $targetname = $targetfolder . basename ($date.".jpg");

	          $title = sanitizeMySql($conn, $_POST["title"]);
	          $categoryId = sanitizeMySql($conn, $_POST["tag"]);
	          $userId = sanitizeMySql($conn, $_SESSION["userId"]);
	          $content = sanitizeMySql($conn, $_POST["content"]);

						$query = "UPDATE posts SET title = '{$title}', categoryid = '{$categoryid}', content = '{$content}', alt = '{$alt}', createDate = '{$date}', isPub = '{$isPub}'";

						if(move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
							$query .= ", image = '{$targetname}'";
						}

						$query .= " WHERE id = '{$id}'";

						// kör queryn
						$stmt = $conn->stmt_init();

						if ($stmt->prepare($query)) {
							$stmt->execute();
						} else {
							echo mysqli_error();
						}
	      	}
	      }
	    }
	  } ?>






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
