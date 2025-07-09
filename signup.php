<?php 


session_start();
require 'budget.php';














?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account / Sign In</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <div class="signupform-containers sign-up-container">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <div class="social-containers">
                    <a href="https://www.instagram.com/" class="social"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/" class="social"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://myaccount.google.com/" class="social"><i class="fab fa-google"></i></a>
                </div>
                <span>or use your email for registration</span>

                <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>



                <div class="input-groups">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Full Name" name="fullname" />
                </div>
                <div class="input-groups">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Email" name="email" />
                </div>
                <div class="input-groups">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" />
                </div>
                <button name="signup" class="sign-up-button" onclick="window.location.href='income.php'">Sign Up</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h1>Welcome Back!</h1>
                    <p>To stay connected with us please login with your personal information.</p>
                    <button class="signinbtn" id="signIn" onclick="window.location.href='signin.php'">Sign In</button>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>
