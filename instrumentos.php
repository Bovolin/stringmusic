<?php
session_start();
include ("conexao.php");

if(isset($_SESSION['usuario'])){
  $session = $_SESSION['usuario'];

  foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $imagemusuario = $usuarios['path'];
    if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
    elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
    elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
  }
}

?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>StringMusic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>
    <script src="js/clipboard.min.js"></script>

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
  <!-- HEADER -->
<header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <select name="dropdown" id="dropdown" onchange="javascript: abreJanela(this.value)">
            <option value="loja.php">Loja</option>
            <option value="instrumentos.php" selected>Instrumentos</option>
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
                  <div class="menu" style="right: -2% !important;">
                    <h3>'; echo $nomeusuario; echo '<br><span>'; echo $especialidadeusuario; echo '</span></h3>
                      <ul class="un">
                        <li class="info"><i class="bx bx-user-circle"></i><a href="painel.php">Meu Perfil</a></li>
                        <li class="info"><i class="bx bx-cart"></i><a href="mercadopag/view/mercadopag.php">Carrinho</a></li>
                        <li class="info"><i class="bx bx-envelope"></i><a href="meusprodutos.php">Meus Produtos</a></li>
                        <li class="info"><i class="bx bx-notepad"></i><a href="minhascompras.php">Minhas Compras</a></li>
                        <li class="info"><i class="bx bx-package"></i><a href="vendidos.php">Minhas Vendas</a></li>
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
<section class="product" id="product">
  <h1 style="font-size: 25px;display: inline; color: var(--color-headings);">Instrumentos</h1>
      <?php
        if(isset($_SESSION['usuario'])) echo '<a href="adicionarinst.php" style="text-decoration: none;"><h5 style="font-size: 15px;display: inline; margin-left: 30px;">Adicionar Instrumento <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        echo 
        '<form action="buscainst.php" method="get" style="display:inline; float: right; margin-top: -15px;">
          <input type="text" name="n" style="width: 300px;" class="field" placeholder="Insira o nome do instrumento">
          <button class="btnpart_loja" style="width: 50px; align-items: center"><i class="fas fa-search"></i></button>
        </form>';
      ?>
      <br><br><br>
      <div class="box-container">
      <?php
      $sql = "SELECT s.cd_instrumento, s.nm_instrumento, s.vl_instrumento, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_instrumento = s.cd_instrumento) as feedback FROM tb_instrumento AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE s.nm_inativo = 0";
      $query = $mysqli->query($sql);

      while($dados = $query->fetch_array()){
        echo '<div class="box">
          <div class="icons">
              <a href="#" class="fas fa-share"></a>
              <button class="btn'; echo $dados['nm_instrumento']; echo'" data-clipboard-text="https://localhost/stringmusic/prodinst.php?i='; echo $dados['nm_instrumento']; echo '"><a class="fas fa-copy"></a></button>
          </div>';?>
          <script>
              var button = document.getElementsByClassName("btn<?php echo $dados['nm_instrumento']?>");
              new ClipboardJS(button);
          </script>
          <?php echo'
          <img src="'; echo $dados['path']; echo '" alt="">
          <h3>'; 
          if(strlen($dados['nm_instrumento']) > 14)
            echo str_replace(substr($dados['nm_instrumento'], 11), '...', $dados['nm_instrumento']);
          else
            echo $dados['nm_instrumento'];
          echo '</h3>';
          if($dados['feedback'] == NULL)
              echo '
              <div class="stars">
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) < 1 && floatval($dados['feedback']) != NULL)
              echo '
              <div class="stars">
                  <i class="fas fa-star-half-alt"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) == 1)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) > 1 && floatval($dados['feedback']) < 2)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) == 2)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) > 2 && floatval($dados['feedback']) < 3)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) == 3)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) > 3 && floatval($dados['feedback']) < 4)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) == 4)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i> 
              </div>';
          elseif(floatval($dados['feedback']) > 4 && floatval($dados['feedback']) < 5)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
              </div>';
          elseif(floatval($dados['feedback']) == 5)
              echo '
              <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i> 
              </div>';
          echo '<div class="price"> R$'; echo $dados['vl_instrumento'];  echo '</div>
          <form method="get" action="prodinst.php">
            <input type="text" name="i" style="display: none;" value="'; echo $dados['nm_instrumento']; echo '">
            <input type="submit" class="btnpart" value="Comprar">
          </form>
      </div>';
      }
      ?>
  </div>
</section>
   
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
