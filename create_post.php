<?php session_start()?>
<?php
include "db_connection.php";
include "./includes/head.php";
include "./includes/top_admin.php";
?>
<div class="admin-main">
  <div class="create-post">
    <!-- CREATE NEW BLOG POST -->
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

if (isset($_POST["publish"])) {
    saveOrPub(1);
  } elseif (isset($_POST["saveDraft"])) {
    saveOrPub(0);
  }
