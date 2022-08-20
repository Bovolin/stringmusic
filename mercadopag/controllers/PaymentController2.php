<?php
/* Controller de Pagamentos via Boleto */

/* Nota: é aqui onde definitivamente é feito o pagamento via boleto, nesse controller falta a conexão com o MP; 
    Na época eu não trabalhei muito na forma de pagamento por boleto, por estar focado no cartão de crédito. */

//Requerimento das configurações (Access Token e Key)
require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//$payment_methods = MercadoPago\SDK::get(uri: "/v1/payment_methods");

//Cria o informações essenciais do boleto -> essa função está incompleta
$date_of_expiration = NOW();
$amount = filter_input(INPUT_POST,'paymentMethodId',FILTER_DEFAULT);
$description = filter_input(INPUT_POST,'description',FILTER_DEFAULT);
$fname;
$lname;
$email;
$cpf;
$cep;
$rua;
$numero;
$bairro;
$cidade;
$uf;

//Cria o boleto
$payment = new MercadoPago\Payment();
$payment->date_of_expiration = $date_of_expiration;
$payment->transaction_amount = $amount;
$payment->description = $description;
$payment->payment_method_id = "bolbradesco";
$payment->payer = array(
    "email" => $email,
    "first_name" =>  $fnme,
    "last_name" => $lname,
    "identification" => array(
        "type" => "CPF",
        "number" => $cpf
    ),
    "address" => array(
        "zip_code" => $cep,
        "street_name" => $rua,
        "street_number" => $numero,
        "neighborhood" => $bairro,
        "city" => $cidade,
        "federal_unit" => $uf
    )
);
//Salva o boleto
$payment->save();
//Redireciona o usuário para a página do boleto onde pode imprimir ou baixar
header("Location: ". $payment->transaction_details->external_resource_url);
die();
?>