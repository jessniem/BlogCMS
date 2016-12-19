<?php
include "./includes/head.php";
include "db_connection.php";

    //USER FEEDBACK: IF EMAIL OR PASSWORD IS EMPTY
    if (isset($_GET['login']) && $_GET["login"] == "empty") {
        ?>
        <div class="error feedback fadeOut">You have to fill in your email and password</div> 
        <?php
    //USER FEEDBACK: IF EMAIL OR PASSWORD DOESN'T MATCH DB
    } elseif (isset($_GET['login']) && $_GET["login"] == "fail") {
        ?>
        <div class="error feedback fadeOut">Wrong email or password</div>
        <?php
    }
    ?>
    <main class="login">
        <section class="login-form">
            <!-- FORM: LOGIN -->
            <form action="logincheck.php" method="post">
                <label for="email">Your email:</label>
                <input name="email" type="text">
                <label for="password">Your password:</label>
                <input name="password" type="password">
                <button name="send" type="submit">Login</button>
            </form>
        </section>
    </main>
</body>
</html>
