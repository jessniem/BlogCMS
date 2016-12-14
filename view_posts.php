<?php session_start()?>
<?php
require_once "db_connection.php";
include_once "./includes/head.php";
include_once "./includes/top.php";
require_once "functions.php";

$stmt = $conn->stmt_init();

// DELETE POST
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $query = "DELETE FROM posts WHERE id = $id";
    if ($stmt->prepare($query)) {
        $stmt->execute();
        echo "Post is deleted";
    }
}

// COUNT NUMBER OF PUBLISHED POSTS
$published = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 1");
$data = mysqli_fetch_assoc($published);
$totalPub = $data['total'];

// COUNT NUMBER OF DRAFTS
$drafts = mysqli_query($conn, "SELECT count(*) as total FROM posts WHERE  isPub = 0");
$data = mysqli_fetch_assoc($drafts);
$totalDrafts = $data['total'];

?>
<main class="admin-main">
    <section class="post-list">
        <h1>Edit posts</h1> <?php
        if ($totalDrafts > 0) { ?>
            <h2>Drafts (<?php echo $totalDrafts; ?>)</h2> <?php
            // LIST OF CREATED DRAFTS
            listPostAdmin(0);
        }
        if ($totalPub > 0) { ?>
            <h2>Published posts (<?php echo $totalPub; ?>)</h2> <?php
            // LIST OF CREATED DRAFTS
            listPostAdmin(1);
        } ?>
    </section>

    <!-- UPDATE POST -->
    <?php
    editPost();
    ?>
</main>

<?php
include "./includes/footer.php";
?>
