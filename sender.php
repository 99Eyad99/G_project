<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'mailer/vendor/autoload.php';

require_once 'mailer/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once 'mailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';





function sendForActivate($to , $subject , $body , $link){

$mail = new PHPMailer(true);


//$mail->isSMTP();
$mail->host = 'smtp.gmail.com';
$mail->SMTPAuth=true;
$mail->SMTPSecure = 'tls';
$mail->SMTPDebug = 0;
$mail->SMTPAutoTLS = false;
$mail->Port = '587';



$mail->username = 'swfoffical@gmail.com';
$mail->passowrd = 'SWF@99admin$$';


$mail->setFrom('swfoffical@gmail.com');
$mail->addAddress($to);
$mail->AddReplyTo('swfoffical@gmail.com');


$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body = $body.' '.$link;


if($mail->send()){
    return 1;
}else{
    return 0;
}



}










?>