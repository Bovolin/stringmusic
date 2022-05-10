<?php

include ("conexao.php");
include ("verifica_login.php");

//Interpretação
$data_int = $_POST['data_envio_int'];
$codigo_int = $_POST['codigo_int'];
$data_compra_int = $_POST['data_compra_int'];

//Instrumento
$data_ins = $_POST['data_envio_ins'];
$codigo_ins = $_POST['codigo_ins'];
$data_compra_ins = $_POST['data_compra_ins'];

//Serviço
$data_ser = $_POST['data_envio_ser'];
$codigo_ser = $_POST['codigo_ser'];
$data_compra_ser = $_POST['data_compra_ser'];

if($data_int != null)
    $query = $mysqli->query("UPDATE tb_compra SET dt_entrega = '$data_int' WHERE cd_carrinho = '$codigo_int' AND dt_compra = '$data_compra_int'");
elseif($data_ins != null)
    $query = $mysqli->query("UPDATE tb_compra SET dt_entrega = '$data_ins' WHERE cd_carrinho = '$codigo_ins' AND dt_compra = '$data_compra_ins'");
elseif($data_ser != null)
    $query = $mysqli->query("UPDATE tb_compra SET dt_entrega = '$data_ser' WHERE cd_carrinho = '$codigo_ser' AND dt_compra = '$data_compra_ser'");

header("Location: vendidos.php");
$_SESSION['atualizado'] = true;
die();
?>