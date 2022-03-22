<?php

require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//$payment_methods = MercadoPago\SDK::get(uri: "/v1/payment_methods");

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

$payment = new MercadoPago\Payment();
$payment->date_of_expiration = "2019-06-30T21:52:49.000-04:00";
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
$payment->save();
header("Location: ". $payment->transaction_details->external_resource_url);
?>