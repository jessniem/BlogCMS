<?php
  require_once "db_connection.php";

//ARRAY FOR THE TAG MENU:
$tags = array(
        array("href" => "index.php",       "name" => "Most recent"),
        array("href" => "index.php?tag=3", "name" => "Black and white"),
        array("href" => "index.php?tag=5", "name" => "Color"),
        array("href" => "index.php?tag=1", "name" => "Illustration"),
        array("href" => "index.php?tag=2", "name" => "Portrait")
        );
// ARRAY FOR THE ADMIN MENU (BUT NOT logout.php) 
$admin = array(
         array("href" => "create_post.php", "name" => "Create post"),
         array("href" => "edit_posts.php", "name" => "Edit posts"),
         array("href" => "statistics.php", "name" => "Statistics"),
         array("href" => "profile.php", "name" => "Profile")
);
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

?>

<!--- HAMBURGER MENU ---->
<div class="menu2">
  <header class="hamburgerheader">
    <button class="hamburger">&#9776;</button>
    <button class="cross">&#735;</button>
  </header>
  <div class="basemenu">
    <ul>
      <li>
        <select>
          <option value="kategorier" disabled selected>Categories</option><?php
          //PRINT OUT THE TAGS
          for ($i = 0; $i < count($tags); $i++) { ?>
            <option><li><a href="<?php echo $tags["$i"]["href"]; ?>"><?php echo $tags["$i"]["name"]; ?></a></li></option> <?php
          } ?>
        </select>
      </li>
      <li>
        <select>
          <option value="months" disabled selected>Months</option>
          <div class="dropdown-content"> <?php
            $stmt = $conn->stmt_init();
            // CHECK WHICH MONTHS THAT HAVE POSTS
            for($i = 0; $i<12; $i++) {
              $month = $months["$i"];
              $query = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTHNAME(createDate) = '$month'");
              $data = mysqli_fetch_assoc($query);
              $monthsum = $data['total'];
              if($monthsum > 0) { ?>
                <option><a class="month" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></a></option><?php
              }
            } ?>
          </div>
        </select>
      </li>
      <li><hr/></li>
      <li><a href="about_me.php">About me</a></li>
      <li><a href="index.php">Home</a></li> <?php
      // OPTIONS ONLY VISIBLE FOR LOGGED IN USERS 
      if (isset($_SESSION["logged_in"])) {
        for ($i = 0; $i < count($admin); $i++) { ?>
          <li><a href="<?php echo $admin["$i"]["href"]; ?>"><?php echo $admin["$i"]["name"]; ?></a></li> <?php
        } ?>
        <li><a href="logout.php">Logout</a></li> <?php
      } ?>
    </div>
  </ul>
</div> <!-- /HAMBURGER MENU -->



<!-- TOPMENU -->
<div class="menu top-menu"> <?php

  // ADMIN OPTIONS ONLY VISIBLE FOR LOGGED IN USERS 
  if (isset($_SESSION["logged_in"])) { ?>
    <div class="left">
      <ul> <?php
        for ($i = 0; $i < count($admin); $i++) { ?>
          <li><a href="<?php echo $admin["$i"]["href"]; ?>"><?php echo $admin["$i"]["name"]; ?></a></li> <?php
        } ?>
      </ul>
    </div> <?php
  } ?>

  <div class="right">
    <ul>
      <li><a href="about_me.php">About me</a></li> <?php
      //LOGOUT LINK FOR LOGGED IN USERS 
      if (isset($_SESSION["logged_in"])) { ?>
        <li><a href="logout.php">Logout</a></li> <?php
      } ?>
      <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
    </ul>
  </div>
</div>
<!-- BANNER -->
<div class="banner">
  <img class="mobile-logo" src="./img/logo_mobile.png" alt="logo">
  <img class="desktop-logo" src="./img/logo_desktop.png" alt="logo">
</div>
<div class="bluefiller">
</div>
<div class="menu filter-menu">
  <ul> <?php
  //PRINT OUT THE TAGS MENU
  for ($i = 0; $i < count($tags); $i++) { ?>
    <li><a href="<?php echo $tags["$i"]["href"]; ?>"><?php echo $tags["$i"]["name"]; ?></a></li> <?php
  } ?>
  <div class="dropdown">
    <li>
      <button class="dropdown-btn">Months <i class="fa fa-caret-down" aria-hidden="true"></i></button>
    </li>
    <div class="dropdown-content"> <?php
      $stmt = $conn->stmt_init();
      // CHECK WHICH MONTHS THAT HAVE POSTS AND NOT
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
  </div> <!-- /DROPDOWN -->
  </ul>
</div>
