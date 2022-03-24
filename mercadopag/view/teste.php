<?php
require("../../lib/vendor/autoload.php");
$carrinho = new \Classes\ClassCarrinho();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>StringMusic</title>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../../css/stylepagamento.css"/>
    <script src="../../js/script.js"></script>
</head>
<body>
    <div class='container'>
        <div class='window'>
          <div class='order-info'>
            <div class='order-info-content'>
              <h2>Carrinho</h2>
              <?php if($carrinho->getQuantity() != 0) echo "<div class='line'></div>"; ?>
              <table class='order-table'>
                <tbody>
                  <?php
                    echo $carrinho->listProducts();
                  ?>
                </tbody>
              </table>           
              <div class='line'></div>
              <div class='total'>
                <span style='float:left;'>
                  TOTAL
                </span>
                <span style='float:right; text-align:right;'>
                  R$<?php echo $carrinho->getAmount(); ?>
                  <p>Você possui <?php echo $carrinho->getQuantity(); ?> produto(s) no carrinho</p>
                  <a href="https://localhost/stringmusic/mercadopag/controllers/CarrinhoController.php?action=clear"><i class="fas fa-trash"></i> Esvaziar Carrinho</a>
                </span>
              </div>
            </div>
          </div>
              <div class='credit-info'>
                <div class='credit-info-content'>
                  <select name="select-payment" id="select-payment" class="dropdown-btn" onchange="payment(this)">  
                    <option value="payment-cartao">Cartão de Crédito</option>
                    <option value="payment-boleto">Boleto Bancário</option>
                    <option value="payment-pix">PIX</option>
                  </select>
                  <div id="pai">
                    <div id="payment-cartao" class="selecionado">
                      <form action="../controllers/PaymentController.php" method="post">
                        <div class="brand" style="display: none;"></div>
                        <br>    
                        <img alt="Bandeira Cartão" style="width: 250px; height:100px; margin-left: 20%" id="bandeira">
                        <br>
                        <label for="name">Nome</label>
                        <input type="text" id="name" name="name" class='input-field'>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class='input-field'>
                        <label for="cardNumber">Número do cartão</label>
                        <input type="text" class='input-field' id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off>
                        <table class='half-input-table'>
                          <tr>
                            <td>
                              Validade (mm/aa)
                              <input type="text" class='input-field'>
                            </td>
                            <td>
                              CVV
                              <input type="text" class='input-field'>
                            </td>
                          </tr>
                        </table>
                        <button class='pay-btn'>Comprar</button>
                        <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
                        <input type="hidden" name="description" value="Instrumento Bom">
                        <input type="hidden" name="paymentMethodId">
                      </form>
                    </div>
                    <div id="payment-boleto">
                      <form action="../controllers/PaymentController2.php" method="post">
                        Nome
                        <input type="text" class="input-field">
                        Sobrenome
                        <input type="text" class="input-field">
                        Email
                        <input type="text" class="input-field">
                        CPF
                        <input type="text" class="input-field">
                        <table class="half-input-table">
                          <tr>
                            <td>
                              CEP
                              <input type="text" class="input-field">
                            </td>
                            <td>
                              Rua
                              <input type="text" class="input-field">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Número
                              <input type="text" class="input-field">
                            </td>
                            <td>
                              Bairro
                              <input type="text" class="input-field">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Cidade
                              <input type="text" class="input-field">
                            </td>
                            <td>
                              UF
                              <input type="text" class="input-field">
                            </td>
                          </tr>
                        </table>
                        <button class="pay-btn">Comprar</button>
                        <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
                        <input type="hidden" name="description" value="Instrumento Bom">
                        <input type="hidden" name="paymentMethodId">
                      </form>
                    </div>
                    <div id="payment-pix">
                      <form action="../controllers/PaymentController3.php" method="post">
                        Nome
                        <input type="text" class="input-field">
                        Sobrenome
                        <input type="text" class="input-field">
                        Email
                        <input type="text" class="input-field">
                        CPF
                        <input type="text" class="input-field">
                        <table class="half-input-table">
                          <tr>
                            <td>
                              CEP
                              <input type="text" class="input-field">
                            </td>
                            <td>
                              Rua
                              <input type="text" class="input-field">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Número
                              <input type="text" class="input-field">
                            </td>
                            <td>
                              Bairro
                              <input type="text" class="input-field">
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Cidade
                              <input type="text" class="input-field">
                            </td>
                            <td>
                              UF
                              <input type="text" class="input-field">
                            </td>
                          </tr>
                        </table>
                        <button class="pay-btn">Comprar</button>
                        <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
                        <input type="hidden" name="description" value="Instrumento Bom">
                        <input type="hidden" name="paymentMethodId">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- container -->

<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="../mercadopag.js"></script>
</body>
</html>