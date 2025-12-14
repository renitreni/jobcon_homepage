<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize form data
    $name    = htmlspecialchars($_POST['name'] ?? '');
    $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please provide a valid email address.";
        exit;
    }

    // Email configuration
    $to = "Sab_princes@yahoo.com";
    $email_subject = "Contact Form: $subject";

    // Create email headers
    $headers = "From: $name <$email>" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";

    // Create email body (plain text)
    $email_body = "New Contact Form Submission\n\n";
    $email_body .= "From: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Subject: $subject\n\n";
    $email_body .= "Message:\n$message\n";

    // Send email using PHP's mail() function
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "✅ Your message has been sent successfully. We'll get back to you soon.";
    } else {
        http_response_code(500);
        echo "❌ Failed to send your message. Please try again later.";
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
