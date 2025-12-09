<?php

require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function configureMailer() {
    $mail = new PHPMailer(true); 

    try {
      
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'authenticator.living.pi@gmail.com'; 
        $mail->Password   = 'zghjcrdlrcgsqcuu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465; 
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom('authenticator.living.pi@gmail.com', 'Living-Pi'); 
        $mail->isHTML(true);

        return $mail;

    } catch (Exception $e) {
        return null;
    }
}
?>
