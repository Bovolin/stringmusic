<?php
session_start();
include ("conexao.php");

//Verificação antes de logar
if(empty($_POST['emaillogar']) || empty($_POST['senhalogar'])){
    header ('Location: login.php');
    exit();
}

//Evitando mysql injection
$email = mysqli_real_escape_string($mysqli, $_POST['emaillogar']);
$senha = mysqli_real_escape_string($mysqli, $_POST['senhalogar']);

$select = "SELECT nm_email AS nome, nm_senha FROM tb_usuario WHERE nm_email = '$email' AND nm_senha = md5('$senha')";
$result = $mysqli->query($select);
//conta as linhas (pode usar COUNT(*))
$row = mysqli_num_rows($result);

foreach($mysqli->query("SELECT cd_usuario AS codigo, nm_senha FROM tb_usuario WHERE nm_email = '$email' and nm_senha = md5('$senha')") as $username){
    $codigo = $username['codigo'];
}

if($row == 1){
    $_SESSION['usuario'] = $codigo;
    header ('Location: painel.php');
    exit;
}
else{
    $_SESSION['nao_usuario'] = true;
    header ('Location: login.php');
    exit;
}

?>