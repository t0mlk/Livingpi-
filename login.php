<?php
session_start();
require 'db_verbindung.php';

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT id, email, password, ist_verifiziert FROM benutzer_db WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        if ($user['ist_verifiziert'] == 1) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "E-Mail oder Passwort ist falsch.";
            }
        } else {
            $error = "Ihr Konto wurde noch nicht verifiziert. Bitte überprüfen Sie Ihre E-Mails.";
        }
    } else {
        $error = "E-Mail oder Passwort ist falsch.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Anmelden</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Anmelden</h1>
        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Anmelden</button>
        </form>
        <p><a href="forgot_password.php">Passwort vergessen?</a></p>
        <p>Noch kein Konto? <a href="registrieren.php">Registrieren</a></p>
    </div>
</body>
</html>

