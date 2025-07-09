<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Buddy - Settings</title>
    <link rel="stylesheet" href="settings.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <header class="navbar">
            <div class="logo">
                <img src="Header/img/Budget-removebg-preview.png"  alt="">
            </div>
            <nav>
                <ul>
                    <li><a href="dashh.php">Dashboard</a></li>
                    <li><a href="dailyexpense.php">Tracking</a></li>
                    <li><a href="budgetset.php">Budgets</a></li>
                    <li><a href="report.php">Reports</a></li>
                    <li><a href="settings.php">Settings</a></li>

                </ul>
            
        </header>

        <div class="content-area">
            <div class="settings-card">
                <h2>Settings</h2>

                <div class="section">
                    <h3>Budget alerts</h3>
                    <div class="setting-item">
                        <div class="setting-text">
                            <h4>Budget exceeded</h4>
                            <p>Get notified when you're close to exceeding your budget.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="budgetExceededToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-text">
                            <h4>Budget nearing limit</h4>
                            <p>Receive alerts when you're nearing your budget limit.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="budgetNearingLimitToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="section">
                    <h3>Daily spending reminders</h3>
                    <div class="setting-item">
                        <div class="setting-text">
                            <h4>Daily spending reminder</h4>
                            <p>Get a daily reminder to track your expenses.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="dailySpendingReminderToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-text">
                            <h4>Daily spending summary</h4>
                            <p>Receive a summary of your daily spending.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="dailySpendingSummaryToggle">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="settings_script.js"></script>
</body>
</html>
