<?php

include ("conexao.php");
include ("verifica_login.php");

$data = $_POST['data_envio'];
$codigo = $_POST['codigo'];
$data_compra = $_POST['data_compra'];

$query = $mysqli->query("UPDATE tb_compra SET dt_entrega = '$data' WHERE cd_carrinho = '$codigo' AND dt_compra = '$data_compra'");

header("Location: vendidos.php");
$_SESSION['atualizado'] = true;
die();
?>