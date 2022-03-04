<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/stylelogin.css" />
    <script src="swal.js"></script>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <title>StringMusic</title>
  </head>

  <?php
  if(isset($_SESSION['status_cadastro'])) $onload = 'cadastroFeito';
  elseif(isset($_SESSION['email_enviado'])) $onload = 'email_enviado';
  elseif(isset($_SESSION['email_recusado'])) $onload = 'email_recusado';

  if(isset($_SESSION['status_cadastro'])):
  ?>
    <script>
      function cadastroFeito(){
        Swal.fire({
          icon: 'success',
          title: 'Cadastro efetuado com sucesso!'
        })
      }
    </script>
  <?php
  endif;
  unset($_SESSION['status_cadastro']);

  if(isset($_SESSION['email_enviado'])):
  ?>
  <script>
    function email_enviado(){
      Swal.fire({
        icon: 'success',
        title: 'Senha alterada com sucesso!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['email_enviado']);

  if(isset($_SESSION['email_recusado'])):
  ?>
  <script>
    function email_recusado(){
      Swal.fire({
        icon: 'error',
        title: 'Houve um erro ao alterar sua senha!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['email_recusado']);
  ?>

  <body onload="<?php echo $onload?>()">
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          
          <form action="logar.php" method="post" class="sign-in-form">
            <h2 class="title">Login</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="emaillogar" placeholder="Email" required/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="senhalogar" minlegth="8" maxlength="16" placeholder="Senha" required/>
            </div>
            <?php
              if(isset($_SESSION['nao_usuario'])):
            ?>
            <p style="color: red;">E-mail ou senha incorretos</p>
            <a href="esquecer_senha.php" style="color: blue; text-decoration: none;">Esqueceu a senha?</a>
            <?php
              endif;
              unset($_SESSION['nao_usuario']);
            ?>
            <input type="submit" value="Login" class="btn solid" />
            <p class="social-text">Ou entre através das redes sociais</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </form>

          <form action="concluir.php" method="post" class="sign-up-form">
            <h2 class="title">Registro</h2>

            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="nome" placeholder="Nome Completo" required/>
            </div>

            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" placeholder="Email" required/>
            </div>
            
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="senha" minLegth="8" maxlength="16" placeholder="Senha" required/>
            </div>

            <input type="submit" class="btn" value="Registrar" />

            <p class="social-text">Ou registre-se com as redes sociais!</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </form>
        </div>
      </div> 

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>Novo na plataforma?</h3>
            <p>Clique no botão abaixo para ir para o menu de registro</p>
            <button class="btn transparent" id="sign-up-btn">Registrar</button>
            <a href="index.php"><img src="logo/roxo_preto.png" style="margin-top: 20px; margin-left: -30px;" class="image" alt=""></a>
          </div>  
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>Já possui conta?</h3>
            <p>Clique no botão abaixo para ir para o menu de login</p>
            <button class="btn transparent" id="sign-in-btn">Entrar</button>
            <a href="index.php"><img src="logo/roxo_preto.png" style="margin-top: 20px; margin-left: 30px;" class="image" alt=""></a>
          </div>
        </div>
      </div>
    </div>
    

    <script src="scriptlogin.js"></script>

    
  </body>
</html>
