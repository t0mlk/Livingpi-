<?php
require 'db_verbindung.php';

$error = "";
$success = "";
$show_form = false;
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $stmt = $pdo->prepare("SELECT id FROM benutzer_db WHERE reset_token = ? AND token_ablaufzeit > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $show_form = true;
    } else {
        $error = "Der Wiederherstellungs-Link ist ungültig oder abgelaufen.";
    }
} else {
    $error = "Fehlender Wiederherstellungs-Token.";
}

if ($show_form && $_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];

    if ($new_password !== $password_repeat) {
        $error = "Die Passwörter stimmen nicht überein.";
    } elseif (strlen($new_password) < 8) {
        $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $update_stmt = $pdo->prepare("UPDATE benutzer_db SET password = ?, reset_token = NULL, token_ablaufzeit = NULL WHERE id = ?");
        if ($update_stmt->execute([$hashed_password, $user['id']])) {
            $success = "Ihr Passwort wurde erfolgreich geändert. Sie können sich nun anmelden.";
            $show_form = false; 
        } else {
            $error = "Fehler beim Speichern des neuen Passworts.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort zurücksetzen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Passwort zurücksetzen</h1>
        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>

        <?php if ($show_form): ?>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <label for="password">Neues Passwort:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="password_repeat">Passwort wiederholen:</label>
                <input type="password" id="password_repeat" name="password_repeat" required>
                
                <button type="submit">Passwort speichern</button>
            </form>
        <?php endif; ?>
        
        <?php if (!$show_form || $success): ?>
            <p><a href="login.php">Zurück zur Anmeldung</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
