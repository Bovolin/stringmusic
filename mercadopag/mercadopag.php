<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado Pago</title>
</head>
<body>
    <form action="controllers/PaymentController.php" method="post" id="pay" name="pay">
        <fieldset>
            <ul>
                <li>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </li>
                <li>
                    <label for="cardNumber">Número do Cartão</label>
                    <input type="text" id="cardNumber" data-checkout="cardNumber">
                    <div class="brand"></div>
                </li>
                <li>
                    <label for="securityCode">Código de Segurança</label>
                    <input type="text" id="securityCode" data-checkout="securityCode">
                </li>
                <li>
                    <label for="cardExperiationYear">Data de Expiração</label>
                    <input type="text" id="cardExperiationYear" data-checkout="cardExperiationYear">
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
                    <label for="docNumber">Número do Documento</label>
                    <select id="docNumber" data-checkout="docNumber"></select>
                </li>
                <li>
                    <label for="installments">Parcelas</label>
                    <select id="installments" class="form-control" name="installments"></select>
                </li>
            </ul>
            <input type="hidden" name="amount" id="amount" value="120.00">
            <input type="hidden" name="description">
            <input type="hidden" name="paymentMethodId">
            <input type="submit" value="Pagar!">
        </fieldset>
    </form>
    
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="mercadopag.js"></script>
</body>
</html>