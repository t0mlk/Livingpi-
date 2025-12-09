<?php
require 'db_verbindung.php';
require 'mail_config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("SELECT id FROM benutzer_db WHERE email = ? AND ist_verifiziert = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry_time = date("Y-m-d H:i:s", time() + 3600); // Token 1 Stunde gültig
        
        $update_stmt = $pdo->prepare("UPDATE benutzer_db SET reset_token = ?, token_ablaufzeit = ? WHERE id = ?");
        $update_stmt->execute([$token, $expiry_time, $user['id']]);

        $mail = configureMailer();
        if ($mail) {
            try {
                $mail->addAddress($email);
                $mail->Subject = 'Passwort zurücksetzen';
                $reset_link = "http://localhost/reset_password.php?token=" . $token;
                $mail->Body = 'Sie haben die Passwort-Wiederherstellung angefordert. Klicken Sie auf diesen Link, um ein neues Passwort festzulegen: <a href="' . $reset_link . '">Passwort zurücksetzen</a>. Dieser Link läuft in einer Stunde ab.';
                $mail->send();
                $message = "Ein Link zur Passwort-Wiederherstellung wurde an Ihre E-Mail-Adresse gesendet.";
            } catch (Exception $e) {
                $message = "Fehler beim Senden der E-Mail.";
            }
        } else {
             $message = "Ein Link zur Passwort-Wiederherstellung wurde erstellt, aber der E-Mail-Versand ist fehlgeschlagen.";
        }
    } else {
        $message = "Wenn die E-Mail existiert, wird ein Link gesendet."; // Sicherheitshalber vage bleiben
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Passwort vergessen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Passwort vergessen</h1>
        <p>Geben Sie Ihre E-Mail-Adresse ein, um einen Link zur Wiederherstellung zu erhalten.</p>
        <?php if ($message): ?><p class="info"><?php echo $message; ?></p><?php endif; ?>
        <form action="forgot_password.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <button type="submit">Link senden</button>
        </form>
        <p><a href="login.php">Zurück zur Anmeldung</a></p>
    </div>
</body>
</html>
