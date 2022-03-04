<?php
session_start();

include("conexao.php");

require_once('email/PHPMailer.php');
require_once('email/SMTP.php');
require_once('email/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

if(isset($_POST['enviar'])){
    $email = $_POST['email'];

    $slt = $mysqli->query("SELECT nm_senha AS senha, cd_usuario AS codigo FROM tb_usuario WHERE nm_senha = '$email'");
    $slt = $slt->fetch_assoc();

    if($slt == 0){
        $_SESSION['email_recusado'] = true;
        header("Location: login.php");
        die();
    }
    else{
        if(count($erro) == 0 && $slt > 0){
            $nova_senha = substr(md5(time()), 0, 6);
            $nova_senha_cript = md5($nova_senha);
        
                try{
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true; // Alterar para true
                    $mail->Username = '#@gmail.com'; //Substituir pelo email
                    $mail->Password = '#'; //Substituir pela senha do email
                    $mail->Port = 587;
                
                    $mail->setFrom('#@gmail.com');
                    $mail->addReplyTo('no-reply@gmail.com.br');
                    //Só funcionará quando hospedar
                    $mail->addAddress($email);
                
                    $mail->isHTML(true);
                    $mail->Subject = 'Troca de senha String;Music';
                    $mail->Body = 'Houve uma requisição para troca de senha na plataforma String;Music. <br> Sua nova senha é: ' . $nova_senha;
                
                    if($mail->send()){
                        $upd = $mysqli->query("UPDATE tb_usuario SET nm_senha = '$nova_senha_cript' WHERE nm_email = '$email'");
                        $_SESSION['email_enviado'] = true;
                        header ("Location: login.php");
                        die();
                    }
                    else{
                        $_SESSION['email_recusado'] = true;
                        header ("Location: login.php");
                        die(); 
                    }
                }
                catch (Exception $e){
                    $_SESSION['email_recusado'] = true;
                    header ("Location: login.php");
                    die(); 
                }
            }
            else{
                $_SESSION['email_recusado'] = true;
                header("Location: login.php");
                die();
            }
        }
    }   
else{
    $_SESSION['email_recusado'] = true;
    header("Location: login.php");
    die();
}
?>