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

    $upd = $mysqli->query("UPDATE tb_interpretacao SET vl_interpretacao = '$valor', ds_interpretacao = '$descricao' WHERE cd_interpretacao = '$codigo'");

    $_SESSION['alterado'] = true;
    header("Location: meusprodutos.php");
    exit;
}
elseif($_POST['remover']){
    $dlt = $mysqli->query("UPDATE tb_interpretacao SET nm_inativo = 1 WHERE cd_interpretacao = '$codigo'");

    $_SESSION['removido'] = true;
    header("Location: meusprodutos.php");
    exit;
}

?>