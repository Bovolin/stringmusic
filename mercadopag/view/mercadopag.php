<?php
require("../../lib/vendor/autoload.php");
$carrinho = new \Classes\ClassCarrinho();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado Pago</title>
</head>
<body>

    <div>
        <table style="width: 80%; margin: 30px 10%; text-align: center;">
            <thead>
                <tr style="background: #333; font-weight: bold; color: #fff">
                    <th style="padding: 7px; 0;">ID</th>
                    <th style="padding: 7px; 0;">Descrição</th>
                    <th style="padding: 7px; 0;">Preço</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    echo $carrinho->listProducts();
                ?>
            </tbody>
        </table>
    </div>

    <form action="controllers/PaymentController.php" method="post" id="pay" name="pay">
        <fieldset>
            <h2>Você possui <?php echo $carrinho->getQuantity();?> produto(s) no carrinho</h2>
            <ul>
                <li>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="test_user_19653727@testuser.com">
                </li>
                <li>
                    <label for="cardNumber">Número do Cartão</label>
                    <input type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off>
                    <div class="brand"></div>
                </li>
                <li>
                    <label for="securityCode">Código de Segurança</label>
                    <input type="text" id="securityCode" data-checkout="securityCode">
                </li>
                <li>
                    <label for="cardExpirationMonth">Mês de Expiração</label>
                    <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth">
                </li>
                <li>
                    <label for="cardExpirationYear">Ano de Expiração</label>
                    <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear">
                </li>
                <li>
                    <label for="cardholderName">Titular do Cartão</label>
                    <input type="text" id="cardholderName" data-checkout="cardholderName">
                </li>
                <li>
                    <label for="docType">Documento</label>
                    <select id="docType" data-checkout="docType"></select>
                </li>
                <li>
                    <!--
                        4235647728025682
                        123
                        11/25
                     -->
                    <label for="docNumber">Número do Documento</label>
                    <input type="text" id="docNumber" data-checkout="docNumber">
                </li>
                <li>
                    <label for="installments">Parcelas</label>
                    <select id="installments" class="form-control" name="installments"></select>
                </li>
            </ul>
            <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
            <input type="hidden" name="description" value="Instrumento Bom">
            <input type="hidden" name="paymentMethodId">
            <input type="submit" value="Pagar!">
            
            <a href="https://localhost/stringmusic/mercadopag/controllers/CarrinhoController.php?action=clear">Esvaziar Carrinho</a>
        </fieldset>
    </form>
    
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="mercadopag.js"></script>
</body>
</html>