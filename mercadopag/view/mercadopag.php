<?php
/* Página visual onde fica o carrinho de compras */
//Ignora os erros caso apareça
ini_set('display_errors', 0);
error_reporting(0);
//Chama o autoload do composer
require("../../lib/vendor/autoload.php");
//Inclusão da Classe carrinho
include("../../class/ClassCarrinho.php");
//Instancia um novo carrinho
$carrinho = new ClassCarrinho();
//Autenticação de usuário logado
include("../../verifica_login.php");
//Sessão usada para mostrar notificação logo ao abrir a página (SweetAlert)
$_SESSION['init'] = true;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>StringMusic</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../../css/stylepagamento.css"/>
    <link rel="shortcut icon" href="../../favicon/ms-icon-310x310.png" />
    <script src="../../js/script.js"></script>
    <script src="../../js/swal.js"></script>
</head>
<?php
//Verificação das sessões para mostrar SweetALert
  if(isset($_SESSION['init'])) $onload = 'init()';
  if(isset($_SESSION['transaction_error'])) $onload = 'onload()';

  if(isset($_SESSION['transaction_error'])):
?>
<script>
  function onload(){
    Swal.fire({
      icon: 'error',
      title: 'Ocorreu um erro interno. Tente novamente!'
    })
  }
</script>
<?php
  endif;
  //Encerrando sessão
  unset($_SESSION['transaction_error']);

  if(isset($_SESSION['init'])):
?>
<script>
  function init(){
    Swal.fire({
      icon: 'info',
      html: '<h1>Essa é um página de teste da API do Mercado Livre, insira as credencias de teste:</h1><br>Nome Completo: APRO <br> Email: test_user_19653727@testuser.com <br> Número de Documento: 35581050600 <br> Número de Cartão: 4235647728025682 <br> Validade: 11/2025'
    })
  }
</script>
<?php
  endif;  
  //Encerrando sessão
  unset($_SESSION['init']);
?>
<!-- HTML -->
<body onload="<?php echo $onload ?>">
    <div class='container'>
        <div class='window'>
          <div class='order-info'>
            <div class='order-info-content'>
              <a href="../../loja.php" style="color: black;"><i class='bx bx-arrow-back' style="width: 250px; margin-top: 10px;"></i></a>
              <h2>Carrinho</h2>
              <!-- Se não houver produtos no carrinho -> exibe mensagem de aviso -->
              <?php if($carrinho->getQuantity() != 0) echo "<div class='line'></div>"; ?>
              <!-- Exibe os produtos do carrinho -->
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
                <!-- Exibe o total do carrinho e a quantidade de produtos -->
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
                      <!-- formulário via cartão de crédito -->
                      <form action="../controllers/PaymentController.php" method="post" id="pay" name="pay">
                        <div class="brand" style="display: none;"></div>
                        <label for="cardholderName">Nome Completo</label>
                        <input id="nome_completo" name="nome_completo" type="text" id="cardholderName" data-checkout="cardholderName" class='input-field'>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class='input-field' value="test_user_19653727@testuser.com">
                        <table class="half-input-table">
                          <tr>
                            <td>
                              <label for="docType">Documento</label>
                              <select id="docType" data-checkout="docType" class='input-field'></select>
                            </td>
                            <td>
                              <label for="docNumber">Número do Documento</label>
                              <input type="text" id="docNumber" data-checkout="docNumber" class='input-field'>
                            </td>
                          </tr>
                        </table>
                        <div class="cartao-bandeira">
                          <label for="cardNumber">Número do cartão</label>
                          <input type="text" class='input-field' id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off>
                          <img alt="Bandeira Cartão" class="bandeira-img" id="bandeira">
                        </div>
                        <table class='half-input-table'>
                          <tr>
                            <td>
                              <label for="cardExpirationMonth">Mês de Expiração</label>
                              <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" class='input-field'>
                            </td>
                            <td>
                              <label for="cardExpirationYear">Ano de Expiração</label>
                              <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" class='input-field'>
                            </td>
                          </tr>
                        </table>
                        <table class="half-input-table">
                          <tr>
                            <td>
                              <label for="securityCode">Código de Segurança</label>
                              <input type="text" class="input-field" id="securityCode" data-checkout="securityCode">
                            </td>
                            <td>
                              <label for="installments">Parcelas</label>
                              <select id="installments" class="form-control input-field" name="installments"></select>
                            </td>
                          </tr>
                        </table>

                        <!-- input escondido para pegar o total do valor do carrinho -->
                        <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
                        <!-- input escondido para colocar descrição da compra (coloquei um value aleatório) -->
                        <input type="hidden" name="description" value="Instrumento Bom">
                        <!-- input escondido e essencial para definir o ID do método de pagamento -->
                        <input type="hidden" name="paymentMethodId">
                        <!-- input para enviar o formulário -->
                        <input type="submit" class='pay-btn' value="Comprar">
                      </form>
                    </div>
                    <!-- formulário via boleto -->
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
                        <!-- input escondido para pegar o total do valor do carrinho -->
                        <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
                        <!-- input escondido para colocar descrição da compra (coloquei um value aleatório) -->
                        <input type="hidden" name="description" value="Instrumento Bom">
                        <!-- input escondido e essencial para definir o ID do método de pagamento -->
                        <input type="hidden" name="paymentMethodId">
                      </form>
                    </div>
                    <!-- formulário via PIX -->
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
                        <!-- input escondido para pegar o total do valor do carrinho -->
                        <input type="hidden" name="amount" id="amount" value="<?php echo $carrinho->getAmount(); ?>">
                        <!-- input escondido para colocar descrição da compra (coloquei um value aleatório) -->
                        <input type="hidden" name="description" value="Instrumento Bom">
                        <!-- input escondido e essencial para definir o ID do método de pagamento -->
                        <input type="hidden" name="paymentMethodId">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- container -->

<!-- JavaScript da API do MP (para pegar bandeiras de cartão e etc.) -->
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="../mercadopag.js"></script>
</body>
</html>