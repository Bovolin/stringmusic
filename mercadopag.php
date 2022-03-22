<?php

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>String;Music</title>
    <link rel="stylesheet" type="text/css" href="css/stylepayment.css">
    <script src="js/script.js"></script>
</head>
<body>
    
    <div class='container'>
        <div class='window'>
          <div class='order-info'>
            <div class='order-info-content'>
              <h2>Carrinho</h2>
              <div class='line'></div>
              <table class='order-table'>
                <tbody>
                  <tr>
                    <td>
                      <img src='img/nota.jpg' class='full-width'>
                    </td>
                    <td>
                      <br> <span class='thin'>Tipo de produto</span>
                      <br>Nome do produto<br> <span class='thin small'> Descrição<br><br></span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class='price'>R$99,95</div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class='line'></div>
              <table class='order-table'>
                <tbody>
                  <tr>
                    <td><img src='img/nota.jpg' class='full-width'>
                    </td>
                    <td>
                      <br> <span class='thin'>Tipo de produto</span>
                      <br>Nome do produto<br> <span class='thin small'> Descrição</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class='price'>R$235,95</div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class='line'></div>
              <table class='order-table'>
                <tbody>
                  <tr>
                    <td><img src='img/nota.jpg' class='full-width'>
                    </td>
                    <td>
                      <br> <span class='thin'>Tipo de produto</span>
                      <br>Nome do produto<br> <span class='thin small'> Descrição</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class='price'>R$25.95</div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class='line'></div> <!-- SEMPRE QUE FOR ADICIONAR UM NOVO PRODUTO, TEM QUE SER COPIADO A PARTIR DAQUI-->
              <table class='order-table'>
                <tbody>
                  <tr>
                    <td><img src='img/nota.jpg' class='full-width'>
                    </td>
                    <td>
                      <br> <span class='thin'>Tipo de produto</span>
                      <br>Nome do produto<br> <span class='thin small'> Descrição</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class='price'>R$235,95</div>
                    </td>
                  </tr>
                </tbody>
              </table> <!-- ATÉ O FINAL DA TABLE, E EM SEGUIDA COLAR NOVAMENTE OUTRA LINE > ORDER-TABLE-->             
              <div class='line'></div>
              <div class='total'>
                <span style='float:left;'>
                  TOTAL
                </span>
                <span style='float:right; text-align:right;'>
                  R$999,99
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
                      <form action="mercadopag/controllers/PaymentController.php" method="post">
                        <img src='https://dl.dropboxusercontent.com/s/ubamyu6mzov5c80/visa_logo%20%281%29.png' height='80' class='credit-card-image' id='credit-card-image'> <!-- imagem trocando de acordo com o tipo de cartão-->
                        Nome
                        <input type="text" class='input-field'>
                        Número do cartão
                        <input type="text" class='input-field'>
                        <table class='half-input-table'>
                          <tr>
                            <td>Validade (mm/aa)
                              <input type="text" class='input-field'>
                            </td>
                            <td>CVV
                              <input type="text" class='input-field'>
                            </td>
                          </tr>
                        </table>
                        <button class='pay-btn'>Comprar</button>
                      </form>
                    </div>
                    <div id="payment-boleto">
                      <form action="mercadopag/controllers/PaymentController2.php" method="post">
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
                      </form>
                    </div>
                    <div id="payment-pix">
                      <form action="mercadopag/controllers/PaymentController3.php" method="post">
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
                      </form>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div> <!-- container -->
</body>
</html>