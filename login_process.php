<?php
session_start();
include('config.php');

$email = $_POST['loginEmail'] ?? '';
$password = $_POST['loginPassword'] ?? '';

if (!$email || !$password) {
    die("Both email and password are required.");
}

// Check user in database
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No account found with that email.");
}

$user = $result->fetch_assoc();

if (password_verify($password, $user['password'])) {
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_course'] = $user['course'];

    header("Location: student_portal.php");
    exit();
} else {
    die("Incorrect password.");
}
?>

