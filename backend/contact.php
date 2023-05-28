<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to PHPMailer autoloader

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Email recipient
    $to = 'your-email@example.com'; // Replace with your own email address

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io'; // Replace with your SMTP host
        $mail->Port = 2525; // Replace with your SMTP port
        $mail->SMTPAuth = true;
        $mail->Username = 'your-username'; // Replace with your SMTP username
        $mail->Password = 'your-password'; // Replace with your SMTP password

        // Sender and recipient
        $mail->setFrom($email, $name);
        $mail->addAddress($to);

        // Email content
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "Name: $name\n\n";
        $mail->Body .= "Email: $email\n\n";
        $mail->Body .= "Message:\n$message";

        // Send email
        $mail->send();

        // Display success message
        echo "<script>alert('Thank you for your message. We will get back to you soon.');</script>";
    } catch (Exception $e) {
        // Display error message
        echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
    }
}
?>
