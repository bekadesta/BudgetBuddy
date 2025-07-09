<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch spending by category for this month
$summaryQuery = $conn->prepare("
    SELECT category, SUM(amount) as total 
    FROM expenses 
    WHERE user_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
    GROUP BY category
");
$summaryQuery->bind_param("i", $user_id);
$summaryQuery->execute();
$summaryResult = $summaryQuery->get_result();

$spending = ['Need' => 0, 'Want' => 0, 'Saving' => 0];
while ($row = $summaryResult->fetch_assoc()) {
    $spending[$row['category']] = $row['total'];
}

// Fetch all notes for this month
$notesQuery = $conn->prepare("
    SELECT amount, category, note, created_at 
    FROM expenses 
    WHERE user_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE())
    ORDER BY created_at DESC
");
$notesQuery->bind_param("i", $user_id);
$notesQuery->execute();
$notesResult = $notesQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link rel="stylesheet" href="reports.css">
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
        <nav >
            <div class="navbar">
                <ul>
                <li><a href="dashh.php">Dashboard</a></li>
                <li><a href="dailyexpense.php">Tracking</a></li>
                <li><a href="budgetset.php" >Budgets</a></li>
                <li><a href="report.php">Reports</a></li>
                <li><a href="setting.php">Setting</a></li>
               
                </ul>
            </div>
        </nav>    
    </header>
    
    <section class="report-container">
             <div>
        <h1>Monthly Spending Report</h1>

        <div class="summary-section" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div class="summary-box need">
                <h3>Needs</h3>
                <p>ETB <?php echo number_format($spending['Need'], 2); ?></p>
            </div>
            <div class="summary-box want">
                <h3>Wants</h3>
                <p>ETB <?php echo number_format($spending['Want'], 2); ?></p>
            </div>
            <div class="summary-box saving">
                <h3>Savings</h3>
                <p>ETB <?php echo number_format($spending['Saving'], 2); ?></p>
            </div>
        </div>

        <h2 class="notes-title">Your Spending Notes This Month</h2>
        <div class="notes-section">
            <?php if ($notesResult->num_rows > 0): ?>
                <?php while ($note = $notesResult->fetch_assoc()): ?>
                    <div class="note-card <?php echo strtolower($note['category']); ?>">
                        <h4><?php echo $note['category']; ?> - ETB <?php echo number_format($note['amount'], 2); ?></h4>
                        <p><strong>Note:</strong> <?php echo htmlspecialchars($note['note']); ?></p>
                        <span class="date"><?php echo date('F j, Y', strtotime($note['created_at'])); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No expenses recorded this month.</p>
            <?php endif; ?>
        </div>
     </div>
    </section>



</body>
</html>

