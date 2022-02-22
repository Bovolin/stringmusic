<?php
  include ("conexao.php");
  include ("verifica_login.php");
  $session = $_SESSION['usuario'];

  foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
    $semfoto = $conferefoto['confere'];
  }

  if(empty($semfoto)){
    foreach($mysqli->query(
    "SELECT us.nm_usuario AS nome, 
    us.nm_email AS email, 
    us.sg_especialidade AS especialidade,
    us.ds_usuario AS descricao, 
    DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
    u.sg_uf AS uf,
    cm.nm_cidade AS cidade
      FROM tb_usuario AS us JOIN tb_uf AS u
        ON u.cd_uf = us.cd_uf
          JOIN tb_cidade AS cm
            ON cm.cd_cidade = us.cd_cidade
              WHERE us.cd_usuario = '$session'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $descricaousuario = $usuarios['descricao'];
    $emailusuario = $usuarios['email'];
    $nascimentousuario = $usuarios['nascimento'];
    $ufusuario = $usuarios['uf'];
    $cidadeusuario = $usuarios['cidade'];
    $imgusuario = "imgs/user.png";

    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
  }
  else{
    foreach($mysqli->query(
      "SELECT us.nm_usuario AS nome, 
      us.nm_email AS email, 
      us.sg_especialidade AS especialidade, 
      us.ds_usuario AS descricao, 
      DATE_FORMAT(us.dt_nascimento, '%d/%m/%Y') AS nascimento,
      u.sg_uf AS uf,
      cm.nm_cidade AS cidade,
      i.path AS path
        FROM tb_usuario AS us JOIN tb_uf AS u
          ON u.cd_uf = us.cd_uf
            JOIN tb_imagem AS i
              ON i.cd_imagem = us.cd_imagem
                JOIN tb_cidade AS cm
                  ON cm.cd_cidade = us.cd_cidade
                    WHERE us.cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $descricaousuario = $usuarios['descricao'];
      $emailusuario = $usuarios['email'];
      $nascimentousuario = $usuarios['nascimento'];
      $ufusuario = $usuarios['uf']; 
      $cidadeusuario = $usuarios['cidade'];
      $imgusuario = $usuarios['path'];
    
      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
      }
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/styleusuarios.css" />
        <script src="swal.js"></script>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
        <title>StringMusic</title>
    </head>

    <?php
    if(isset($_SESSION['cred_alterada'])) $onload = "cred_alterada()";

    if(isset($_SESSION['cred_alterada'])):
    ?>
    <script>
      function cred_alterada(){
        Swal.fire({
          icon: 'success',
          text: 'Credenciais alteradas com sucesso!'
        })
      }
    </script>
    <?php
    endif;
    unset($_SESSION['cred_alterada']);
    ?>
    <body onload="<?php echo $onload ?>">
    <!-- Ocorreu um erro ao colocar o css no Style original, por isso, criei um style interno -->
    <style>
      
    </style>
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
                    <img src="'; echo $imgusuario; echo'">
                </div>
                <div class="menu">
                  <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                    <ul>
                      <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                      <li class="info"><i class="bx bxs-user-detail"></i><a href="editarperfil.php">Editar Perfil</a></li>
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
        <section class="section-perfil-usuario">
          <div class="perfil-usuario-fundo">
            <div class="perfil-usuario-portal">
              <div class="perfil-usuario-avatar">
                <img src="<?php echo $imgusuario ?>" alt="">
                <button type="button" class="button-avatar" id="loadFileXml" onclick="document.getElementById('file').click();">
                  <i class="fas fa-camera"></i>
                  <?php
                    if(isset($_SESSION['fotoinserida'])):
                  ?>
                  <script>
                    function fotoenviada(){
                      Swal.fire({
                        icon: 'success',
                        title: 'Foto enviada com sucesso!'
                      })
                    }
                  </script>
                  <?php
                    endif;
                    unset($_SESSION['fotoinserida']);
                  ?>
                  <form action="mudarfoto.php" method="post" id="form" onsubmit="fotoenviada()" enctype="multipart/form-data">
                    <input type="file" style="display:none;" id="file" name="file" accept="image/png, image/jpg, image/jpeg">
                  </form>
                </button>
              </div>
              <button type="button" class="button-fundo">
                <i class="fas fa-images"></i>Mudar fundo
              </button>
            </div>
          </div>
          <div class="perfil-usuario-body">
            <div class="perfil-usuario-descricao">
              <h3 class="titulo"><?php echo $nomeusuario?></h3>
              <p class="texto"><?php echo $descricaousuario?></p> 
            </div>
            <div class="perfil-usuario-footer">
              <ul class="dados">
                <li><i class="icono fas fa-map-marked-alt" aria-hidden="true"> Localização: </i></li>
                <h4><?php echo $cidadeusuario . ', ' . $ufusuario?></h4>
                <li><i class="icono fas fa-envelope"> Email para contato: </i></li>
                <h4><?php echo $emailusuario?></h4>
                <li><i class="icono fa fa-calendar" aria-hidden="true"> Ano de nascimento: </i></li>
                <h4><?php echo $nascimentousuario?></h4>
                <li><i class="icono fas fa-music"> Tipo de usuário: </i></li>
                <h4><?php echo $especialidadeusuario?></h4>
              </ul>
            </div>
            <div class="redes-sociais">
              <a href="editarperfil.php" class="boton-redes instagram fas fa-user-edit" style="background: linear-gradient(45deg, #336BB8, #37B82A);"><i class="icon-facebook"></i></a>
              <a href="" class="boton-redes facebook fab fa-facebook-f"><i class="icon-facebook"></i></a>
              <a href="" class="boton-redes instagram fab fa-instagram"><i class="icon-instagram"></i></a>
              <input type="checkbox" name="switch-theme" id="switch">
              <label for="switch">Toggle</label>
            </div>
          </div>
        </section>

        <script src="script_dark.js"></script>

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

    <style>
      /* footer */
      footer {
        position: fixed;
        bottom: 0;
      }

      @media (max-height:800px) {
        footer {
            position: static;
        }
      }

      .footer-distributed {
        background-color: #2d2a30;
        box-sizing: border-box;
        width: 100%;
        text-align: left;
        font: bold 16px sans-serif;
        padding: 50px 50px 60px 50px;
        margin-top: 80px;
      }

      .footer-distributed .footer-left, .footer-distributed .footer-center, .footer-distributed .footer-right {
        display: inline-block;
        vertical-align: top;
      }

      /* Footer left */

      .footer-distributed .footer-left {
        width: 30%;
      }

      .footer-distributed h3 {
        color: #ffffff;
        font: normal 36px 'Cookie', cursive;
        margin: 0;
      }


      .footer-distributed h3 span {
        color: #3F71EA;
      }

      /* Footer links */

      .footer-distributed .footer-links {
        color: #ffffff;
        margin: 20px 0 12px;
      }

      .footer-distributed .footer-links a {
        display: inline-block;
        line-height: 1.8;
        text-decoration: none;
        color: inherit;
      }

      .footer-distributed .footer-company-name {
        color: #8f9296;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
      }


      /* Footer Center */

      .footer-distributed .footer-center {
        width: 35%;
      }

      .footer-distributed .footer-center i {
        background-color: #33383b;
        color: #ffffff;
        font-size: 25px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        text-align: center;
        line-height: 42px;
        margin: 10px 15px;
        vertical-align: middle;
      }

      .footer-distributed .footer-center i.fa-envelope {
        font-size: 17px;
        line-height: 38px;
      }

      .footer-distributed .footer-center p {
        display: inline-block;
        color: #ffffff;
        vertical-align: middle;
        margin: 0;
      }

      .footer-distributed .footer-center p span {
        display: block;
        font-weight: normal;
        font-size: 14px;
        line-height: 2;
      }

      .footer-distributed .footer-center p a {
        color: #3F71EA;
        text-decoration: none;
        ;
      }

      /* Footer Right */

      .footer-distributed .footer-right {
        width: 30%;
      }

      .footer-distributed .footer-company-about {
        line-height: 20px;
        color: #92999f;
        font-size: 13px;
        font-weight: normal;
        margin: 0;
      }

      .footer-distributed .footer-company-about span {
        display: block;
        color: #ffffff;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
      }

      .footer-distributed .footer-icons {
        margin-top: 25px;
      }

      .footer-distributed .footer-icons a {
        display: inline-block;
        width: 35px;
        height: 35px;
        cursor: pointer;
        background-color: #33383b;
        border-radius: 2px;
        font-size: 20px;
        color: #ffffff;
        text-align: center;
        line-height: 35px;
        margin-right: 3px;
        margin-bottom: 5px;
      }

      .footer-distributed .footer-icons a:hover {
        background-color: #3F71EA;
      }

      .footer-links a:hover {
        color: #3F71EA;
      }

      @media (max-width: 880px) {
        .footer-distributed .footer-left, .footer-distributed .footer-center, .footer-distributed .footer-right {
            display: block;
            width: 100%;
            margin-bottom: 40px;
            text-align: center;
        }
        .footer-distributed .footer-center i {
            margin-left: 0;
        }
      }     
    </style>

    <script>
          document.getElementById("file").onchange = function() {
            document.getElementById("form").submit();
          };
    </script>
        
    <!-- Menu navbar -> script interno -->
    <script>
          function menuAlterna(){
            const trocaMenu = document.querySelector('.menu');
            trocaMenu.classList.toggle('active');
          }
    </script>
        
    
    </body>

</html>