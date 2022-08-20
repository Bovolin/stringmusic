<?php
//Requerimento do composer
require("../../lib/vendor/autoload.php");
//Conexão com o banco
include("../../conexao.php");
//Instancia um no exception da classe Exceptions
$exception = new \Classes\ClassException();
//Inicia sessão
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styleloja.css">
    <title>StringMusic</title>
</head>
<?php
//Exibe os erros
$exception->setPayment($_SESSION['payment']);

if($exception->verifyTransaction()['class'] == 'error') $icone = 'error';
elseif($exception->verifyTransaction()['class'] == 'success') $icone = 'success';
elseif($exception->verifyTransaction()['class'] == 'alert') $icone = 'info';

$token_compra = $_SESSION['token'];

//Criptografar token
$frase_array = str_split(str_replace(' ', '',$token_compra));
$frase_count = strlen(str_replace(' ', '',$token_compra));
$j = 2;
$token_livre = 0;
for($i = 0; $i < $frase_count; $i++){
    $conta = pow($j, ord($frase_array[$i]) + 12);
    $j +=  5;
    $token_livre += $conta;
}

//Pega a data da compra
foreach($mysqli->query("SELECT dt_compra as data_compra from tb_compra where nm_token = '$token_livre' limit 1") as $infos_compra){
    $data = $infos_compra['data_compra'];
}

?>
<body>
    <div class="payment-group">
        <?php
        //Exibe as mensagens de exception
        if($icone == 'error') echo '<img class="payment-ticket-img" src="../../imgs/ticket-error.png" alt="imagem com um X">';
        elseif($icone == 'success') echo '<img class="payment-ticket-img" src="../../imgs/ticket.png" alt="imagem com um correto">';
        elseif($icone == 'info') echo '<img class="payment-ticket-img" src="../../imgs/ticket-error.png" alt="imagem com um X">';
        ?>
            <h2><?php echo $exception->verifyTransaction()['message'] ?></h2>
            <div class="payment-ticket">
                <p>Código da compra: <?php echo $token_compra ?></p>
            </div>  
        <div class="payment-info">
            <p>Data da compra: <?php echo $data ?></p>
        </div>
        <div class="payment-btn">
            <form action="../../destruirsession.php" method="post">
                <input type="submit" class="btn" name="c" value="Continuar">
                <input type="submit" class="btn" name="v" value="Ver Produto">
            </form>
        </div>
    </div>

<!-- Script do MP -->    
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="../mercadopag.js"></script>
</body>
</html>