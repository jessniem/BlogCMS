<?php
include "./includes/head.php";
include "db_connection.php";
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
