<?php
require("../../lib/vendor/autoload.php");
$carrinho = new \Classes\ClassCarrinho();
$exception = new \Classes\ClassException();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../js/swal.js"></script>
    <title>Mercado Pago</title>
</head>
<style>
    .result{display: grid; width: 100%; justify-items: center;}
        .success{width: 50%; background: #77c563; border-radius: 5px; padding: 10px; text-align: center;}
        .alert{width: 50%; background: #ff544d; border-radius: 5px; padding: 10px; text-align: center;}
        .error{width: 50%; background: #ffd809; border-radius: 5px; padding: 10px; text-align: center;}
</style>
<?php 
$exception->setPayment($_SESSION['payment']);

if($exception->verifyTransaction()['class'] == 'error') $icone = 'error';
elseif($exception->verifyTransaction()['class'] == 'success') $icone = 'success';
elseif($exception->verifyTransaction()['class'] == 'alert') $icone = 'info';
?>
<script>
    function teste(){
        Swal.fire({
            icon: '<?php echo $icone ?>',
            text: '<?php echo $exception->verifyTransaction()['message'] ?>'
        })
    }
</script>
<body onload="teste()">

    <!--<div class="result">
        <?php //$exception->setPayment($_SESSION['payment']); ?>
        <div class="<?php //echo $exception->verifyTransaction()['class']; ?>">
            <?php //echo $exception->verifyTransaction()['message']; ?>
        </div>
    </div>-->
        
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="../mercadopag.js"></script>
</body>
</html>