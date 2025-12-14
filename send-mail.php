<?php
// Load Composer's autoloader (required after `composer require phpmailer/phpmailer`)
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ✅ Collect form data safely
    $first   = htmlspecialchars($_POST['firstName'] ?? '');
    $last    = htmlspecialchars($_POST['lastName'] ?? '');
    $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars($_POST['phone'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $job     = htmlspecialchars($_POST['jobApply'] ?? '');

    // ✅ Create email body (HTML)
    $body = "
        <h2>New Job Application</h2>
        <p><strong>First Name:</strong> {$first}</p>
        <p><strong>Last Name:</strong> {$last}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Address:</strong> {$address}</p>
        <p><strong>Job Apply:</strong> {$job}</p>
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
        $mail->addReplyTo($email, $first . ' ' . $last);    // Reply goes to applicant

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Job Application';
        $mail->Body    = $body;

        // ✅ Send email
        $mail->send();
        echo "✅ Your application has been sent successfully.";
    } catch (Exception $e) {
        echo "❌ Failed to send your application. Error: {$mail->ErrorInfo}";
    }
}
