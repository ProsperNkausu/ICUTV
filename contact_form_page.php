<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = 'Invalid email address.';
    } else {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // //Server settings
            // $mail->isSMTP();                                      // Set mailer to use SMTP
            // $mail->Host = 'smtp.example.com';                     // Specify main and backup SMTP servers
            // $mail->SMTPAuth = true;                               // Enable SMTP authentication
            // $mail->Username = 'your_username';                    // SMTP username
            // $mail->Password = 'your_password';                    // SMTP password
            // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            // $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('chichimuzimo@gmail.com');          // Add a recipient
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(false);                                 // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

            $mail->send();
            $response = 'Message sent successfully!';
        } catch (Exception $e) {
            $response = 'Failed to send the message. Please try again later.';
            error_log('Mail error: ' . $mail->ErrorInfo);
        }
    }

    // Redirect back to the form with a message
    header('Location: contact.php?response=' . urlencode(htmlspecialchars($response, ENT_QUOTES, 'UTF-8')));
    exit();
}
?>
