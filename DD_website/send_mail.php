<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = htmlspecialchars($_POST["naam"]);
    $email = htmlspecialchars($_POST["email"]);
    $bericht = htmlspecialchars($_POST["bericht"]);
    $honeypot = $_POST["website"];

    // Honeypot check
    if (!empty($honeypot)) {
        header("Location: fout.php");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP instellingen
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'remco.gerritse06@gmail.com';
        $mail->Password   = 'jouw-app-wachtwoord';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Adressen
        $mail->setFrom('jouwmail@gmail.com', 'Carnavalsvereniging Website');
        $mail->addAddress('remco.gerritse06@gmail.com', 'Bestuur Deudouwers');
        $mail->addReplyTo($email, $naam);

        // Inhoud
        $mail->isHTML(true);
        $mail->Subject = "Nieuw bericht via het contactformulier";
        $mail->Body    = "
            <h2>Nieuw bericht van de website</h2>
            <p><strong>Naam:</strong> {$naam}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Bericht:</strong><br>{$bericht}</p>
        ";
        $mail->AltBody = "Naam: {$naam}\nEmail: {$email}\n\nBericht:\n{$bericht}";

        $mail->send();

        header("Location: bedankt.php");
        exit;
    } catch (Exception $e) {
        header("Location: fout.php");
        exit;
    }
}
