<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top.php";
require_once "functions.php";
?>

<main class="admin-main">

    <section class="profile">
        <h1>Your information</h1>

        <?php
        //IF NOT GUEST USER
        if ($_SESSION["access"] <= 2 ) {
        ?>
            <!-- PROFILE PICTURE -->
            <section class="profile-pic">
                <?php
                $id = $_SESSION["userId"];
                // UPDATE PROFILE PICTURE
                if(isset ($_POST["profilepic"]) ) {
                    $targetfolder = './userimg/';
                    $targetname =  $targetfolder . basename("user". -$id . ".jpg");
                    if(move_uploaded_file($_FILES["profilepic"]["tmp_name"], $targetname)) {
                        ?>
                        <!-- USER FEEDBACK: CHANGED PROFILE PICTURE -->
                        <div class="feedback fadeOut">Your profile picture has been updated!</div>
                        <?php
                    }
                    $query = "UPDATE users SET profilePic = '{$targetname}' WHERE id = '{$id}'";
                    $stmt = $conn->stmt_init();
                    if ($stmt->prepare($query)) {
                        $stmt->execute();
                    } else {
                      echo mysqli_error();
                    }
                }

                // DESCRIPTION AND EMAIL
                if(isset ($_POST["submit"]) ) {
                    $email = sanitizeMySql($conn, $_POST["email"]);
                    $description = $_POST["description"]; // no sanitizeMySql, nl2br() on output instead 
                    $stmt = $conn->stmt_init();
                    $query = "UPDATE users SET email = '{$email}', description = '{$description}' WHERE id = $id";
                    if ($stmt->prepare($query)) {
                        $stmt->execute();
                        ?>
                        <!-- USER FEEDBACK: CHANGED DESCRIPTION OR EMAIL -->
                        <div class="feedback fadeOut">Your information has been updated!</div>
                        <?php
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
                <!-- FORM: DESCRIPTION AND EMAIL -->
                <form method="post" enctype="multipart/form-data">
                    <div class="flex-container">
                        <div class="upload-image">
                            <label>Your profile picture:</label>
                            <input name="profilepic" type="file" accept="image/*" onchange="loadFile(event)">
                            <button type="submit" name="profilepic">Update profile picture</button>
                        </div> <!-- /upload-image -->
                        <div class="img-preview">
                            <img src="<?php echo $profilepic ?>" id="output" alt="Preview of uploaded image"/>
                        </div> <!-- /img-preview -->
                    </div> <!-- /flex-container -->
                </form>
            </section> <!-- /profile-pic -->
            <section>
                <form method="post">
                    <label>About you:</label>
                    <textarea name="description"><?php echo $description; ?></textarea>
                    <label>Your email:</label>
                    <input type="email" name="email" value="<?php echo $email; ?>">
                    <button type="submit" name="submit" class="description">Save</button>
                </form>
            </section>
        <?php
        }
        ?>

        <!-- CHANGE PASSWORD -->
        <section>
            <?php
            // USER FEEDBACK: CHANGED PASSWORD
            if (isset($_GET["pw"])) {
                if ($_GET["pw"] == "ok") { 
                    ?>
                    <div class="feedback fadeOut">Your password has been updated!</div> 
                    <?php
                } elseif ($_GET["pw"] == "error") { 
                    ?>
                    <div class="error feedback fadeOut">Somthing went wrong, your password has NOT been updated!</div> 
                    <?php
                }
            }
            ?>
            <!-- FORM: CHANGE PASSWORD -->
            <h2>Change password</h2>
            <form class="" action="changepw.php" method="post">
                <input type="password" name="currentPW" value="" placeholder="Current password">
                <input type="password" name="newPW" value="" placeholder="New password">
                <input type="password" name="newPW2" value="" placeholder="Repeat new password">
                <button type="submit" name="submit" class="password">Change password</button>
            </form>
        </section>

        <!-- ADD GUEST USER -->
        <?php
        //IF NOT GUEST USER
        if ($_SESSION["access"] <= 2 ) { 
            ?>
            <section>
                <h2>Add new guest user</h2>
                <?php
                // USER FEEDBACK: ADD GUEST USER
                if (isset($_GET["user"])) {
                    if ($_GET["user"] == "already_registered") {
                        ?>
                        <div class="error feedback fadeOut">The email is already registered</div> <!-- /error feedback fadeOut -->
                        <?php
                    } elseif ($_GET["user"] == "invalid_email") { 
                        ?>
                        <div class="error feedback fadeOut">Invalid email address</div> <!-- /error feedback fadeOut -->
                        <?php
                    } elseif ($_GET["user"] == "success") {
                        ?>
                        <div class="feedback fadeOut">
                            The user was added successfully! <br>
                            User name: <?php echo $_SESSION["user_email"]; ?> <br>
                            Password: <?php echo $_SESSION["user_pw"]; ?>
                        </div> <!-- /feedback fadeOut -->
                        <?php
                    }
                }
                ?>
                <!-- FORM: ADD GUEST USER -->
                <form class="addGuest" action="handle_guest_users.php" method="post">
                    <input type="text" name="fname" value="" placeholder="First name" required="required">
                    <input type="text" name="lname" value="" placeholder="Last name" required="required">
                    <input type="email" name="email" value="" placeholder="Email" required="required">
                    <input type="text" name="password" value="" placeholder="Password" required="required">
                    <button type="submit" name="submit">Create new user</button>
                </form>
            </section>

            <!-- LIST OF CURRENT GUEST USERS -->
            <section>
                <h3>Guest users</h3>
                <?php
                $query = "SELECT id, email, firstName, lastName FROM users WHERE accesslevel = 3";
                if($stmt->prepare($query)) {
                    $stmt->execute();
                    $stmt->bind_result($id, $email, $firstname, $lastname);
                    while (mysqli_stmt_fetch($stmt)) {
                    ?>
                    <div class="flex-list row">
                        <a href="handle_guest_users.php?delete=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="trash"><i class="fa fa-trash-o"></i></a>
                        <?php
                        echo "$firstname $lastname - $email";
                        ?>
                    </div> <!-- /flex-list row -->
                    <?php
                    }
                }
                ?>
            </section>
        <?php
        }
        ?>
    </section> <!-- /profile -->
</main>

<?php
include_once "./includes/footer.php";
?>
