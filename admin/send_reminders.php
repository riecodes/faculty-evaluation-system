<?php
session_start();
require_once '../assets/plugins/phpmailer/src/PHPMailer.php';
require_once '../assets/plugins/phpmailer/src/SMTP.php';
require_once '../assets/plugins/phpmailer/src/Exception.php';
require_once '../db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 1) {
    header("Location: ../login.php");
    exit;
}

// Load mail configuration
$mail_config = require_once '../config/mail_config.php';

$academic_id = $_SESSION['academic']['id'];
$success = true;
$sent_count = 0;
$errors = [];

// Get all pending evaluations
$pending_query = $conn->query("SELECT 
    s.id as student_id,
    s.email,
    CONCAT(s.firstname, ' ', s.lastname) as student_name,
    CONCAT(c.curriculum, ' ', c.level, '-', c.section) as class,
    CONCAT(f.firstname, ' ', f.lastname) as faculty_name,
    sub.subject,
    sub.code as subject_code
FROM student_list s
INNER JOIN class_list c ON c.id = s.class_id
INNER JOIN restriction_list r ON r.class_id = c.id
INNER JOIN faculty_list f ON f.id = r.faculty_id
INNER JOIN subject_list sub ON sub.id = r.subject_id
WHERE r.academic_id = {$academic_id}
AND r.id NOT IN (
    SELECT restriction_id 
    FROM evaluation_list 
    WHERE academic_id = {$academic_id} 
    AND student_id = s.id
)");

while($student = $pending_query->fetch_assoc()) {
    if(!empty($student['email'])) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $mail_config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mail_config['username'];
            $mail->Password = $mail_config['password'];
            $mail->SMTPSecure = $mail_config['encryption'];
            $mail->Port = $mail_config['port'];

            // Recipients
            $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
            $mail->addAddress($student['email'], $student['student_name']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reminder: Faculty Evaluation Pending';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background-color: #183f74; color: white; padding: 20px; text-align: center;'>
                        <h2>Faculty Evaluation Reminder</h2>
                    </div>
                    <div style='padding: 20px;'>
                        <p>Dear {$student['student_name']},</p>
                        <p>This is a reminder that you have pending faculty evaluations for the following:</p>
                        <ul>
                            <li><strong>Faculty:</strong> {$student['faculty_name']}</li>
                            <li><strong>Subject:</strong> {$student['subject_code']} - {$student['subject']}</li>
                            <li><strong>Class:</strong> {$student['class']}</li>
                        </ul>
                        <p>Please complete your evaluation at your earliest convenience. Your feedback is important for the continuous improvement of our faculty members.</p>
                        <p>You can access the evaluation system through your student portal.</p>
                    </div>
                    <div style='text-align: center; padding: 20px; font-size: 12px; color: #666;'>
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            ";

            $mail->send();
            $sent_count++;
        } catch (Exception $e) {
            $error_msg = "Failed to send email to {$student['email']}. Error: {$mail->ErrorInfo}";
            $errors[] = $error_msg;
            $success = false;
        }
    }
}

// Redirect back to report page with status
$status = $success ? 'success' : 'error';
$message = $success ? 
    "Reminders sent successfully! {$sent_count} emails sent." : 
    "Error sending reminders: " . implode(", ", $errors);

header("Location: ../index.php?page=report&status={$status}&message=" . urlencode($message));
exit; 