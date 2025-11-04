<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>

<h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
<p>Email: <?php echo $_SESSION['user_email']; ?></p>
<p>Course: <?php echo $_SESSION['user_course']; ?></p>
<a href="logout.php">Logout</a>
