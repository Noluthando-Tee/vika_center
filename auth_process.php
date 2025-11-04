<?php

session_start();

require_once 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


// ----- REGISTRATION -----

if (isset($_POST['action']) && $_POST['action'] === 'register') {

$name = trim($_POST['full_name']);

$email = trim($_POST['email']);

$password = trim($_POST['password']);

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


// Check if email exists

$check = $conn->prepare("SELECT id FROM users WHERE email = ?");

$check->bind_param("s", $email);

$check->execute();

$check->store_result();

if ($check->num_rows > 0) {

echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);

exit;

}


// Insert new user

$stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");

$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {

echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);

} else {

echo json_encode(['status' => 'error', 'message' => 'Registration failed.']);

}

exit;

}


// ----- LOGIN -----

if (isset($_POST['action']) && $_POST['action'] === 'login') {

$email = trim($_POST['email']);

$password = trim($_POST['password']);


$stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");

$stmt->bind_param("s", $email);

$stmt->execute();

$stmt->store_result();


if ($stmt->num_rows > 0) {

$stmt->bind_result($id, $name, $hashedPassword);

$stmt->fetch();


if (password_verify($password, $hashedPassword)) {

$_SESSION['user_id'] = $id;

$_SESSION['user_name'] = $name;

echo json_encode(['status' => 'success', 'message' => "Welcome, $name"]);

} else {

echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);

}

} else {

echo json_encode(['status' => 'error', 'message' => 'Email not registered.']);

}

header('Location: index.php');

exit;

}


if ($user && password_verify($password, $user['password'])) {

$_SESSION['name'] = $user['name'];

$_SESSION['alerts'][] = [

'type' => 'success',

'message' => 'Login successful'

];

} else {

$_SESSION['alerts'][] = [

'type' => 'error',

'message' => 'Incorrect email or password'

];

$_SESSION['active_form'] = 'login';

}


// redirect

header('Location: index.php');

exit;

}


?>