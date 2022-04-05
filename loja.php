<?php

include("foto.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String;Music</title>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <script src="js/script.js"></script>
    <script src="js/swal.js"></script>
    <!-- FONT AWESOME ICONS  -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styleloja.css">

</head>
<body>

<!-- HEADER -->
<header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <select name="dropdown" id="dropdown" onchange="javascript: abreJanela(this.value)">
            <option value="loja.php" selected>Loja</option>
            <option value="instrumentos.php">Instrumentos</option>
            <option value="interpretacoes.php">Partituras</option>
            <option value="servico.php">Serviços</option>
        </select>
        <?php
            if(isset($_SESSION['usuario'])){
              echo
              '<div class="action">
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
                        <li class="info_button"><input type="checkbox" name="switch-theme" id="switch">
                        <label for="switch" class="toggle">Toggle</label>
                        <script src="js/script_dark.js"></script></li>
                      </ul>
                  </div>
                </div>';
            }
            else{
              echo '<a href="login.php">Login</a>';
            }
            ?>
    </nav>

</header>

<!-- HEADER FIM -->

<!-- BANNER -->

<br><br><br><br><br><br>

<section class="banner-container">
    <div class="banner">
        <a href="">
            <img src="img/1.jpg" alt="">
        </a>
    </div>

    <div class="banner">
        <a href="">
            <img src="img/2.jpg" alt="">
        </a>
    </div>
</section>

<!-- BANNER FIM -->

<!-- CATEGORIA -->
<section class="category" id="category">

    <h1 class="heading">Comprar por <span>categoria</span></h1>

    <div class="box-container">

        <div class="box">
            <h3>Instrumentos</h3>
            <p>Textinho</p>
            <img src="img/partitura.jpg" alt="">
            <a href="instrumentos.php" class="btn">Acessar</a>
        </div>
        <div class="box">
            <h3>Partituras</h3>
            <p>Textinho</p>
            <img src="img/partitura.jpg" alt="">
            <a href="interpretacoes.php" class="btn">Acessar</a>
        </div>
        <div class="box">
            <h3>Serviços</h3>
            <p>Textinho</p>
            <img src="img/partitura.jpg" alt="">
            <a href="servico.php" class="btn">Acessar</a>
        </div>

    </div>

</section>

<!-- CATEGORIA FIM -->

<!-- PRODUTOS  -->

<section class="product" id="product">

    <h1 class="heading">Últimos <span>produtos</span></h1>

    <div class="box-container">

        <div class="box">
            <div class="icons">
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-copy"></a>
            </div>
            <img src="img/partitura.jpg" alt="">
            <h3>Musica 1</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price"> R$10,50 </div>
            <a href="#" class="btn">Comprar</a>
        </div>

        <div class="box">
            <span class="discount">-45%</span>
            <div class="icons">
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-copy"></a>
            </div>
            <img src="img/partitura.jpg" alt="">
            <h3>Musica 2</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price"> R$10,50 <span> R$13,20 </span> </div>
            <a href="#" class="btn">Comprar</a>
        </div>

        <div class="box">
            <div class="icons">
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-copy"></a>
            </div>
            <img src="img/partitura.jpg" alt="">
            <h3>Musica 3</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price">R$10,50</div>
            <a href="#" class="btn">Comprar</a>
        </div>

        <div class="box">
            <div class="icons">
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-copy"></a>
            </div>
            <img src="img/partitura.jpg" alt="">
            <h3>Musica 4</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price">R$10,50</div>
            <a href="#" class="btn">Comprar</a>
        </div>

        <div class="box">
            <div class="icons">
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-copy"></a>
            </div>
            <img src="img/partitura.jpg" alt="">
            <h3>Musica 5</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price">R$10,50</div>
            <a href="#" class="btn">Comprar</a>
        </div>

        <div class="box">
            <span class="discount">-29%</span>
            <div class="icons">
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-copy"></a>
            </div>
            <img src="img/partitura.jpg" alt="">
            <h3>Musica 6</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <div class="price">R$10,50 <span> R$13,20 </span> </div>
            <a href="#" class="btn">Comprar</a>
        </div>

    </div>

</section>

<!-- PRODUTOS FIM -->
    
</body>
</html>