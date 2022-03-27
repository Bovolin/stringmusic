<?php
session_start();
include("../../conexao.php");

require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//Variaveis
$email = filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL);
$cardNumber = filter_input(INPUT_POST, 'cardNumber', FILTER_DEFAULT);
$securityCode = filter_input(INPUT_POST, 'securityCode', FILTER_DEFAULT);
$cardExpirationMonth = filter_input(INPUT_POST,'cardExpirationMonth', FILTER_DEFAULT);
$cardExpirationYear = filter_input(INPUT_POST,'cardExpirationYear', FILTER_DEFAULT);
$cardholderName = filter_input(INPUT_POST,'cardholderName', FILTER_DEFAULT);
$docType = filter_input(INPUT_POST,'docType', FILTER_DEFAULT);
$installments = filter_input(INPUT_POST,'installments', FILTER_DEFAULT);
$amount = filter_input(INPUT_POST,'amount', FILTER_DEFAULT);
$description = filter_input(INPUT_POST,'description', FILTER_DEFAULT);
$paymentMethodId = filter_input(INPUT_POST,'paymentMethodId', FILTER_DEFAULT);
$token = filter_input(INPUT_POST,'token', FILTER_DEFAULT);

//Metódo
$payment = new MercadoPago\Payment();
$payment->transaction_amount = $amount;
$payment->token = $token;
$payment->description = $description;
$payment->installments = $installments;
$payment->payment_method_id = $paymentMethodId;
$payment->payer = array(
    "email" => $email
);
$payment->save();

//Selecionar codigo comprador
/*foreach($mysqli->query("SELECT cd_usuario AS codigo FROM tb_usuario WHERE nm_email = '$email'") as $seleciona){
    $codigo_usuario = $seleciona['codigo'];
}

//Contador da tabela compras
$select_compra = $mysqli->query("SELECT COUNT(cd_compra) AS compra FROM tb_compra");
$select_compra = $select_compra->fetch_assoc();
$contador_compra = $select_compra['compra'] + 1;

$insert_compra = $mysqli->query("INSERT INTO tb_compra (cd_compra, dt_compra, vl_compra, cd_usuario) VALUES ('$contador_compra', NOW(), '$amount', '$codigo_usuario')");

//Contador de itens
$select_itens = $mysqli->query("SELECT COUNT(cd_item) AS item FROM tb_item");
$select_itens = $select_itens->fetch_assoc();
$contador_itens = $select_itens['item'] + 1;

//Inserir item
$insert_item = $mysqli->query("INSERT INTO tb_item (cd_item, cd_compra, 
                                cd_interpretacao, cd_instrumento, cd_servico)
                                VALUES ('$contador_itens', '$contador_compra', 
                                '', '', '')");*/

$_SESSION['payment'] = $payment;
header("Location: ../view/result.php");
# unset($_SESSION['id_interpretacao']);
?>