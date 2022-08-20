<?php
/* Controller de Pagamentos via PIX */

/* Nota: é aqui onde definitivamente é feito o pagamento via PIX, nesse controller falta a conexão com o MP; 
    Na época eu não trabalhei muito na forma de pagamento por PIX também, por estar focado no cartão de crédito. */

//Requerimento das configurações (Access Token e Key)
require('../config/config.php');
// Carregar Autoload do composer
require('../../lib/vendor/autoload.php');
MercadoPago\SDK::setAccessToken( access_token: SAND_TOKEN);

//Variáveis para o pix
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

//Cria o QRCode do PIX
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
//Salva o código pix
$payment->save();

#https://codepen.io/hannahtsou/pen/pRBvaN

//Aqui é feito o html da página para exibir o QRCode do PIX, como não trabalhei nessa função apenas exibi a imagem do QRCode
echo "<img style='width: 300px; height: 300px' src='data:image/png;base64, " . $payment->point_of_interaction->transaction_data->qr_code_base64 . "' alt='Pix'>"
?>