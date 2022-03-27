<?php
session_start();
include("../../conexao.php");

$compra = new \Compra\ClassCompra();

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
}*/

$_SESSION['payment'] = $payment;
header("Location: ../view/result.php");
# unset($_SESSION['id_interpretacao']);
?>