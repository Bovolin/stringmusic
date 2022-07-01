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
  
  $comp_serv_int = $mysqli->query("SELECT count(co.cd_compra) as comp_int from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho where co.cd_usuario = '$session' and c.cd_interpretacao != 0");
  $comp_serv_int = $comp_serv_int->fetch_assoc();
  if($comp_serv_int['comp_int'] != 0){
    $comp_int = $mysqli->query("SELECT max(s.nm_genero) as max_int from tb_interpretacao as s join tb_carrinho as c on s.cd_interpretacao = c.cd_interpretacao join tb_compra as co on c.cd_carrinho = co.cd_carrinho where co.cd_usuario = '$session'");
    $comp_int = $comp_int->fetch_assoc();
    $result_int = $comp_int['max_int'];
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
            <option value="instrumentos.php">Instrumentos</option>
            <option value="interpretacoes.php" selected>Partituras</option>
            <option value="servico.php">Serviços</option>
        </select>
        <?php
            if(isset($_SESSION['usuario'])){
              foreach($mysqli->query("SELECT count(i.cd_interpretacao) + (SELECT count(i.cd_instrumento) from tb_instrumento as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_instrumento = c.cd_instrumento join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL) + (SELECT count(i.cd_servico) from tb_servico as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_servico = c.cd_servico join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL) as 'vendas' from tb_interpretacao as i join tb_usuario as u on u.cd_usuario = i.cd_usuario join tb_carrinho as c on i.cd_interpretacao = c.cd_interpretacao join tb_compra as co on c.cd_carrinho = co.cd_carrinho where u.cd_usuario = '$session' and co.dt_entrega is NULL") as $quantidade){
                  $vendas = $quantidade['vendas'];
              }
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
                      <li class="info"><i class="bx bx-package"></i><a href="vendidos.php">Minhas Vendas</a><span class="dotAlert">'; echo $vendas; echo '</span></li>
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
  <h1 style="font-size: 25px;display: inline; color: var(--color-headings);">Partituras</h1>
      <?php
        if(isset($_SESSION['usuario'])) { echo '<a href="adicionarprod.php" style="text-decoration: none;"><h5 style="font-size: 15px;display: inline; margin-left: 30px;">Adicionar Partitura <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';}
        echo 
        '<form action="busca.php" method="get" style="display:inline; float: right; margin-top: -15px;">
          <input type="text" name="n" style="width: 300px;" class="field" placeholder="Insira o nome do produto">
          <button class="btnpart_loja" style="width: 50px; align-items: center"><i class="fas fa-search"></i></button>
        </form>';
      ?>
      <br><br><br>
      <div class="box-container">
      <?php

      if(isset($_SESSION['usuario'])){
        $session = $_SESSION['usuario'];
        $comp_serv_int2 = $mysqli->query("SELECT count(co.cd_compra) as comp_int2 from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho where co.cd_usuario = '$session' and c.cd_interpretacao != 0");
        $comp_serv_int2 = $comp_serv_int2->fetch_assoc();

        if($comp_serv_int2['comp_int2'] != 0){
          $query_alg = $mysqli->query("SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, i.path, s.nm_genero, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_interpretacao = s.cd_interpretacao) as feedback FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE s.nm_inativo = 0 having s.nm_genero = '$result_int'");

          while($dados_alg = $query_alg->fetch_array()){
            echo '<div class="box">
                <div class="icons">
                    <a href="#" class="fas fa-share"></a>
                    <button class="btn'; echo $dados_alg['nm_interpretacao']; echo'" data-clipboard-text="https://localhost/stringmusic/prodserv.php?s='; echo $dados_alg['nm_interpretacao']; echo '"><a class="fas fa-copy"></a></button>
                </div>';?>
                <script>
                    var button = document.getElementsByClassName("btn<?php echo $dados_alg['nm_interpretacao']?>");
                    new ClipboardJS(button);
                </script>
                <?php echo'
              <img src="'; echo $dados_alg['path']; echo '" alt="">
              <h3>'; 
                if(strlen($dados_alg['nm_interpretacao']) > 14)
                  echo str_replace(substr($dados_alg['nm_interpretacao'], 11), '...', $dados_alg['nm_interpretacao']);
                else
                  echo $dados_alg['nm_interpretacao'];
                echo '</h3>';
                if($dados_alg['feedback'] == NULL)
                    echo '
                    <div class="stars">
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) < 1 && floatval($dados_alg['feedback']) != NULL)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) == 1)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) > 1 && floatval($dados_alg['feedback']) < 2)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) == 2)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) > 2 && floatval($dados_alg['feedback']) < 3)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) == 3)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) > 3 && floatval($dados_alg['feedback']) < 4)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) == 4)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i> 
                    </div>';
                elseif(floatval($dados_alg['feedback']) > 4 && floatval($dados_alg['feedback']) < 5)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>';
                elseif(floatval($dados_alg['feedback']) == 5)
                    echo '
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i> 
                    </div>';
                echo '<div class="price"> R$'; echo $dados_alg['vl_interpretacao'];  echo '</div>
              <form method="get" action="produto.php">
                <input type="text" name="p" style="display: none;" value="'; echo $dados_alg['nm_interpretacao']; echo '">
                <input type="submit" class="btnpart" value="Comprar">
              </form>
            </div>';


            /*
              pe = APRO
              n° = 35581050600
              ca = 4235647728025682
              val = 11/2025
            */

          }
        }
      }

      $sql = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.vl_interpretacao, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_interpretacao = s.cd_interpretacao) as feedback FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem WHERE s.nm_inativo = 0";
      $query = $mysqli->query($sql);

      while($dados = $query->fetch_array()){
        echo '<div class="box">
          <div class="icons">
              <a href="#" class="fas fa-share"></a>
              <button class="btn'; echo $dados['nm_interpretacao']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $dados['nm_interpretacao']; echo '"><a class="fas fa-copy"></a></button>
          </div>';?>
          <script>
              var button = document.getElementsByClassName("btn<?php echo $dados['nm_interpretacao']?>");
              new ClipboardJS(button);
          </script>
          <?php echo'
          <img src="'; echo $dados['path']; echo '" alt="">
          <h3>'; 
          if(strlen($dados['nm_interpretacao']) > 14){
            echo str_replace(substr($dados['nm_interpretacao'], 11), '...', $dados['nm_interpretacao']);
          }
          else{
            echo $dados['nm_interpretacao'];
          } 
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

          echo '<div class="price"> R$'; echo $dados['vl_interpretacao'];  echo '</div>
          <form method="get" action="produto.php">
            <input type="text" name="p" style="display: none;" value="'; echo $dados['nm_interpretacao']; echo '">
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
