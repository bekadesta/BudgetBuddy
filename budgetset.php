<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$success = '';
$error = '';
$needs = $wants = $savings = 0.00;
$income = 0.00;

// Fetch user's income
$stmt = $conn->prepare("SELECT amount FROM income WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) {
    $incomeRow = $res->fetch_assoc();
    $income = (float)$incomeRow['amount'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $needs = (float)$_POST['needs'] ?? 0;
    $wants = (float)$_POST['wants'] ?? 0;
    $savings = (float)$_POST['savings'] ?? 0;

    if (($needs + $wants + $savings) !== 100.0) {
        $error = "❌ Total must be exactly 100%.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM budget_allocations WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check && $check->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE budget_allocations SET needs_percent=?, wants_percent=?, savings_percent=? WHERE user_id=?");
            $stmt->bind_param("dddi", $needs, $wants, $savings, $user_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO budget_allocations (user_id, needs_percent, wants_percent, savings_percent) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iddd", $user_id, $needs, $wants, $savings);
        }

        if ($stmt->execute()) {
            $success = "✅ Budget allocation saved!";
        } else {
            $error = "❌ Database error: " . $stmt->error;
        }
    }
}

// Load existing allocations if not just submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $conn->prepare("SELECT needs_percent, wants_percent, savings_percent FROM budget_allocations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $needs = $row['needs_percent'];
            $wants = $row['wants_percent'];
            $savings = $row['savings_percent'];
        }
    }
}

// Budget value calculations
$needsValue = $income * ($needs / 100);
$wantsValue = $income * ($wants / 100);
$savingsValue = $income * ($savings / 100);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget Settings</title>
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
        <nav>
            <div class="navbar">
                <ul>
                    <li><a href="dashh.php">Dashboard</a></li>
                    <li><a href="dailyexpense.php">Tracking</a></li>
                    <li><a href="budgetset.php">Budgets</a></li> 
                    <li><a href="report.php">Reports</a></li>
                    <li><a href="settings.php">Settings</a></li>
                    <li class="logos"><img src="./Images/user-solid.svg" alt=""></li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="containerforsettings">
        <div>
            <h2>Budget Settings</h2>

            <h3>Budgeting Rule</h3>
            <div class="rule-box">
                <p><strong>The 50/30/20 Rule</strong><br>
                Needs: 50%, Wants: 30%, Savings: 20%</p>
            </div>

            <div class="custom-box">
                <p><strong>Custom Budgeting</strong><br>
                <span>Customize your budget allocation percentages</span></p>
            </div>

            <h3>Your Custom Budget Allocation</h3>

            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php elseif ($error): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="budgetset.php" method="POST">
                <div class="input-group2">
                    <label for="needs"><h4>Needs (%)</h4></label>
                    <input type="number" name="needs" id="needs" step="0.01" required value="<?= htmlspecialchars($needs) ?>">
                </div>

                <div class="input-group2">
                    <label for="wants"><h4>Wants (%)</h4></label>
                    <input type="number" name="wants" id="wants" step="0.01" required value="<?= htmlspecialchars($wants) ?>">
                </div>

                <div class="input-group2">
                    <label for="savings"><h4>Savings (%)</h4></label>
                    <input type="number" name="savings" id="savings" step="0.01" required value="<?= htmlspecialchars($savings) ?>">
                </div>

                <div class="styled-button2">
                    <button type="submit">Save Changes</button>
                </div>
            </form>

        </div>
    </section>
</body>
</html>
