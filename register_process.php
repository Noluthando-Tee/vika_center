<?php


session_start();  // start session
include('config.php');

$fullName = $_POST['registerFullName'] ?? '';
$email = $_POST['registerEmail'] ?? '';
$phone = $_POST['registerPhone'] ?? '';
$password = $_POST['registerPassword'] ?? '';
$course = $_POST['registerCourse'] ?? '';

// Check for empty fields
if (!$fullName || !$email || !$phone || !$password || !$course) {
    die("All fields are required.");
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("This email is already registered. Please use another email or login.");
}
$stmt->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, course) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $fullName, $email, $phone, $hashedPassword, $course);

if ($stmt->execute()) {
    echo "Registration Successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

