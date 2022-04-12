<?php
require("../../lib/vendor/autoload.php");
session_start();
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
<?php 
$exception->setPayment($_SESSION['payment']);

if($exception->verifyTransaction()['class'] == 'error') $icone = 'error';
elseif($exception->verifyTransaction()['class'] == 'success') $icone = 'success';
elseif($exception->verifyTransaction()['class'] == 'alert') $icone = 'info';
?>
<script>
    function swal(){
        Swal.fire({
            icon: '<?php echo $icone ?>',
            text: '<?php echo $exception->verifyTransaction()['message'] ?>',
            confirmButtonColor: '#32cd32',
            confirmButtonText: 'Prosseguir'
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href= "mercadopag.php";
            }
        })
    }
</script>
<body onload="swal()">
        
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="../mercadopag.js"></script>
</body>
</html>