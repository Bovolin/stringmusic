<?php

require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//$payment_methods = MercadoPago\SDK::get(uri: "/v1/payment_methods");

$payment = new MercadoPago\Payment();
$payment->date_of_expiration = "2019-06-30T21:52:49.000-04:00";
$payment->transaction_amount = 100;
$payment->description = "Titulo";
$payment->payment_method_id = "bolbradesco";
$payment->payer = array(
    "email" => "test_user_19653727@testuser.com",
    "first_name" => "Test",
    "last_name" => "User",
    "identification" => array(
        "type" => "CPF",
        "number" => "19119119100"
    ),
    "address" => array(
        "zip_code" => "06233200",
        "street_name" => "Av. das Nações Unidas",
        "street_number" => "3003",
        "neighborhood" => "Bonfim",
        "city" => "Osasco",
        "federal_unit" => "SP"
    )
);
$payment->save();
header("Location: ". $payment->transaction_details->external_resource_url);
?>