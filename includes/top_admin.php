<!--- hamburger menu ---->
<div class="menu2">
	<header class="hamburgerheader">
	  <button class="hamburger">&#9776;</button>
	  <button class="cross">&#735;</button>
	</header>
	<div class="basemenu">
		<ul>
			<li><a href="create_post.php">Create post</a></li>
			<li>
				<select>
					<option disabled selected>Edit posts</option>
					<option><li><a href="#">View all posts</a></li></option>
					<option><li><a href="?tag=abstract">View drafts</a></li></option>
					<option><li><a href="?tag=art">View published posts</a></li></option>
				</select>
			</li>
			<li><a href="statistics.php">Statistics</a></li>
			<li><a href="profile.php">Profile</a></li>
		</ul>
	</div>
</div>

<!-- Topmenu -->
<div class="menu top-menu">
	<ul>
		<li><a href="logout.php">Logout</a></li>
	</ul>
</div>
<div class="menu filter-menu">
	<ul>
		<li><a href="index.php">Create post</a></li>
		<div class="dropdown">
			<li>
				<button class="dropdown-btn">Edit posts <i class="fa fa-caret-down" aria-hidden="true"></i></button>
			</li>
			<div class="dropdown-content">
				<a class="month" href="index.php?month=01">View all posts</a>
				<a class="month" href="index.php?month=02">View drafts</a>
				<a class="month" href="index.php?month=03">View published posts</a>
			</div>
		</div> <!-- //dropdown -->
		<li><a href="#"> Statistics</a></li>
		<li><a href="#"> Profile</a></li>
	</ul>
</div>
