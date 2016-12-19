<?php
require_once "db_connection.php";

// ARRAY FOR TAG MENU:
$tags = array(
        array("href" => "index.php",       "name" => "Most recent"),
        array("href" => "index.php?tag=3", "name" => "Black and white", "num" => 3),
        array("href" => "index.php?tag=5", "name" => "Color", 			"num" => 5),
        array("href" => "index.php?tag=1", "name" => "Illustration", 	"num" => 1),
        array("href" => "index.php?tag=2", "name" => "Portrait", 		"num" => 2)
        );

// ARRAY FOR ADMIN (but NOT logout.php):
$admin = array(
         array("href" => "create_post.php", "name" => "Create post"),
         array("href" => "edit_posts.php", "name" => "Edit posts"),
         array("href" => "statistics.php", "name" => "Statistics"),
         array("href" => "profile.php", "name" => "Profile")
);
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
?>

<!-- HAMBURGER MENU (MOBILE) -->
<div class="hamburgermenu">
    <header class="hamburgerheader">
        <button class="hamburgericon">&#9776;</button>
        <button class="cross">&#735;</button>
    </header> <!-- /hamburgerheader -->
    <form method="get" action="index.php" id="filter">
        <div class="basemenu">
            <ul>
                <!-- FILTER OPTION: CATEGORIES -->
                <li>
                    <select name="tag" onchange="reload();">
                        <option value="categories" disabled selected>Categories &#9662;</option>
                        <?php
                        for ($i = 1; $i < count($tags); $i++) { ?>
                           <option value="<?php echo $tags[$i]["num"]; ?>"><?php echo $tags["$i"]["name"]; ?></option>
                           <?php
                        }
                        ?>
                    </select>
                </li>
                <!-- FILTER OPTION: MONTHS -->
                <li>
                    <select name="month" onchange="reload()">
                        <option value="months" disabled selected>Months &#9662;</option>
                         <?php
                            $stmt = $conn->stmt_init();
                            // CHECK WHICH MONTHS THAT HAVE POSTS
                            for($i = 0; $i<12; $i++) {
                                $month = $months["$i"];
                                $query = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTHNAME(createDate) = '$month'");
                                $data = mysqli_fetch_assoc($query);
                                $monthsum = $data['total'];
                                if($monthsum > 0) {
                                    ?>
                                    <option value="<?php echo ($i+1); ?>"><?php echo $months[$i]; ?></option>
                                <?php
                                }
                            }
                            ?>
                    </select>
                </li>
                <li><hr/></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="about_me.php">About me</a></li>
                <?php
                // ONLY FOR LOGGED IN USERS
                if (isset($_SESSION["logged_in"])) {
                    for ($i = 0; $i < count($admin); $i++) {
                        ?>
                        <li><a href="<?php echo $admin["$i"]["href"]; ?>"><?php echo $admin["$i"]["name"]; ?></a></li> <?php
                    }
                    ?>
                    <li><a href="logout.php">Logout</a></li>
                    <?php
                } else {
                    ?>
                    <li><a href="login.php">Login</a></li>
                <?php
                }
                ?>
            </ul>
        </div> <!-- /basemenu -->
        <input type="submit" value="Sortera" hidden>
    </form>
</div> <!-- /hamburger menu -->

<!-- TOPMENU (DESKTOP) -->
<div class="menu top-menu">
    <div class="left">
        <?php
        // ADMIN OPTIONS
        if (isset($_SESSION["logged_in"])) {
            ?>
            <ul>
            <?php
            for ($i = 0; $i < count($admin); $i++) {
                ?>
                <li><a href="<?php echo $admin["$i"]["href"]; ?>"><?php echo $admin["$i"]["name"]; ?></a></li>
                <?php
            }
            ?>
            </ul>
            <?php
        } else {
            ?>
            <ul>
                <li><a href="login.php">Login</a></li>
            </ul>
            <?php
        }
        ?>
    </div> <!-- /left -->
    <div class="right">
        <ul>
            <li><a href="about_me.php">About me</a></li>
            <?php
            // LOGOUT (ONLY FOR LOGGED IN USERS)
            if (isset($_SESSION["logged_in"])) {
                ?>
                <li><a href="logout.php">Logout</a></li>
            <?php
            }
            ?>
            <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
        </ul>
    </div> <!-- /right -->
</div> <!-- /menu top-menu -->

<?php
$currentfile = strrchr($_SERVER['PHP_SELF'], "/");
if (($currentfile == "/index.php") ||
    ($currentfile == "/about_me.php") ||
    ($currentfile == "/comments.php")) {
    ?>

    <!-- BANNER -->
    <a href="index.php">
        <div class="banner">
            <img class="mobile-logo" src="./img/logo_mobile.png" alt="logo">
            <img class="desktop-logo" src="./img/logo_desktop.png" alt="logo">
        </div> <!-- /banner -->
    </a>
    <div class="bluefiller"></div>

    <!-- FILTER MENU -->
    <div class="menu filter-menu">
            <!-- FILTER OPTION: CATEGORIES -->
            <?php
            for ($i = 0; $i < count($tags); $i++) {
                ?>
                <a href="<?php echo $tags["$i"]["href"]; ?>"><?php echo $tags["$i"]["name"]; ?></a>
            <?php
            }
            ?>
            <!-- FILTER OPTION: MONTHS -->
            <div class="dropdown">
                <button class="dropdown-btn">Months <i class="fa fa-caret-down" aria-hidden="true"></i></button>
                <div class="dropdown-content">
                    <?php
                    $stmt = $conn->stmt_init();
                    // LOOP OUT MONTHS WITH AND WITHOUT POSTS
                    for($i = 0; $i<12; $i++) {
                        $month = $months["$i"];
                        $query = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE isPub = 1 AND MONTHNAME(createDate) = '$month'");
                        $data = mysqli_fetch_assoc($query);
                        $monthsum = $data['total'];
                        // MAKE EMPTY MONTHS UNAVAILABLE
                        if($monthsum == 0) {
                            ?>
                            <p class="month empty"><?php echo $months[$i]; ?></p>
                        <?php
                        } else {
                            ?>
                            <a class="month" href="index.php?month=<?php echo ($i+1); ?>"><?php echo $months[$i]; ?> </a>
                            <?php
                        }
                    }
                    ?>
                </div> <!-- /dropdown-content -->
            </div> <!-- /dropdown -->
    </div> <!-- /menu filter-menu -->
    <?php
}
?>
