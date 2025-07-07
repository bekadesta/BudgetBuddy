<?php 














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
            <form action="signup.php" method="get">
            <button class="signupbtn" id="signUp" onclick="window.location.href='signup.php'">Sign Up</button>
        </div>

        <div class="panel right-panel">
            <form action="#">
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
