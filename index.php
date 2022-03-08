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
    <script src="tent.js" defer></script>
    <script src="script.js"></script>
    <script src="swal.js"></script>
</head>
<body>
    <nav class="container-fluid nav">
        <div class="container cf">
          <i class="fa fa-bars nav-toggle"></i>
          <ul class="un-navbar">
            <li class="navbar"><a href="index.php">Inicio</a></li>
            <li class="navbar"><a href="loja.php">Produtos</a></li>
            <li class="navbar"><a href="servico.php">Serviços</a></li>
            <?php
            if(isset($_SESSION['usuario'])){
              echo
              '<li class="navbar">
                <div class="action">
                  <div class="profile" onclick="menuAlterna();">
                      <img src="'; echo $imagemusuario; echo'">
                  </div>
                  <div class="menu">
                    <h3 style="margin-bottom: 0px;">'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
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
              echo '<li class="navbar"><a href="login.php">Login</a></li>';
            }
            ?>
          </ul>
        </div>
      </nav>
      
      <div class="container-fluid splash" id="splash">
        <div class="container">
          <img src="logo/roxo_preto.png" alt="Logo" class="profile-image">
        </div>
      </div>
      
      <div class="container-fluid intro" id="about">
        <div class="container">
          <h2>Sobre nós</h2>
          <p>Na carência de apoio a músicos e compositores que passam por vicissitudes em relação à procura de emprego e formas de 
            demonstrar seus talentos, o String;Music é uma plataforma que visa auxiliar esses profissionais com meios de divulgar seus 
            trabalhos através de alas de serviços e produtos.</p>
        </div>
      </div>
      
      <div class="container-fluid features" id="skills">
        <div class="container cf">
          <h2>Anuncie Já!</h2>
          <div class="col-3">
            <h3>Divulgue aqui seu trabalho! <i class="fas fa-briefcase"></i></h3>
            <p>E encontre os melhores lugares para apresentá-lo!</p>
          </div>
          <div class="col-3">
            <h3>Contrate os melhores músicos <i class="fas fa-music"></i></h3>
            <p>Encontre aqui o músico perfeito para seu evento!</p>
          </div>
          <div class="col-3">
            <h3>Anuncie aqui seu produto!</h3>
            <p>Ir para página de adicionar produtos! <a href="adicionarprod.php"><i class="fas fa-sign-in-alt"></a></i></p>
          </div>
        </div>
      </div>
      
      <div class="container-fluid portfolio" id="portfolio">
        <div class="container cf">
          <h2>Produtos mais comprados</h2>
          <div class="gallery">
            <main class="grid">
              <article>
                <img src="imgs/partitura.jpg" alt="">
                <div class="text">
                  <h3>Interpretação</h3>
                  <p>Música: Ace of Spades</p>
                  <button class="btnpart">Comprar</button>
                </div>
              </article>
              <article>
                <img src="imgs/partitura.jpg" alt="">
                <div class="text">
                  <h3>Interpretação</h3>
                  <p>Música: Ace of Spades</p>
                  <button class="btnpart">Comprar</button>
                </div>
              </article>
              <article>
                <img src="imgs/partitura.jpg" alt="">
                <div class="text">
                  <h3>Interpretação</h3>
                  <p>Música: Ace of Spades</p>
                  <button class="btnpart">Comprar</button>
                </div>
              </article>
            </main>
          </div>
        </div>
      </div>
      
      <div class="container-fluid contact" id="contact">
        <div class="container">
          <form action="enviar_email.php" method="post">
            <h2>Contate-nos</h2>
            <input type="text" placeholder="Nome" id="name" name="nome" class="full-half" maxlength="30">
            <input type="text" placeholder="Telefone" id="telefone" name="telefone" class="full-half" maxlength="17">
            <input type="email" placeholder="Email" id="email" name="email" maxlength="50">
            <input type="text" placeholder="Assunto" id="subject" name="titulo" maxlength="20">
            <textarea placeholder="Mensagem" id="message" name="mensagem" style="resize: none" maxlength="200"></textarea>
            <input type="submit" value="Enviar">
          </form>
        </div>
      </div>
</body>
</html>