<?php
// -----------------------------
// 1. DATABASE CONNECTION
// -----------------------------
$servername = "localhost";
$username = "root";
$password = ""; // Change if your MySQL has a password
$dbname = "vika_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// -----------------------------
// 2. GET AND SANITIZE FORM DATA
// -----------------------------
$fullName = trim($_POST['fullName'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$message  = trim($_POST['message'] ?? '');

// -----------------------------
// 3. SERVER-SIDE VALIDATION
// -----------------------------
$errors = [];

if (empty($fullName)) $errors[] = "Full Name is required.";
if (empty($email)) $errors[] = "Email is required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
if (empty($phone)) $errors[] = "Phone number is required.";
if (!preg_match('/^[0-9]{10}$/', $phone)) $errors[] = "Phone number must be exactly 10 digits.";
if (empty($message)) $errors[] = "Message is required.";

if (!empty($errors)) {
    $error_msg = implode("\\n", $errors);
    echo "<script>alert('Error:\\n$error_msg'); window.history.back();</script>";
    exit;
}

// Escape data for database
$fullName = $conn->real_escape_string($fullName);
$email    = $conn->real_escape_string($email);
$phone    = $conn->real_escape_string($phone);
$message  = $conn->real_escape_string($message);

// -----------------------------
// 4. INSERT INTO DATABASE
// -----------------------------
$sql = "INSERT INTO enquiries (fullName, email, phone, message) 
        VALUES ('$fullName', '$email', '$phone', '$message')";

if ($conn->query($sql) === TRUE) {
    // -----------------------------
    // 5. SEND EMAIL NOTIFICATION TO ADMIN
    // -----------------------------
    $to      = "admin@vikaacademy.co.za"; // Replace with your admin email
    $subject = "New Enquiry from Vika Academy Website";
    $body    = "You have received a new enquiry:\n\n"
             . "Name: $fullName\n"
             . "Email: $email\n"
             . "Phone: $phone\n"
             . "Message:\n$message\n\n"
             . "Sent on: " . date('Y-m-d H:i:s');
    $headers = "From: noreply@vikaacademy.co.za";

    @mail($to, $subject, $body, $headers);

    // -----------------------------
    // 6. SUCCESS MESSAGE
    // -----------------------------
    echo "<script>
            alert('Thank you for your enquiry! We will respond within 24 hours.');
            window.location.href='index.html';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>