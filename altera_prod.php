<?php

session_start();
include("conexao.php");
include("verifica_login.php");

$codigo = $_POST['codigo'];

if($_POST['alterar'] != "") $altera = 1;
else $altera = 0;
if($_POST['remover'] != "") $remove = 1;
else $remove = 0;

if($altera = 1){
    $valor = $_POST['prc'];
    $descricao = $_POST['desc'];

    $upd = $mysqli->query("UPDATE tb_interpretacao SET vl_interpretacao = '$valor', ds_interpretacao = '$descricao' WHERE cd_interpretacao = '$codigo'");

    $_SESSION['alterado'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif($remove = 1){
    $dlt = $mysqli->query("UPDATE tb_interpretacao SET nm_inativo = 1 WHERE cd_interpretacao = '$codigo'");

    $_SESSION['removido'] = true;
    header("Location: meusprodutos.php");
    exit;
}

?>