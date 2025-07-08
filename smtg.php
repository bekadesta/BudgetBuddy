<?php
session_start();
include 'db.php';


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully.";
}

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// Fetch the income from the database
$stmt = $conn->query("SELECT amount FROM income ORDER BY id DESC LIMIT 1");

if ($stmt) {
    $row = $stmt->fetch_assoc(); // Use fetch_assoc() to get the row as an associative array
    $income = $row['amount'] ?? 0.00; // Safely access the 'amount' column or default to 0.00
} else {
    $income = 0.00; // Default to 0.00 if the query fails
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get percentages from the form
    $needsPercentage = (int)$_POST['needs'];
    $wantsPercentage = (int)$_POST['wants'];
    $savingsPercentage = (int)$_POST['savings'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $needsPercentage = filter_input(INPUT_POST, 'needs', FILTER_VALIDATE_INT);
        $wantsPercentage = filter_input(INPUT_POST, 'wants', FILTER_VALIDATE_INT);
        $savingsPercentage = filter_input(INPUT_POST, 'savings', FILTER_VALIDATE_INT);    
    // Validate percentages
    if (($needsPercentage + $wantsPercentage + $savingsPercentage) !== 100) {
        echo "The percentages must add up to 100.";
        exit();
    }

    // Calculate budget allocations
    $needs = ($income * $needsPercentage) / 100;
    $wants = ($income * $wantsPercentage) / 100;
    $savings = ($income * $savingsPercentage) / 100;
    

    // Insert or update the calculated budget in the database
    $stmt = $conn->prepare("INSERT INTO allocation (user_id, needs, wants, savings) 
                            VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE 
                            needs = VALUES(needs), wants = VALUES(wants), savings = VALUES(savings)");

$stmt->close();
$conn->close();
   
   
}}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Allocation</title>
</head>
<body>
    <h3>Your Custom Budget Allocation</h3>
    <p>Income: $<?php echo number_format($income, 2); ?></p>
    <form action="smtg.php" method="POST">
        <label for="needs">Needs (%):</label>
        <input type="number" id="needs" name="needs" value="50" required><br>

        <label for="wants">Wants (%):</label>
        <input type="number" id="wants" name="wants" value="30" required><br>

        <label for="savings">Savings (%):</label>
        <input type="number" id="savings" name="savings" value="20" required><br>

        <button type="submit">Save Budget</button>
    </form>
</body>
</html>