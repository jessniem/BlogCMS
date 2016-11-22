<?php
include "./includes/head.php";
include "db_connection.php";

//If email or password is empty
if (isset($_GET['login']) && $_GET["login"] == "empty") {
		echo "<p class='fail'>You must fill in email address and password</p>";
		//If email or password doesn't match db
	} elseif (isset($_GET['login']) && $_GET["login"] == "fail") {
		echo "<p class='fail'>Wrong username or password</p>";
	}
?>
  <main>
    <section class="login">
      <form action="logincheck.php" method="post">
        <label for="email">Your email:</label>
        <input name="email" type="email">
        <label for="password">Your password:</label>
        <input name="password" type="password">
        <button name="send" type="submit">Login</button>
      </form>
    </section>
  </main>
</body>
</html>
