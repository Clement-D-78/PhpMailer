<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

if (isset($_POST['submit'])) {
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $subject = htmlspecialchars(strip_tags($_POST['subject']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(strip_tags($_POST['message']));

    if (!$email) {
        header("Location: ../index.php?error=invalidemail");
        exit();
    }
    
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // A remplacer par ton hôte SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'mail@gmail.com'; // Ton adresse email
        $mail->Password = 'mdpMail'; // Ton mot de passe email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;


        $mail->setFrom($email, $name); // Expediteur
        $mail->addAddress('mail@gmail.com'); // Destinataire

        $mail->isHTML(true); // Permet d'utiliser le format HTML
        $mail->Subject = $subject;
        $mail->Body = nl2br("You have received a message from $name.<br><br>" . $message);
        $mail->AltBody = "You have received a message from $name.\n\n" . $message;

        $mail->send();
        header("Location: ../index.php?mailsend");
    } catch (Exception $e) {
        header("Location: ../index.php?error=mailerror&message=" . urlencode($mail->ErrorInfo));
    }
    exit();
}
?>
