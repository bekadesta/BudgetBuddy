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

$resultSpending = $conn->query("SELECT SUM(amount) AS total_spending FROM expenses WHERE user_id = $user_id");
$totalSpending = 0.00;
if ($resultSpending) {
    $row = $resultSpending->fetch_assoc();
    $totalSpending = $row['total_spending'] ?? 0.00;
}
$remainingBudget = $income - $totalSpending;

$budgetResult = $conn->prepare("SELECT needs_percent, wants_percent, savings_percent FROM budget_allocations WHERE user_id = ?");
$budgetResult->bind_param("i", $user_id);
$budgetResult->execute();
$budgetData = $budgetResult->get_result();

$needs = $wants = $savings = 0;

if ($budgetData && $budgetData->num_rows > 0) {
    $row = $budgetData->fetch_assoc();
    $needs = (float)$row['needs_percent'];
    $wants = (float)$row['wants_percent'];
    $savings = (float)$row['savings_percent'];

    // Calculate actual values based on income
    $needsValue = ($income * $needs) / 100;
    $wantsValue = ($income * $wants) / 100;
    $savingsValue = ($income * $savings) / 100;
}


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
                <li><a href="budgetset.php" >Budgets</a></li>
                <li><a href="Reports.html">Reports</a></li>
                <li><a href="settings.html">setting</a></li>
               <li class="logos"><a href="signin.php"><img src="./Images/user-solid.svg" alt="User Icon"></a></li>

                </ul>
            </div>
        </nav>
        
    </header>
    <section>
       <div class="container1">
            <h1>Welcome, <?php echo $_SESSION['fullname']; ?></h1>

            <h2 class="title">
            Dashboard
            </h2>

                        <?php if($income == 0): ?>
                <div class="alert">
                    <p>You haven't set your monthly income yet.</p>
                    <a href="inputincome.php" class="btn">Set Income Now</a>
                </div>
            <?php endif; ?>
            <div class="box-containers">
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
  <div class="box-container">
    <div class="box" id="needs-box">
      <div class="fill"></div>
    </div>
    <div class="label" id="needs-label"></div>
  </div>

  <div class="box-container">
    <div class="box" id="wants-box">
      <div class="fill"></div>
    </div>
    <div class="label" id="wants-label"></div>
  </div>

  <div class="box-container">
    <div class="box" id="savings-box">
      <div class="fill"></div>
    </div>
    <div class="label" id="savings-label"></div>
  </div>
</div>
            </div>
            </div>
            <div class="styled-button">
                <a href="inputincome.php">Input Montly Income</a>
            </div>
        </div>

    </section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const needsPercent = <?php echo $needs ?? 0; ?>;
  const wantsPercent = <?php echo $wants ?? 0; ?>;
  const savingsPercent = <?php echo $savings ?? 0; ?>;

  const needsValue = <?php echo $needsValue ?? 0; ?>;
  const wantsValue = <?php echo $wantsValue ?? 0; ?>;
  const savingsValue = <?php echo $savingsValue ?? 0; ?>;

  const needsFill = document.querySelector('#needs-box .fill');
  const wantsFill = document.querySelector('#wants-box .fill');
  const savingsFill = document.querySelector('#savings-box .fill');

  needsFill.style.height = needsPercent * 1.5 + 'px';
  wantsFill.style.height = wantsPercent * 1.5 + 'px';
  savingsFill.style.height = savingsPercent * 1.5 + 'px';

  document.getElementById('needs-label').textContent = `Needs (${needsPercent}%): ETB ${needsValue.toFixed(2)}`;
  document.getElementById('wants-label').textContent = `Wants (${wantsPercent}%): ETB ${wantsValue.toFixed(2)}`;
  document.getElementById('savings-label').textContent = `Savings (${savingsPercent}%): ETB ${savingsValue.toFixed(2)}`;
});
</script>


</body>
</html>
