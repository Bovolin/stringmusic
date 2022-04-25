<?php
    include ("foto.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>String;Music</title>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <script src="js/script.js"></script>
    <script src="js/swal.js"></script>
</head>
<body>
    <!-- HEADER -->
<header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" id="logo" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <a href="loja.php">Loja</a>
        <?php
            if(isset($_SESSION['usuario'])){
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

<!-- HOME  -->

<section class="home" id="home" style="min-height: 80vh">
    <div class="content">
        <h3>A plataforma própria para<span> músicos</span></h3>
        <p>Já pensou em algum lugar onde você possa comprar tudo relacionado a música? Acesse nossa loja!</p>
        <a href="loja.php" class="btnpart">Acessar Loja</a>
    </div>
    <div class="image">
        <img src="imgs/S_disco4.png" alt=""> <!-- NECESSITO DO FAV ICON GRANDE NA MINHA MESA JÁ !!-->
    </div>
</section>

<!-- HOME FIM -->

<!-- DESTAQUES  -->

<section class="features" id="features" style="min-height: 50vh">
    <h1 class="heading"> Destaques da <span> plataforma </span> </h1>
    <div class="box-container">
        <div class="box">
            <img src="imgs/Sem-Titulo-2.jpg" style="width: 250px" alt="">
            <h3>Divulgue aqui seu trabalho!</i></h3>
            <p>E encontre lugares para apresentá-lo!</p>
        </div>
        <div class="box">
            <img src="imgs/Sem-Título-5.png" style="width: 250px" alt="">
            <h3>Contrate os melhores!</h3>
            <p>Encontre aqui o músico ideal para seu evento!</p>
        </div>
        <div class="box">
            <img src="imgs/banner_treix.png" style="width: 250px" alt="">
            <h3>Anuncie aqui seu produto!</h3>
            <p>Ir para página de adicionar produtos! <a href="adicionarprod.php"><i class="fas fa-sign-in-alt"></a></i></p>
        </div>
    </div>
</section>

<!-- FEATURES FIM -->

<!-- SOBRE  -->
<!--
<section class="about" id="about">

    <h1 class="heading"> Sobre a plataforma </h1>

    <div class="column">

        <div class="image">
            <img src="imgs/prods/Guitarra.jpg" alt="">
        </div>

        <div class="content">
            <h3>A melhor solução para a fins de musicalidade</h3>
            <p>e suas frases icônicas inseridas aqui</p>
        </div>

    </div>

</section>-->

<!-- SOBRE FIM -->


<!-- CONTATO  -->

<section class="contact" id="contact">
    <div class="image">
        <img src="logo/padrão.png" alt="">
    </div>
    <form action="">
        <h1 class="heading">Contato</h1>
        <div class="inputBox">
            <input type="text" required>
            <p class="lbl_index">Nome</p>
        </div>
        <div class="inputBox">
            <input type="email" required>
            <p class="lbl_index">Email</p>
        </div>
        <div class="inputBox">
            <input type="text" required>
            <p class="lbl_index" onkeypress="return Onlynumbers(this)">Telefone</p>
        </div>
        <div class="inputBox">
            <input type="text" required>
            <p class="lbl_index">Título</p>
        </div>
        <div class="inputBox">
            <textarea required name="" id="" cols="30" rows="10"></textarea>
            <p class="lbl_index">Mensagem</p>
        </div>
        <input type="submit" class="btnpart" value="Enviar">
    </form>

</section>

<!-- CONTATO FIM --> 

<!-- FOOTER -->

<footer>
    <ul class="social-icon">
        <li><a href="#"><i class='bx bxl-facebook-circle'></i></a></li>
        <li><a href="#"><i class='bx bxl-instagram-alt' ></i></a></li>
        <li><a href="#"><i class='bx bxl-youtube' ></i></a></li>
    </ul>
    <ul class="menu">
        <li><a href="index.php">Inicio</a></li>
        <li><a href="loja.php">Loja</a></li>
    </ul>
    <p>Copyright</p>
</footer>

<!-- FOOTER FIM-->
</body>
</html>