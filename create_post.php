<?php session_start()?>

<?php
 include "./includes/head.php";
 include "./includes/top_admin.php";




?>

hej du är inloggad

<div class="admin-main">
  <div class="create-post">
    <form method="post" enctype="multipart/form-data">

      <h1>Create new post</h1>
      <h3>Upload Image</h3>
      <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)"><br>
      <img src="#" style="width:300px; height:auto;" id="output"/>
      <br>

      <input type="text" name="title" placeholder="title"> <br>
      <textarea name="content" style="width:500px; height:300px;"> </textarea> <br>


      <h3>Tags</h3>
        <input type="radio" name="tag" value="3"> Blackandwhite
        <input type="radio" name="tag" value="5"> Color
        <input type="radio" name="tag" value="1"> Illustration
        <input type="radio" name="tag" value="2"> Portrait
        <br>
      <button type="submit" name="saveDraft">Save Draft</button>
      <button type="submit" name="publish">Publish</button>
    </form>

  </div>
</div>

<?php
if (isset ($_POST["publish"])) {

  $targetfolder = "./illustrations/";
  $date = date('c');
  $published = date('Y d m'); //TODO: detta funkar inte!
  $targetname = $targetfolder . basename ($date.".jpg");

  $title = $_POST["title"];
  $categoryId = $_POST["tag"];
  $userId = $_SESSION["userId"];
  $content = $_POST["content"];
  //$image = $_FILES["postImage"];

  if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetname)) {
					//filluppladdningen har gått bra!
					echo "Filuppladdningen gick bra!";

					$conn = new mysqli ("localhost","root","root","echo");
					$query = "INSERT INTO posts VALUES
          (NULL, '$title', '$categoryId','$userId', '$content', '$targetname', '$published', '1')";

					$stmt = $conn->stmt_init();

          if ($stmt->prepare($query)) {
           $stmt->execute();
         } else {
           echo mysqli_error();
         }
      } else {
        echo "Ett fel har uppstått";
      }
}
