<?php
session_start();
include("../../conexao.php");

require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//Variaveis
$email = filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL);
$nome = filter_input(INPUT_POST,'nome_completo',FILTER_DEFAULT);
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
$save = $payment->save();
if($save == FALSE){
    $_SESSION['transaction_error'] = true;
    header("Location: ../view/mercadopag.php");
    die();
}
else{
    //Variáveis - compra
    //Selecionar codigo comprador
    $code_user = $_SESSION['usuario'];
    $forma_payment = "c";

    //Criptografar token
    $frase_array = str_split(str_replace(' ', '',$token));
    $frase_count = strlen(str_replace(' ', '',$token));
    $j = 2;
    $token_livre = 0;
    for($i = 0; $i < $frase_count; $i++){
        $conta = pow($j, ord($frase_array[$i]) + 12);
        $j +=  5;
        $token_livre += $conta;
    }


    //Insert na tabela compra 
    $cod =$mysqli->query("SELECT cd_carrinho AS cod_car FROM tb_carrinho WHERE nm_inativo = 0 AND cd_usuario = '$code_user'");

    while($codigo_car = $cod->fetch_assoc()){
        //Contador de compra
        $contador_compra = $mysqli->query("SELECT COUNT(cd_compra) AS cod_comp FROM tb_compra");
        $contador_compra = $contador_compra->fetch_assoc();
        $result_compr = $contador_compra['cod_comp'] + 1;

        $cd_car = $codigo_car["cod_car"];
        $mysqli->query("INSERT INTO tb_compra (cd_compra, dt_compra, vl_compra, cd_usuario, cd_carrinho, nm_pagamento, nm_pagante, nm_email, nm_token) VALUES ('$result_compr', NOW(), '$amount', '$code_user', '$cd_car', '$forma_payment', '$nome', '$email', '$token_livre')");
    }

    //Desativar carrinho
    $upd = $mysqli->query("UPDATE tb_carrinho SET nm_inativo = 1 WHERE nm_inativo = 0 AND cd_usuario = '$code_user'");

    $_SESSION['payment'] = $payment;
    $_SESSION['token'] = $token;
    header("Location: ../view/result.php");
    die();
}

?>