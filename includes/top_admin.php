<!--- hamburger menu ---->
<div class="menu2">
	<header class="hamburgerheader">
	  <button class="hamburger">&#9776;</button>
	  <button class="cross">&#735;</button>
	</header>
	<div class="basemenu">
		<ul>
			<li><a class="admin" href="create_post.php">Create post</a></li>
			<li>
				<select>
					<option disabled selected>Edit posts</option>
					<option><li><a href="view_posts.php">View all posts</a></li></option>
					<option><li><a href="?view=drafts">View drafts</a></li></option>
					<option><li><a href="?view=published">View published posts</a></li></option>
				</select>
			</li>
			<li><a class="admin" href="statistics.php">Statistics</a></li>
			<li><a class="admin" href="profile.php">Profile</a></li>
			<li><a class="admin" href="logout.php">Logout</a></li>
		</ul>
	</div>
</div>

<!-- Topmenu -->
<!-- <div class="menu top-menu">
	<ul>
		<li></li>
	</ul>
</div> -->
<div class="menu filter-menu">
		<div class="first">
			<ul>
				<li><a href="create_post.php">Create post</a></li>
				<div class="dropdown">
					<li>
						<button class="dropdown-btn">Edit posts <i class="fa fa-caret-down" aria-hidden="true"></i></button>
					</li>
					<div class="dropdown-content">
						<a class="admin" href="view_posts.php">View all posts</a>
						<a class="admin" href="index.php?view=drafts">View drafts</a>
						<a class="admin" href="index.php?view=published">View published posts</a>
					</div>
				</div> <!-- //dropdown -->
				<li><a href="#"> Statistics</a></li>
				<li><a href="#"> Profile</a></li>
			</ul>
		</div>
		<div class="logout">
			<ul>
				<li><a class="admin" href="logout.php">Logout</a></li>
			</ul>
		</div>
</div>
