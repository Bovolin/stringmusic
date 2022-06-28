<?php
session_start();
include ("conexao.php");

$nome_email = $_POST['nome'];
$gmail_email = $_POST['email'];
$telefone_email = $_POST['telefone'];
$titulo_email = $_POST['titulo'];
$mensagem_email = $_POST['mensagem'];
$data = date("d/m/Y");
$hora = date("H:i:s");

$arquivo = "
    <html>
        <p><b>Nome: </b>$nome_email</p>
        <p><b>Email: </b>$gmail_email</p>
        <p><b>Mensagem: </b>$mensagem_email</p>
        <p>Este email foi enviado em $data às $hora</p>
    </html>
";

$destino = "stringmsc7@gmail.com";
$assunto = "$titulo_email";

$headers = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
$headers .= "From: $nome_email <$gmail_email>";

mail($destino, $assunto, $arquivo, $headers);

header("Location: index.php")
?>