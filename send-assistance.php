<?php
// Load Composer's autoloader (required after `composer require phpmailer/phpmailer`)
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ✅ Collect form data safely
    $firstName         = htmlspecialchars($_POST['firstName'] ?? '');
    $lastName          = htmlspecialchars($_POST['lastName'] ?? '');
    $email             = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone             = htmlspecialchars($_POST['phone'] ?? '');
    $assistanceType    = htmlspecialchars($_POST['assistanceType'] ?? '');
    $subject           = htmlspecialchars($_POST['subject'] ?? '');
    $message           = htmlspecialchars($_POST['message'] ?? '');
    $preferredContact  = htmlspecialchars($_POST['preferredContact'] ?? 'email');
    $urgency           = htmlspecialchars($_POST['urgency'] ?? 'normal');

    // ✅ Create email body (HTML)
    $body = "
        <h2>New Assistance Request</h2>
        <p><strong>From:</strong> {$firstName} {$lastName}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Assistance Type:</strong> " . ucwords(str_replace('-', ' ', $assistanceType)) . "</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Preferred Contact Method:</strong> " . ucfirst($preferredContact) . "</p>
        <p><strong>Urgency Level:</strong> " . ucwords(str_replace('-', ' ', $urgency)) . "</p>
        <hr>
        <h3>Message:</h3>
        <p>{$message}</p>
    ";

    // ✅ Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP settings (Hostinger example)
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';      // 🔹 Your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact@thejobconnections.xyz'; // 🔹 Your email address
        $mail->Password   = 'Contact-jobcon123';     // 🔹 Email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS
        $mail->Port       = 587;                       // 465 if using SMTPS

        // From / To
        $mail->setFrom('contact@thejobconnections.xyz', 'Job Connections');
        $mail->addAddress('contact@thejobconnections.xyz'); // Recipient (can add more if needed)
        $mail->addReplyTo($email, $firstName . ' ' . $lastName);    // Reply goes to requester

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Assistance Request: {$subject}";
        $mail->Body    = $body;

        // ✅ Send email
        $mail->send();
        echo "✅ Your assistance request has been sent successfully. We'll get back to you soon.";
    } catch (Exception $e) {
        echo "❌ Failed to send your request. Error: {$mail->ErrorInfo}";
    }
}
