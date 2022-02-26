<!DOCTYPE html>
<html lang="ptb-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="tent.css">
    <script src="script.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
<header>
    <nav>
      <ul class="ul-nav">
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
                      <li class="info"><i class="bx bxs-user-detail"></i><a href="editarperfil.php">Editar Perfil</a></li>
                      <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                      <li class="sair"><i class="bx bx-log-out"></i><a href="logout.php">Sair</a></li>
                      <li class="info"><input type="checkbox" name="switch-theme" id="switch">
                      <label for="switch" class="toggle">Toggle</label>
                      <script src="script_dark.js"></script></li>
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
<div id="title" class="slidec header">
    <img src="logo/roxo_preto.png">
</div>

<div id="slide1" class="slidec">
    <div class="slider">
        <div class="slide active">
            <a href="loja.php">
                <img src="imgs/banner_treix.png" alt="">
            </a>
        </div>
        <div class="slide">
            <img src="imgs/banner_dois.png" alt=""> 
        </div>
        <div class="slide">
            <img src="imgs/banner_um.png" alt="">
        </div>
        <div class="navigation">
            <i class="fas fa-chevron-left prev-btn"></i>
            <i class="fas fa-chevron-right next-btn"></i>
        </div>
        <div class="navigation-visibility">
            <div class="slide-icon active"></div>
            <div class="slide-icon"></div>
            <div class="slide-icon"></div>
        </div>
    </div>
</div>

<div id="slide2" class="slidec">
  <div class="title">
    <h1>Sobre nós</h1>
    <p>texto bom</p>
  </div>
</div>

<div id="slide3" class="slidec">
    <div class="container">
      <div class="contact-box">
        <div class="left" style="background: url('imgs/bg.jpg') no-repeat center; background-size: cover; height: 100%;">
        </div>
        <div class="right">
          <h2>Contate-nos!</h2>
          <form action="enviar_email.php" method="post">
            <input type="text" name="nome" class="field" placeholder="Nome" required>
            <input type="text" name="email" class="field" placeholder="Email" required>
            <input type="text" name="telefone" class="field" placeholder="Telefone" required>
            <input type="text" name="titulo" class="field" placeholder="Título da mensagem" required>
            <textarea placeholder="Informe o motivo de seu contato!" name="mensagem" class="field" style="resize: none;" required></textarea>
            <input type="submit" class="btn2" value="Enviar">
          </form>
        </div>
      </div>
    </div>
</div>

<div id="slide4" class="slidec header">
  <footer class="footer-distributed">
    <div class="footer-left">
        <h3>String<span>Music</span></h3>

        <p class="footer-links">
            <a href="index.php">Início</a>
            |
            <a href="contato.php">Contatos</a>
            |
            <a href="login.php">Login</a>
        </p>

        <p class="footer-company-name">Copyright © 2021 <strong>StringMusic</strong>
        <p style="color: white;">Todos os direitos reservados</p>
    </div>
    <div class="footer-center">
        <div>
            <i class="fa fa-map-marker"></i>
            <p><span>ETEC Dra Ruth Cardoso</span>
                São Vicente / SP</p>
        </div>

        <div>
            <i class="fa fa-phone"></i>
            <p>+55 (13) 5555-5555</p>
        </div>
        <div>
            <i class="fa fa-envelope"></i>
            <p><a href="#">stringmsc@email.com</a></p>
        </div>
    </div>
    <div class="footer-right">
        <p class="footer-company-about">
            <span>Sobre nós</span>
            <strong>StringMusic</strong>
        </p>
        <div class="footer-icons">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
  </footer>
</div>
</body>
</html>
