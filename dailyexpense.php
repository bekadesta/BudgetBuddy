<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = isset($_POST['Amount']) ? (float)$_POST['Amount'] : 0;
    $category = $_POST['category'] ?? '';
    $note = $_POST['Note'] ?? '';

    // Basic validation
    if ($amount <= 0) {
        $error = "Please enter a valid amount greater than zero.";
    } elseif (!in_array($category, ['Need', 'Want', 'Saving'])) {
        $error = "Please select a valid category.";
    } else {
        // Insert into expenses table (create this table if you haven't)
        $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, category, note, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("idss", $user_id, $amount, $category, $note);

        if ($stmt->execute()) {
            $success = "Expense added successfully!";
            header("Location: dashh.php");
        } else {
            $error = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Calculate total spending for the user (all categories)
$result = $conn->query("SELECT SUM(amount) AS total_spent FROM expenses WHERE user_id = $user_id");
$totalSpending = 0.0;
if ($result && $row = $result->fetch_assoc()) {
    $totalSpending = (float)$row['total_spent'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daily Expense Tracker</title>
    <link rel="stylesheet" href="./CSS/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body class="dashclass">
    <header>
        <div class="logoss">
            <img src="./Images/Budget-removebg-preview.png" alt="Budget Buddy Logo" />
        </div>
        <nav>
            <div class="navbar">
                <ul>
                    <li><a href="dashh.php">Dashboard</a></li>
                    <li><a href="dailyexpense.php">Tracking</a></li>
                    <li><a href="budgetset.php">Budgets</a></li>
                    <li><a href="Reports.php">Reports</a></li>
                    <li><a href="budgetsettings.php">Settings</a></li>
                    <li class="logos"><img src="./Images/user-solid.svg" alt="User Icon" /></li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="containerdaily">
        <h2 class="expense">Add Your Daily Expense Here</h2>

        <?php if ($success): ?>
            <div class="success-message" style="color: green;"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif ($error): ?>
            <div class="alert" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="dailyexpense.php">
            <div class="input-group">
                <input type="number" id="Amount" name="Amount" min="1" placeholder="Amount you spent today" step="0.01" required />
            </div>
            <br />
            <div class="input-group">
                <input list="categoryOptions" name="category" id="category" placeholder="Category of what you spent" required />
                <datalist id="categoryOptions">
                    <option value="Need">
                    <option value="Want">
                    <option value="Saving">
                </datalist>
            </div>
            <br />
            <div class="input-group">
                <input type="text" id="Note" name="Note" placeholder="Things to remember (optional)" />
            </div>
            <br />
            <div class="styled-button2">
                <button type="submit">Save</button>
            </div>
        </form>

        <h3>Your Total Spending So Far: ETB <?php echo number_format($totalSpending, 2); ?></h3>
    </section>
</body>
</html>
