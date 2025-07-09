<?php
session_start();
include 'db.php';

// Debug output
error_log("Session data: " . print_r($_SESSION, true));

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['income'])) {
    $income = (float)$_POST['income']; // Convert to float
    $user_id = (int)$_SESSION['user_id'];

    // Debug
    error_log("Attempting to save income: $income for user: $user_id");

    // Check if record exists
    $check = $conn->query("SELECT id FROM income WHERE user_id = $user_id");
    if ($check === false) {
        error_log("Check query failed: " . $conn->error);
    } else {
        error_log("Check query returned " . $check->num_rows . " rows");
    }

    if ($check && $check->num_rows > 0) {
        $sql = "UPDATE income SET amount = $income WHERE user_id = $user_id";
    } else {
        $sql = "INSERT INTO income (user_id, amount) VALUES ($user_id, $income)";
    }

    error_log("Executing query: $sql");
    $result = $conn->query($sql);

    if ($result) {
        $_SESSION['income'] = $income;
        $_SESSION['income_updated'] = true;
        header("Location: dashh.php");
        exit();
    } else {
        error_log("Failed to save income: " . $conn->error);
        $_SESSION['error'] = "Failed to save income. Please try again.";
        header("Location: inputincome.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Monthly Income</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dashclass">
     <header>
            <div  class="logoss">
                <li style="list-style-type: none;">
                    <a href="signin.php">
                    <img src="./Images/Budget-removebg-preview.png" alt="">
                </li>
            </div>
        <div>
        <nav >
            <div class="navbar">
                <ul>
                <li><a href="dashh.php">Dashboard</a></li>
                <li><a href="Tracking.html">Tracking</a></li>
                <li><a href="budgetset.php">Budgets</a></li>
                <li><a href="Reports.html">Reports</a></li>
                <li><a href="settings.html">setting</a></li>
                <li class="logos"><img src="./Images/user-solid.svg" alt=""></li>
                </ul>
            </div>
        </nav>
    </header>
    <section>
        <div class="inputsection" >
            <h2>What's your montly income?</h2>
        <form action="inputincome.php" method="POST">
                    <div class="input-group">
            <input type="number" name="income" placeholder="ETB 0.00" step="0.01" min="0" required>
        </div>
        <div class="styled-button">
              <button type="submit">Next</button>
        </div>
        </form>    
        </div>
    </section>
</body>
</html>