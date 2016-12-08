<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<main class="admin-main">
  <section>
    <!-- FORM: CREATE NEW BLOG POST -->
    <h1>Create new post</h1>
    <form method="post" enctype="multipart/form-data">
      <div class="flex-container">
        <!-- UPLOAD IMAGE -->
        <div class="upload-image">
          <h2>Upload image</h2>
          <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)" required="required">
          <label>Text about the image (for SEO and accessibility):</label>
          <input type="text" name="alt" value="" placeholder="Image description (alt)">
        </div> <!-- .upload-image -->
        <!-- IMAGE PREVIEW -->
        <div class="img-preview">
          <img src="#" id="output" alt="preview of uploaded image">
        </div> <!-- .img-preview -->
      </div> <!-- .flex-container -->
      <!-- POST CONTENT -->
      <div class="post-content">
        <input type="text" name="title" placeholder="Title" required="required">
        <textarea name="content" placeholder="Content"></textarea>
        <!-- SELECT TAGS -->
        <div class="select-tag">
          <fieldset>
            <h3>Select tag</h3>
            <input type="radio" id="blackandwhite" name="tag" value="3">
            <label for="blackandwhite">Blackandwhite</label>
            <input type="radio" id="color" name="tag" value="5">
            <label for="color">Color</label>
            <input type="radio" id="illustration" name="tag" value="1">
            <label for="illustration">Illustration</label>
            <input type="radio" id="portrait" name="tag" value="2">
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
</main>

<?php
if (isset($_POST["publish"])) {
    saveOrPub(1);
  } elseif (isset($_POST["saveDraft"])) {
    saveOrPub(0);
  }
?>

<?php
include "./includes/footer.php";
?>
