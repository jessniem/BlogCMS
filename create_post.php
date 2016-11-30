<?php session_start()?>
<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<main class="admin-main">
  <section>
    <!-- CREATE NEW BLOG POST -->
    <h1>Create new post</h1>
    <form method="post" enctype="multipart/form-data">
      <div class="content-container">
        <div class="upload-image">
          <h2>Upload Image</h2>
          <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)" required="required">
          <input type="text" name="alt" value="" placeholder="Image description (alt)">
        </div> <!-- .upload-image -->
        <div class="img-preview">
          <img src="#" id="output" alt="preview of uploaded image">
        </div> <!-- .img-preview -->
      </div>
      <div class="post-content">
        <input type="text" name="title" placeholder="Title" required="required">
        <textarea name="content" placeholder="Content"></textarea>
          <div class="tags">
            <fieldset>
              
              <h3>Tags</h3>
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
        <button type="submit" name="saveDraft">Save Draft</button>
        <button type="submit" name="publish">Publish</button>
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
