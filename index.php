<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Startseite</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Willkommen auf der Startseite!</h1>
        <p>Dies ist die öffentliche Startseite Ihrer Anwendung.</p>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Sie sind angemeldet als: **<?php echo htmlspecialchars($_SESSION['email']); ?>**</p>
            <p><a href="dashboard.php">Zum Dashboard</a> | <a href="logout.php">Abmelden</a></p>
        <?php else: ?>
            <p>Bitte melden Sie sich an, um den geschützten Bereich zu sehen.</p>
            <p><a href="login.php">Anmelden</a> | <a href="registrieren.php">Registrieren</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
