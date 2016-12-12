<?php
require_once "db_connection.php";

$stmt = $conn->stmt_init();
 ?>


<!--- hamburger menu ---->
<div class="menu2">
	<header class="hamburgerheader">
	  <button class="hamburger">&#9776;</button>
	  <button class="cross">&#735;</button>
	</header>
	<div class="basemenu">
		<ul>
			<li>
				<select>
					<option value="kategorier" disabled selected>Categories</option>
					<option><li><a href="index.phpindex.php"> Most recent</a></li></option>
					<option><li><a href="index.php?tag=3"> Black and white</a></li></option>
					<option><li><a href="index.php?tag=5"> Color</a></li></option>
					<option><li><a href="index.php?tag=1"> Illustration</a></li></option>
					<option><li><a href="index.php?tag=2"> Portrait</a></li></option>
				</select>
			</li>
			<li>
				<select>
					<option value="months" disabled selected>Months</option>
					<option><a class="month" href="index.php?month=1">January</a></option>
					<option><a class="month" href="index.php?month=2">February</a></option>
					<option><a class="month" href="index.php?month=3">March</a></option>
					<option><a class="month" href="index.php?month=4">April</a></option>
					<option><a class="month" href="index.php?month=5">May</a></option>
					<option><a class="month" href="index.php?month=6">June</a></option>
					<option><a class="month" href="index.php?month=7">July</a></option>
					<option><a class="month" href="index.php?month=8">August</a></option>
					<option><a class="month" href="index.php?month=9">September</a></option>
					<option><a class="month" href="index.php?month=10">October</a></option>
					<option><a class="month" href="index.php?month=11">November</a></option>
					<option><a class="month" href="index.php?month=12">December</a></option>
				</select>
			</li>
			<li><hr/></li>
			<li><a href="index.php">Home</a></li>
			<li><a href="about_me.php">About me</a></li>
			<li><a href="create_post.php">Admin</a></li>
			<li><a href="logout.php">Log out</a></li>
		</div>
		</ul>
</div>
</div>
<?php
if (isset($_SESSION["logged_in"])) {

}

?>
<!-- Topmenu --> <?php
if (!isset($_SESSION["logged_in"])) { ?>
<div class="menu top-menu">
	<ul>
		<li><a href="about_me.php">About me</a></li>
		<li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
	</ul>
</div><?php
} ?>
<!-- Banner -->
<div class="banner">
	<img class="mobile-logo" src="./img/logo_mobile.png" alt="logo">
	<img class="desktop-logo" src="./img/logo_desktop.png" alt="logo">
</div>
<div class="bluefiller">
</div>
<div class="menu filter-menu">
	<ul>
		<li><a href="index.php"> Most recent</a></li>
		<li><a href="index.php?tag=3"> Black and white</a></li>
		<li><a href="index.php?tag=5"> Color</a></li>
		<li><a href="index.php?tag=1"> Illustration</a></li>
		<li><a href="index.php?tag=2"> Portrait</a></li>
		<div class="dropdown">
			<li>
				<button class="dropdown-btn">Months <i class="fa fa-caret-down" aria-hidden="true"></i></button>
			</li>
			<div class="dropdown-content"> <?php
				$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
				for($i = 0; $i<13; $i++) {
					$query = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTHNAME(createDate) = months['$i']");
					$data = mysqli_fetch_assoc($query);
					$monthsum = $data['total'];
					if($monthsum == 0) { ?>
					<a class="month empty" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></a> <?php
					} else { ?>
						<a class="month" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></a><?php
					}
				}?>

				<a class="month" href="index.php?month=2">February</a>
				<a class="month" href="index.php?month=3">March</a>
				<a class="month" href="index.php?month=4">April</a>
				<a class="month" href="index.php?month=5">May</a>
				<a class="month" href="index.php?month=6">June</a>
				<a class="month" href="index.php?month=7">July</a>
				<a class="month" href="index.php?month=8">August</a>
				<a class="month" href="index.php?month=9">September</a>
				<a class="month" href="index.php?month=10">October</a>
				<a class="month" href="index.php?month=11">November</a>
				<a class="month" href="index.php?month=12">December</a>
			</div>
		</div> <!-- //dropdown -->
	</ul>
</div>
