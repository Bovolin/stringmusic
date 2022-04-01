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
            echo
            '<li class="items">
                <div class="action">
                <div class="profile" onclick="menuAlterna();">
                    <img src="'; echo $imagemusuario; echo'">
                </div>
                <div class="menu">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                    <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
                    </ul>
                </div>
                </div>
            </li>';
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