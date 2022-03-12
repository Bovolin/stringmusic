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

$frase_array = str_split(str_replace(' ', '',$senha));
$frase_count = strlen(str_replace(' ', '',$senha));
$j = 2;
$crip = 0;

for($i = 0; $i < $frase_count; $i++){
    $conta = pow($j, ord($frase_array[$i]) + 12);
    $j +=  20;
    $crip += $conta;
}

$select = "SELECT nm_email AS nome, nm_senha FROM tb_usuario WHERE nm_email = '$email' AND nm_senha = '$crip'";
$result = $mysqli->query($select);
//conta as linhas (pode usar COUNT(*))
$row = mysqli_num_rows($result);

foreach($mysqli->query("SELECT cd_usuario AS codigo, nm_senha FROM tb_usuario WHERE nm_email = '$email' and nm_senha = '$crip'") as $username){
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