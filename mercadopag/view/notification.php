<?php

include("../config/config.php");
MercadoPago\SDK::setAccessToken(SAND_TOKEN);
$payment = MercadoPago\Payment::find_by_id($_GET['data_id']);
$payment->{'status'};

$fp = fopen('log.txt', 'a');
$html = '';
foreach($_GET as $key => $value){
    $html .= $key . '=>' . $value . ' | ';
}
$fwrite = fwrite($fp, $html);
fclose($fp);

?>