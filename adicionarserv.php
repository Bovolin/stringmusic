<?php
include ("conexao.php");
include ("verifica_login.php");

if(isset($_SESSION['usuario'])){
  $session = $_SESSION['usuario'];

  foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
    $semfoto = $conferefoto['confere'];
  }

  if(empty($semfoto)){
    foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade FROM tb_usuario AS u WHERE cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $imagemusuario = "imgs/user.png";
      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
  }
  else{
    foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $imagemusuario = $usuarios['path'];
      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
  }
}

?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>StringMusic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleadicionars.css">
    <script src="script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="swal.js"></script>
</head>

<?php
  if(isset($_SESSION['servicoenviado'])) $onload = "servicoenviado";
  elseif(isset($_SESSION['size_stamp'])) $onload = "size_stamp";
  elseif(isset($_SESSION['error_stamp'])) $onload = "error_stamp";
  elseif(isset($_SESSOIN['servico_existente'])) $onload ="servico_existente";
 
  if(isset($_SESSION['servicoenviado'])):
  ?>
  <script>
    function servicoenviado(){
      Swal.fire({
        icon: 'success',
        text: 'Serviço enviado com sucesso!',
        confirmButtonColor: '#32cd32',
        confirmButtonText: 'Prosseguir'
      }).then((result) => {
        if(result.isConfirmed){
          window.location.href = "servico.php";
        }
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['servicoenviado']);

  if(isset($_SESSION['size_stamp'])):
  ?>
  <script>
    function size_stamp(){
      Swal.fire({
        icon: 'info',
        text: 'Sua imagem necessita ter 1920px de largura por 2560px de altura!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['size_stamp']);

  if(isset($_SESSION['error_stamp'])):
  ?>
  <script>
    function error_stamp(){
      Swal.fire({
        icon: 'info',
        text: 'Ocorreu um erro ao salvar sua imagem, tente novamente!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['error_stamp']);

  if(isset($_SESSION['servico_existente'])):
?>
  <script>
    function servico_existente(){
      Swal.fire({
        icon: 'error',
        text: 'Serviço já existente com esse nome!'
      })
    }
  </script>
  <?php
  endif;
  unset($_SESSION['servico_existente']);
  ?>
<body onload="<?php echo $onload ?>()">

<nav class="container-fluid nav">
        <div class="container cf">
          <div class="brand">
            <a href="index.php"><img src="logo/roxo_preto.png" alt="Logo" style="width: 450px;"></a>
          </div>
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

    <form method="post" action="enviadoserv.php" enctype="multipart/form-data">
        <div class="container">
            <div class="contact-box">
                <div class="left">
                  <label>Escolha uma imagem de capa:</label>
                  <a href="#popup1">
                    <i class="fas fa-question-circle"></i>
                  </a>
                  <div id="popup1" class="overlay" style="z-index: 1">
                    <div class="popup">
                      <h3>Sobre as Imagens</h3>
                      <a class="close" href="#">&times;</a>
                      <div class="content">
                        <br>
                        <h5>
                          Para a publicação, as imagens devem ter dimensões: 
                        </h5>
                        <h5>
                          - 1920px de largura por 2560px de altura.
                        </h5>
                      </div>
                    </div>
                  </div>
                  <input type="file" name="arquivo" id="arquivo" onchange="previewImagem()" accept="image/png, image/jpg, image/jpeg" class="field">
                  <img class="preview">
                </div>
                <div class="right">
                    <h2>Adicionar Serviço</h2>
                    <label>Nome:</label>
                    <input type="text" name="nome" maxlength="100" placeholder="Digite o nome do serviço" class="field" id="nome" required>
                    <br>
                    <label>Descrição:</label>
                    <input type="text" name="desc" class="field" placeholder="Digite o que seu serviço é" id="descricao" required>
                    <br>
                    <label>Preço:</label>
                    <input type="text" name="prc" maxlength="12" onkeypress="return Only(event)" placeholder="Digite o preço do serviço" class="field" id="valor" placeholder="R$" onkeyup="formatarMoeda()" required> 
                    <br>
                    <label>Gênero Musical:</label>
                    <div class="box">
                      <select name="genero_musical" id="genero_musical">
                        <option value="">Selecione</option>
                        <option value="jazz">Jazz</option>
                        <option value="mpb">MPB</option>
                        <option value="hm">Heavy Metal</option>
                        <option value="rap">Rap</option>
                        <option value="hh">Hip Hop</option>
                        <option value="rock">Rock</option>
                        <option value="pop">Pop</option>
                        <option value="blues">Blues</option>
                        <option value="funk">Funk</option>
                        <option value="elect">Eletrônica</option>
                        <option value="gosp">Gospel</option>
                        <option value="pag">Pagode</option>
                        <option value="samb">Samba</option>
                        <option value="folc">Folclórica</option>
                        <option value="erud">Erudita</option>
                        <option value="clas">Clássica</option>
                        <option value="sert">Sertanejo</option>
                        <option value="forr">Forró</option>
                        <option value="coun">Country</option>
                      </select>
                    </div>
                    <input type="submit" value="Enviar" class="btnpart">
                    <a href="servico.php"><input type="button" class="btnpart2" value="Cancelar"></a>
                </div>
                
            </div>
        </div>
        
    </form>

  <script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e)=>{
    let arrowParent = e.target.parentElement.parentElement;
    arrowParent.classList.toggle("showMenu");
      });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", ()=>{
      sidebar.classList.toggle("close");
    });
  </script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
    function previewImagem(){
      var imagem = document.querySelector('input[name=arquivo]').files[0];
      var preview = document.querySelector('.preview');
        
      var reader = new FileReader();
        
      reader.onloadend = function () {
        preview.src = reader.result;
      }
        
      if(imagem){
        reader.readAsDataURL(imagem);
      }else{
        preview.src = "";
      }
    }
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