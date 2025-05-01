<?php
session_start();
include('includes/config.php');
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['sendCode'])) {
    $email = $_POST['email'];

    // Generate OTP
    $otp = rand(100000, 999999);

    // Send OTP via email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bronzetoconqueror03@gmail.com';
        $mail->Password = '';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('bronzetoconqueror03@gmail.com', 'NEWSPORTAL');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body = 'Your OTP is: ' . $otp;

        $mail->send();

        // Store OTP in session
        $_SESSION['otp'] = $otp;

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        exit;
    }
}
