<?php
require 'db_verbindung.php';
require 'mail_config.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];

    // 1. Validierung
    if (empty($email) || empty($password) || empty($password_repeat)) {
        $error = "Bitte füllen Sie alle Felder aus.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ungültiges E-Mail-Format.";
    } elseif ($password !== $password_repeat) {
        $error = "Die Passwörter stimmen nicht überein.";
    } else {
        // 2. E-Mail-Prüfung
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM benutzer_db WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "E-Mail ist bereits registriert.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $verification_code = bin2hex(random_bytes(32));

            // 3. KORRIGIERT: SQL-Parameter und Variablenanzahl stimmen überein (3 Platzhalter für 3 Variablen)
            // ist_verifiziert = 0 und erstellte_zeit = NOW() sind fest codiert
            $stmt = $pdo->prepare("INSERT INTO benutzer_db (email, password, ist_verifiziert, verifizierungs_code, erstellte_zeit) VALUES (?, ?, 0, ?, NOW())");
            
            // Führe den Insert-Befehl aus
            if ($stmt->execute([$email, $hashed_password, $verification_code])) {
                
                // 4. E-Mail-Versand
                $mail = configureMailer();
                if ($mail) {
                    try {
                        $mail->addAddress($email);
                        $mail->Subject = 'Konto verifizieren';
                        $verification_link = "http://localhost/benutzerverwaltung/verify.php?code=" . $verification_code . "&email=" . urlencode($email);
                        $mail->Body = 'Vielen Dank für Ihre Registrierung. Bitte klicken Sie auf diesen Link, um Ihr Konto zu bestätigen: <a href="' . $verification_link . '">Konto bestätigen</a>';
                        $mail->send();
                        $success = "Registrierung erfolgreich! Bitte überprüfen Sie Ihre E-Mails zur Kontobestätigung.";
                    } catch (Exception $e) {
                        $error = "Registrierung erfolgreich, aber E-Mail-Versand fehlgeschlagen (Fehler: " . $e->getMessage() . ").";
                    }
                } else {
                    $error = "Registrierung erfolgreich, aber E-Mail-Konfiguration fehlgeschlagen.";
                }
            } else {
                $error = "Fehler bei der Datenbank-Registrierung.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registrieren</h1>
        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
        <form action="registrieren.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="password_repeat">Passwort wiederholen:</label>
            <input type="password" id="password_repeat" name="password_repeat" required>
            
            <button type="submit">Registrieren</button>
        </form>
        <p>Haben Sie bereits ein Konto? <a href="login.php">Anmelden</a></p>
    </div>
</body>
</html>
