<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Willkommen, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
        <p>Dies ist der geschützte Bereich, den nur angemeldete Benutzer sehen können.</p>
        
        <nav>
            <p><a href="index.php">Zur Startseite</a></p>
            <p><a href="logout.php">Abmelden</a></p>
        </nav>
    </div>
</body>
</html>