<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top_admin.php";
require_once "functions.php";
?>
<main class="admin-main">
  <section>
    <h1>Dina uppgifter</h1>
    <?php
    $id = $_SESSION["userId"];

    //UPDATE PROFILE PICTURE
     if(isset ($_POST["profilepic"]) ) {

          $targetfolder = './userimg/';

          //CREATE A NEW FILENAME
          $targetname =  $targetfolder . basename("user". -$id . ".jpg");

          //PUTS THE URL FOR THE FILE INTO DB
          if(move_uploaded_file($_FILES["profilepic"]["tmp_name"], $targetname)) {
              echo "Filuppladdningen gick bra!";
          }
              
          $query = "UPDATE users SET profilePic = '{$targetname}' WHERE id = '{$id}'"; 
          $stmt = $conn->stmt_init();

          if ($stmt->prepare($query)) {
             $stmt->execute();
            } else {
             echo mysqli_error();
            }
        } 
      //UPPDATE DESCRIPTION AND EMAIL
      if(isset ($_POST["submit"]) ) {

        $email = sanitizeMySql($conn, $_POST["email"]);
        $description = sanitizeMySql($conn, $_POST["description"]);
        $stmt = $conn->stmt_init();

        $query = "UPDATE users SET email = '{$email}', description = '{$description}' WHERE id = $id"; 

        if ($stmt->prepare($query)) {
         $stmt->execute();
        } else {
         echo mysqli_error();
        }
      }

    $stmt = $conn->stmt_init();
    $query = "SELECT * FROM users WHERE id = $id";
    if($stmt->prepare($query)) {
      $stmt->execute();

      $stmt->bind_result($id, $accesslevel, $email, $password, $firstname, $lastname, $profilepic, $description);
      $stmt->fetch();
    }
    ?>
    <!-- PROFILE FORM -->
    <form method="post" enctype="multipart/form-data">
      <input name="profilepic" type="file" accept="image/*" onchange="loadFile(event)">
        <div class="img-preview">
              <img src="<?php echo $profilepic ?>" id="output" alt="Preview of uploaded image"/>
        </div>
      <button type="submit" name="profilepic">Update profile picture</button>
    </form>
    <form method="post">
        <input type="textarea" name="description" value="<?php echo $description; ?>">
        <input type="email" name="email" value="<?php echo $email; ?>">
        <button type="submit" name="submit">Save</button>
    </form>

  </section>
  <section>
    <?php
    if (isset($_GET["pw"])) {
        if ($_GET["pw"] == "ok") {
            echo "Your password has been updated!";
        } elseif ($_GET["pw"] == "error") {
          echo "Somthing went wrong, your password has not been changed!";
        }
    } ?>

    <h2>Byt lösenord</h2>
    <form class="" action="changepw.php" method="post">
      <input type="password" name="currentPW" value="" placeholder="Current password">
      <input type="password" name="newPW" value="" placeholder="New password">
      <input type="password" name="newPW2" value="" placeholder="New password again">
      <input type="submit" name="submit" value="Change password">
    </form>
  </section>
</main>

<?php
 ?>
