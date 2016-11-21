<?php
session_start();
include "db_connection.php";

//Check if email and password match db
if( isset($_POST["send"]) ) {
	if( !empty($_POST["email"]) && !empty($_POST["password"]) ) {

		$email = mysqli_real_escape_string($conn, $_POST["email"]);
		$pass = mysqli_real_escape_string($conn, $_POST["password"]);

		$stmt = $conn->stmt_init();
		$query = "SELECT * FROM users WHERE email = '{$email}'";


		if($stmt->prepare($query)) {
			$stmt->execute();

			$stmt->bind_result($id, $accesslevel, $mail, $password, $firstname, $lastname, $profilepic, $ispub);
			$stmt->fetch();

			if ($id != 0 && $pass == $password) {

				$_SESSION["logged_in"] = true;
				$_SESSION["username"] = $firstname;
				$_SESSION["userId"] = $id; 




			header("Location: create_post.php");

			} else {
				header('Location: login.php?pass_fail');
			}
		}
	} else {
		header('location: login.php?login_fail');
	}
}



?>
