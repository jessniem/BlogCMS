<?php session_start()?>
<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<div class="admin-main">
  <div class="create-post">
    <!-- CREATE NEW BLOG POST -->
    <form method="post" enctype="multipart/form-data">
      <h1>Create new post</h1>
      <h3>Upload Image</h3>
      <input name="postImage" type="file" accept="image/*" onchange="loadFile(event)" required="required"><br>
      <img src="#" style="width:300px; height:auto;" id="output"/>
      <br>
      <input type="text" name="title" placeholder="title" required="required"> <br>
      <textarea name="content" style="width:500px; height:300px;"> </textarea> <br>
      <h3>Tags</h3>
        <input type="radio" name="tag" value="3" required="required"> Blackandwhite
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

if (isset($_POST["publish"])) {
    saveOrPub(1);
  } elseif (isset($_POST["saveDraft"])) {
    saveOrPub(0);
  }
