<?php
session_start();
include ("conexao.php");

$nome_email = $_POST['nome'];
$gmail_email = $_POST['email'];
$telefone_email = $_POST['telefone'];
$titulo_email = $_POST['titulo'];
$mensagem_email = $_POST['mensagem'];

require_once('email/PHPMailer.php');
require_once('email/SMTP.php');
require_once('email/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try{
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true; // Alterar para true
    $mail->Username = 'strmsc937@gmail.com'; //Substituir pelo email
    $mail->Password = 'Melancia55'; //Substituir pela senha do email
    $mail->Port = 587;

    $mail->setFrom('strmsc937@gmail.com');
    $mail->addAddress('strmsc937@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = $titulo_email;
    $mail->Body = 'Enviado de: ' . $nome_email . '<br>' . 'Email: ' . $gmail_email . '<br>' . 'Telefone: ' . $telefone_email . '<br>' . 'Mensagem: ' . $mensagem_email;

    if($mail->send()){
        $_SESSION['email_enviado'] = true;
        header ("Location: contato.php");
        die();
    }
    else{
        $_SESSION['email_recusado'] = true;
        header ("Location: contato.php");
        die(); 
    }
}
catch (Exception $e){
    $_SESSION['email_recusado'] = true;
    header ("Location: contato.php");
    die(); 
}

?>