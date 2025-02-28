<?php
require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Ensure code is set, otherwise generate a new one
if (!isset($_SESSION['code'])) {
    $_SESSION['code'] = rand(100000, 999999); // Generate a 6-digit random code
}

$code = $_SESSION['code'];

// Function to send email
function sendcode($email, $subject, $code) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'codergirlg3@gmail.com';
        $mail->Password   = 'tqde jvjb kqwt quuh'; // Use a secure storage method
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('codergirlg3@gmail.com', 'care');
        $mail->addAddress($email);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "Your verification code is: <b>" . htmlspecialchars($code) . "</b>";

        // Send Email
        if ($mail->send()) {
            echo "Message has been sent with code: $code";
        } else {
            echo "Failed to send email.";
        }
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage
//sendcode("recipient@example.com", "Your Verification Code","123456");
?>
