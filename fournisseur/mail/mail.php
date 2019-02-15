<?php
session_start();
if(!isset($_SESSION["IDFournisseur"])||($_SESSION["IDFournisseur"]===NULL)) header("location:../index.php");
require_once "PHPMailerAutoload.php";

$mail = new PHPMailer;

//Enable SMTP debugging. 
$mail->SMTPDebug = 3;                               
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = "smtp.gmail.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//Provide username and password     
$mail->Username = "ayoubayoub05.96@gmail.com ";                 
$mail->Password = "ayoubayoub05.96.";                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to 
$mail->Port = 587;                                   

$mail->From = "ayoubayoub05.96@gmail.com";
$mail->FromName = $_SESSION["nomdutilisateur"] ;

$mail->addAddress("ayoub2012.96@gmail.com", "islam");

$mail->isHTML(true);
$mail->Subject = $_SESSION["nomdutilisateur"];
$mail->Body = $_POST["message"];
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    header("location: ../interface_fournisseur.php");

} 


?>