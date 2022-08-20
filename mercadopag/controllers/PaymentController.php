<?php

/* Controller de Pagamentos via Cartão de Crédito */

/* Nota: é aqui onde definitivamente é feito o pagamento, nesse controller falta a conexão com o MP. */

//Inicia sessão
session_start();
//Inclui a conexão com o banco
include("../../conexao.php");

//Requerimento das configurações (Access Token e Key)
require('../config/config.php');

// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
//Classe do MercadoPago via SDK
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//Variaveis vindas do mercadopag.php
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

//Metódo para fazer pagamento
$payment = new MercadoPago\Payment();
//Define o total da compra
$payment->transaction_amount = $amount;
//Define o token de exclusividade
$payment->token = $token;
//Define a descrição do produto
$payment->description = $description;
//Define as parcelas
$payment->installments = $installments;
//Define o método de pagamento
$payment->payment_method_id = $paymentMethodId;
//Define o email de quem paga
$payment->payer = array(
    "email" => $email
);
//Salva o pagamento (aqui é onde ocorre o pagamento da API)
$save = $payment->save();
//Verificação para caso dê erro na hora de salvar
if($save == FALSE){
    $_SESSION['transaction_error'] = true;
    header("Location: ../view/mercadopag.php");
    die();
}
else{
    //Inserção da compra no banco

    //Selecionar codigo comprador
    $code_user = $_SESSION['usuario'];
    //Forma de pagamento "c" de Cartão de Crédito
    $forma_payment = "c";

    //Criptografar token

    /* 
        Nota de Criptografia: 
        Na época em que fiz essa criptografia foi para testar a fatoração dos Números de Gödel;
        Até hoje não consegui uma forma de descriptografar isso que eu fiz, ou seja, é uma criptografia de mão única, por isso recomendo
        usar um hash comum. 
    */
    
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

    //Cria sessões de pagamento e token para mostrar para o usuário na página de resultados
    $_SESSION['payment'] = $payment;
    $_SESSION['token'] = $token;
    //Redireciona para a página de resultado (compra aprovada ou não)
    header("Location: ../view/result.php");
    die();
}

?>