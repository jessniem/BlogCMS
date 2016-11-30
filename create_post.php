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
    <form method="post" enctype="multipart/form-data">
      <h1>Create new post</h1>
      <div class="upload-image">
        <h2>Upload Image</h2>
        <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)" required="required">
      </div> <!-- .upload-image -->
      <div class="img-preview">
        <img src="#" id="output" alt="preview of uploaded image">
      </div> <!-- .upload-image -->
      <div class="insert-content">
        <input type="text" name="alt" value="" placeholder="Image description (alt)">
        <input type="text" name="title" placeholder="title" required="required">
        <textarea name="content"></textarea>
        <h2>Tags</h2>
        <input type="radio" name="tag" value="3" required="required">Blackandwhite
        <input type="radio" name="tag" value="5">Color
        <input type="radio" name="tag" value="1">Illustration
        <input type="radio" name="tag" value="2">Portrait
        <button type="submit" name="saveDraft">Save Draft</button>
        <button type="submit" name="publish">Publish</button>
      </div> <!-- .insert-content -->
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
