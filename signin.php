<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    $email = $conn->real_escape_string($email);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["fullname"] = $user["fullname"];
            header("Location: dashh.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In to Budget Buddy</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <div class="panel left-panel">
            <h1>HELLO, BUDDY</h1>
            <p>Enter your personal details and start your journey with us!!</p>
            <button class="signupbtn" id="signUp" onclick="window.location.href='signup.php'">Sign Up</button>
        </div>

        <div class="panel right-panel">
            <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="signin.php">
                <h1>Sign In to budget buddy</h1>
                <div class="social-container">
                    <a href="https://www.instagram.com/" class="social"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/" class="social"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://myaccount.google.com/" class="social"><i class="fab fa-google"></i></a>
                </div>
                <span>or use your email account</span>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email"  placeholder="Email" name="email" />
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" />
                </div>
                <a href="#" class="forgot-password">Forgot your password ?</a>
                <button class="sign-in-button">Sign In</button>
            </form>
        </div>
    </div>

    
</body>
</html>
