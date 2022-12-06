<?php

// Import PHPMailer classes at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function portalesEmail($sendee,$subject,$message){
    // Load Composer's autoloader
    // Instantiation
    $mail = new PHPMailer(true);
    // Server settings
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure= 'tls';
    $mail->Port = 587;
    $mail->Username = 'add here an email to send email';
    $mail->Password = 'add here email to send email';
    // Sender &amp; Recipient
    $mail->From = 'email';
    $mail->FromName = 'Los Portales Theatre';
    $mail->addAddress($sendee);
    // Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Subject = $subject;
    $body = $message;
    $mail->Body = $body;
    if($mail->send()){
            //nothing, continue
    }else{
    echo 'Error sending e-mail. Please contact the administrator if error persist.';
    exit;
    }
}






