<?php

session_start();
include("conexao.php");
include("verifica_login.php");

$codigo = $_POST['codigo'];

echo $altera;
echo $remove;

if($_POST['alterar']){
    $valor = $_POST['prc'];
    $descricao = $_POST['desc'];

    $upd = $mysqli->query("UPDATE tb_servico SET vl_servico = '$valor', ds_servico = '$descricao' WHERE cd_servico = '$codigo'");

    $_SESSION['alterado'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif($_POST['remover']){
    $dlt = $mysqli->query("UPDATE tb_servico SET nm_inativo = 1 WHERE cd_servico = '$codigo'");

    $_SESSION['removido'] = true;
    header("Location: meusprodutos.php");
    exit;
}

?>