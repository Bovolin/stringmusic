<?php

require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

$amount = filter_input(INPUT_POST,'paymentMethodId',FILTER_DEFAULT);
$description = filter_input(INPUT_POST,'description',FILTER_DEFAULT);
$email;
$fname;
$lname;
$cpf;
$cep;
$rua;
$numero;
$bairro;
$cidade;
$uf;

$payment = new MercadoPago\Payment();
$payment->transaction_amount = $amount;
$payment->description = $description;
$payment->payment_method_id = "pix";
$payment->payer = array(
    "email" => $email,
    "first_name" => $fname,
    "last_name" => $lname,
    "identification" => array(
        "type" => "CPF",
        "number" => $cpf
    ),
    "address"=>  array(
        "zip_code" => $cep,
        "street_name" => $rua,
        "street_number" => $numero,
        "neighborhood" => $bairro,
        "city" => $cidade,
        "federal_unit" => $uf
    )
);

$payment->save();

#https://codepen.io/hannahtsou/pen/pRBvaN

echo "<img style='width: 300px; height: 300px' src='data:image/png;base64, " . $payment->point_of_interaction->transaction_data->qr_code_base64 . "' alt='Pix'>"
?>