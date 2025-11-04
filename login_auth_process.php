<?php
require_once('config.php');

if (isset($_POST['emailLogin'], $_POST['passwordLogin'])) { 
    $email = trim($_POST['emailLogin']);
    $password = trim($_POST['passwordLogin']);

    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ Successful login
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];

            $response = [
                'success' => true,
                'message' => 'Login successful!',
                'user' => [
                    'id' => $user['id'],
                    'full_name' => $user['full_name']
                ]
            ];
        } else {
            // ❌ Invalid password
            $response = [
                'success' => false,
                'message' => 'Invalid password.'
            ];
        }
    } else {
        // ❌ No user found
        $response = [
            'success' => false,
            'message' => 'No user found with that email address.'
        ];
    }

    $stmt->close();
    $conn->close(); // ✅ Close connection safely
} else {
    $response = [
        'success' => false,
        'message' => 'Please fill in both email and password.'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>