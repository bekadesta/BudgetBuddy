<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// Get fresh data from database
$user_id = (int)$_SESSION['user_id'];
$result = $conn->query("SELECT amount FROM income WHERE user_id = $user_id");

$income = 0.00;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $income = (float)$row['amount'];
    $_SESSION['income'] = $income;
}

$totalSpending = 0.00;
$remainingBudget = $income - $totalSpending;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link rel="stylesheet" href="./CSS/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dashclass">
     <header>
            <div  class="logoss">
                <img src="./Images/Budget-removebg-preview.png" alt="">
            </div>
        <nav >
            <div class="navbar">
                <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="dailyexpense.php">Tracking</a></li>
                <li><a href="budgetset.php">Budgets</a></li>
                <li><a href="Reports.html">Reports</a></li>
                <li><a href="settings.html">setting</a></li>
                <li class="logos"><img src="./Images/user-solid.svg" alt=""></li>
                </ul>
            </div>
        </nav>
        
    </header>
    <section>
       <div class="container1">
            <p>Welcome, <?php echo $_SESSION['fullname']; ?></p>

            <h2 class="title">
            Dashboard
            </h2>

                        <?php if($income == 0): ?>
                <div class="alert">
                    <p>You haven't set your monthly income yet.</p>
                    <a href="inputincome.php" class="btn">Set Income Now</a>
                </div>
            <?php endif; ?>
            <div class="box-container">
            <div class="baby-box">
                <h4>Total Income</h4>
                <h3>ETB<?php echo number_format($income, 2);?></h3>
            </div>
            <div class="baby-box">
                <h4>Total Spending</h4>
                <h3>ETB <?php echo number_format($totalSpending, 2); ?></h3>
            </div>
            <div class="baby-box">
                <h4>Remaining Budget</h4>
                <h3>ETB <?php echo number_format($remainingBudget, 2); ?></h3>  
            </div>
            </div>
       </div>
        <div class="container2">
            <h3>Spending Breakdown</h3> 
            <div class="container3">
                <div><h3>Spending By Category</h3></div>
                <div><h3>ETB <?php echo number_format($totalSpending, 2); ?></h3></div>
                <div>
                    <h3>This Month</h3>
                    <div class="boxes">
                        <div class="box"></div>
                        <div class="box"></div>
                        <div class="box"></div>
                    </div>
                    <div class="labels">
                        <span>Needs</span>
                        <span>Wants</span>
                        <span>Savings</span>
                    </div>
                </div>
            </div>
            <div class="styled-button">
                <a href="inputincome.php">Input Montly Income</a>
            </div>
        </div>

    </section>
</body>
</html>