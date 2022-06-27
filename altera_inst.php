<?php

session_start();
include("conexao.php");
include("verifica_login.php");

$codigo = $_POST['codigo'];

if(isset($_POST['alterar'])){
    $nome = $_POST['nm'];
    $valor = $_POST['prc'];
    $descricao = $_POST['desc'];

    $upd = $mysqli->query("UPDATE tb_instrumento SET nm_instrumento = '$nome', vl_instrumento = '$valor', ds_instrumento = '$descricao' WHERE cd_instrumento = '$codigo'");

    $_SESSION['alterado'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif(isset($_POST['remover'])){
    $dlt = $mysqli->query("UPDATE tb_instrumento SET nm_inativo = 1 WHERE cd_instrumento = '$codigo'");

    $_SESSION['removido'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif(isset($_POST['cancelar'])){
    header("Location: meusprodutos.php");
    exit;
}

?>