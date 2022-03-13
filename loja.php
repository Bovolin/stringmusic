<?php
session_start();
include ("conexao.php");

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
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
    <script src="js/tent.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>

</head>
<body onload="produtorecusado()">

  <?php
    if(isset($_SESSION['produtorecusado'])):
    ?>
    <script>
    function produtorecusado(){
      Swal.fire({
        icon: 'error',
        title: 'Houve um erro ao enviar seu produto! Tente novamente mais tarde.'
      })
    }
    </script>
    <?php
    endif;
    unset($_SESSION['produtorecusado']);
  ?>
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
                        <script src="js/script_dark.js"></script></li>
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

  <div class="container">
    <br>
    <h2 style="display: inline;">Produtos</h2>
    <?php
      if(isset($_SESSION['usuario'])) echo '<a href="adicionarprod.php" style="text-decoration: none;"><h5 style="display: inline; margin-left: 30px;">Adicionar Produto <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
      echo '<form action="busca.php" method="get" style="display:inline; float: right; margin-top: -15px;">
        <input type="text" name="n" style="width: 300px;" class="field" placeholder="Insira o nome do produto">
        <button class="btnpart" style="width: 50px; align-items: center"><i class="fas fa-search"></i></button>
      </form>';
    ?>
    <br>
    <br>
    <br>
    <main class="grid">
      <?php

      $sql = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, s.qt_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE s.nm_inativo = 0";
      $query = $mysqli->query($sql);

      while($dados = $query->fetch_array()){
        echo '<article>
          <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
          <div class="text">
              <h3>'; echo $dados['nm_interpretacao']; echo '</h3>
              <p>'; echo $dados['ds_interpretacao']; echo '</p>
              <p>R$'; echo $dados['vl_interpretacao']; echo '</p>
              <p>Quantidade: '; echo $dados['qt_interpretacao']; echo '</p>
              <form method="get" action="produto.php">
              <input type="text" name="p" style="display: none;" value="'; echo $dados['nm_interpretacao']; echo '">
              <input type="submit" class="btnpart" value="Comprar">
              </form>
          </div> 
        </article>';
      }

      ?>
        
    </main>
  </div>  
   
  <!-- Botão menu mobile -->
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

  <!-- Menu navbar -> script interno -->
  <script>
            function menuAlterna(){
              const trocaMenu = document.querySelector('.menu');
              trocaMenu.classList.toggle('active');
            }
  </script>      
</body>
</html>
