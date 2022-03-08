<?php
  include ("fundo_foto.php");
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
          <div class="perfil-usuario-fundo" style="background<?php echo $imgfundo ?>">
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
              <button type="button" class="button-fundo" id="loadFileXml" onclick="document.getElementById('fundo').click();">
                <i class="fas fa-images"></i>Mudar fundo
              </button>
              <form action="mudarfundo.php" method="post" id="form2" enctype="multipart/form-data">
                <input type="file" style="display: none;" id="fundo" name="fundo" accept="image/png, img/jpg, image/jpeg">
              </form>
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

    <script>
          document.getElementById("file").onchange = function() {
            document.getElementById("form").submit();
          };

          document.getElementById("fundo").onchange = function() {
            document.getElementById("form2").submit();
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