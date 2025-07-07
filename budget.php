<?php
  require 'db.php';
$error = '';
$success = '';
$sign_in = false;
$current_user = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
  

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($fullname) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Email already exists';
        } else {
            // Insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = 'Signup successful. Please login.';
            } else {
                $error = 'Signup failed. Please try again.';
            }
        }
// login
        if (isset($_POST['signin'])) {
            $email = trim($_POST['signin_email']);
            $password = trim($_POST['signin_password']);
            
            if (empty($email) || empty($password)) {
                $error = 'Username and password are required';
            } else {
                // Get user
                $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    if (password_verify($password, $user['password'])) {
                        // Login successful
                        $sign_in = true;
                        $current_user = $user;
                        $_SESSION['user_id'] = $user['id'];
                       
                       
                }
            }
        }

        $stmt->close();
    }

    $conn->close();
 } 
}
?>
