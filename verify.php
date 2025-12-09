<?php
require 'db_verbindung.php';

$message = "";

if (isset($_GET['code']) && isset($_GET['email'])) {
    $verification_code = $_GET['code'];
    $email = $_GET['email'];
    
    $stmt = $pdo->prepare("SELECT id FROM benutzer_db WHERE email = ? AND verifizierungs_code = ? AND ist_verifiziert = 0");
    $stmt->execute([$email, $verification_code]);
    $user = $stmt->fetch();
    
    if ($user) {
        $update_stmt = $pdo->prepare("UPDATE benutzer_db SET ist_verifiziert = 1, verifizierungs_code = NULL WHERE id = ?");
        if ($update_stmt->execute([$user['id']])) {
            $message = "Ihr Konto wurde erfolgreich verifiziert! Sie können sich jetzt anmelden.";
        } else {
            $message = "Fehler beim Verifizieren des Kontos.";
        }
    } else {
        $message = "Ungültiger Verifizierungscode oder E-Mail. Oder das Konto ist bereits verifiziert.";
    }
} else {
    $message = "Fehlende Parameter für die Verifizierung.";
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Konto Verifizieren</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Kontostatus</h1>
        <p><?php echo $message; ?></p>
        <p><a href="login.php">Zur Anmeldeseite</a></p>
    </div>
</body>
</html>

