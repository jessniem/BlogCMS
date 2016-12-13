<?php
  require_once "db_connection.php";
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
					<option><li><a href="index.php"> Most recent</a></li></option>
					<option><li><a href="index.php?tag=3"> Black and white</a></li></option>
					<option><li><a href="index.php?tag=5"> Color</a></li></option>
					<option><li><a href="index.php?tag=1"> Illustration</a></li></option>
					<option><li><a href="index.php?tag=2"> Portrait</a></li></option>
				</select>
			</li>
			<li>
				<select>
					<option value="months" disabled selected>Months</option>
          <div class="dropdown-content"> <?php
    				$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    				$stmt = $conn->stmt_init();
            // Check which months that have posts
    				for($i = 0; $i<12; $i++) {
              $month = $months["$i"];
    					$query = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTHNAME(createDate) = '$month'");
    					$data = mysqli_fetch_assoc($query);
    					$monthsum = $data['total'];
    					if($monthsum > 0) { ?>
    						<option><a class="month" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></a></option><?php
    					}
    				}?>
    			</div>
				</select>
			</li>
			<li><hr/></li>
      <li><a href="about_me.php">About me</a></li>
      <li><a href="index.php">Home</a></li> <?php
      // options only visible for logged in users
      if (isset($_SESSION["logged_in"])) { ?>
        <li><a href="create_post.php">Create post</a></li>
  			<li><a href="edit_posts.php">Edit posts</a></li>
  			<li><a href="statistics.php">Statistics</a></li>
  			<li><a href="profile.php">Profile</a></li>
  			<li><a href="logout.php">Logout</a></li> <?php
      } ?>
		</div>
		</ul>
</div> <!-- /hamburger menu -->
</div>

<?php
if (isset($_SESSION["logged_in"])) {

}

?>
<!-- Topmenu -->
<div class="menu top-menu"> <?php

  // admin options only visible for logged in users
  if (isset($_SESSION["logged_in"])) { ?>
    <div class="left">
      <ul>
        <li><a href="create_post.php">Create post</a></li>
        <li><a href="edit_posts.php">Edit posts</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="profile.php">Profile</a></li>
      </ul>
    </div> <?php
  } ?>

  <div class="right">
    <ul>
  		<li><a href="about_me.php">About me</a></li> <?php
      //logout link for logged in users
      if (isset($_SESSION["logged_in"])) { ?>
        <li><a href="logout.php">Logout</a></li> <?php
      } ?>
  		<li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
  	</ul>
  </div>
</div>
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
				$stmt = $conn->stmt_init();
        // Check which months that have posts and not
				for($i = 0; $i<12; $i++) {
          $month = $months["$i"];
					$query = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTHNAME(createDate) = '$month'");
					$data = mysqli_fetch_assoc($query);
					$monthsum = $data['total'];
					if($monthsum == 0) { ?>
					<p class="month empty" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></p> <?php
					} else { ?>
						<a class="month" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></a><?php
					}
				}?>
			</div>
		</div> <!-- /dropdown -->
	</ul>
</div>
