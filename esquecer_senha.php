<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <script src="js/swal.js"></script>
        <link rel="stylesheet" href="css/styleconcluir.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
        <title>StringMusic</title>
    </head>

    <body>
    <header>
        <nav style="background: #2d2a30;">
        <ul class="ul-nav">
            <li class="logo"><a href="index.php"><img src="imgs/LogoAqui.png" alt="logo do site"></a></li>
            <li class="items"><a href="index.php">Início</a></li>
            <li class="items"><a href="loja.php">Produtos</a></li>
            <li class="items"><a href="servico.php">Serviços</a></li>
            <li class="items"><a href="contato.php">Contatos</a></li>
            <?php
            if(isset($_SESSION['usuario'])){
                foreach($mysqli->query("SELECT count(i.cd_interpretacao) + (SELECT count(i.cd_instrumento) from tb_instrumento as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_instrumento = c.cd_instrumento join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL) + (SELECT count(i.cd_servico) from tb_servico as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_servico = c.cd_servico join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL) as 'vendas' from tb_interpretacao as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_interpretacao = c.cd_interpretacao join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL") as $quantidade){
                    $vendas = $quantidade['vendas'];
                }
              echo
              '<div class="action">
                  <div class="profile" onclick="menuAlterna();">
                      <img src="'; echo $imagemusuario; echo'">
                  </div>
                  <div class="menu" style="top: 90px !important; right: 8.5% !important">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="info"><i class="bx bx-notepad"></i><a href="minhascompras.php">Minhas Compras</a></li>
                        <li class="info"><i class="bx bx-package"></i><a href="vendidos.php">Minhas Vendas</a><span class="dotAlert">'; echo $vendas; echo '</span></li>
                        <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
                        <li class="info_button"><input type="checkbox" name="switch-theme" id="switch">
                        <label for="switch" class="toggle">Toggle</label>
                        <script src="js/script_dark.js"></script></li>
                      </ul>
                  </div>
                </div>';
            }
            else{
            echo '<li class="items"><a href="login.php">Login</a></li>';
            }
            ?>
            <li class="btn"><a href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
        </nav>

        <!-- NÃO TOCA AQUI PELO AMOR DE DEUS \/-->  
        <script>
            /*  $(document).ready(function(){
            $('.btn').click(function(){
                $('.items').toggleClass("show");
                $('ul li').toggleClass("hide");
            });
            }); */
            const btn = document.getElementsByClassName('btn')[0];
            btn.addEventListener('click', function() {
            let items = document.getElementsByClassName('items');
            for (let i = 0; i <= items.length - 1; i += 1) {
                if (items[i].classList.contains('show')) {
                items[i].classList.remove('show');
                } else {
                items[i].classList.add('show');
                }         
            }
            });
        </script> 
    </header>
        <form action="troca_senha.php" method="post" class="sign-in-form" style="position: absolute; top: 35%; left: 35%;">

            <h2 class="title">Troque sua senha</h2>
            <label>Insira um e-mail válido: </label>
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Insira seu email" required>
            </div>
            <input type="submit" name="enviar" value="Trocar senha" class="btnpart" style="cursor: pointer;">
        </form>
    </body>
</html>