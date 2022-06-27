<?php

session_start();
include("conexao.php");
include("verifica_login.php");

$codigo = $_POST['codigo'];

if(isset($_POST['alterar'])){
    $nome = $_POST['nm'];
    $valor = $_POST['prc'];
    $descricao = $_POST['desc'];

    $upd = $mysqli->query("UPDATE tb_servico SET nm_servico = '$nome', vl_servico = '$valor', ds_servico = '$descricao' WHERE cd_servico = '$codigo'");

    $_SESSION['alterado'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif(isset($_POST['remover'])){
    $dlt = $mysqli->query("UPDATE tb_servico SET nm_inativo = 1 WHERE cd_servico = '$codigo'");

    $_SESSION['removido'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif(isset($_POST['cancelar'])){
    header("Location: meusprodutos.php");
    exit;
}

?>