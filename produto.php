<?php
session_start();
include ("conexao.php");

$produto = $_GET['p'];

/*
if(isset($produto)){
  $result = $mysqli->query("SELECT i.cd_interpretacao AS codigo FROM tb_interpretacao AS i WHERE 
  i.nm_produto = $produto");
  $_SESSION['id_interpretacao'] = $result; // = 1
}
*/

foreach($mysqli->query("SELECT i.cd_usuario AS codigousuario, i.cd_interpretacao AS codigo, i.nm_interpretacao AS interpretacao, i.ds_interpretacao AS descricao, i.qt_interpretacao AS quantidade, i.vl_interpretacao AS valor, u.nm_usuario AS nomeusuario, u.ds_usuario AS descricaousuario, im.path AS pathh FROM tb_interpretacao AS i JOIN tb_usuario AS u ON u.cd_usuario = i.cd_usuario JOIN tb_imagem AS im ON im.cd_imagem = i.cd_imagem WHERE i.nm_interpretacao = '$produto'") as $interpretacao){
  $nomeinterpretacao = $interpretacao['interpretacao'];
  $descricaointerpretacao = $interpretacao['descricao'];
  $quantidadeinterpretacao = $interpretacao['quantidade'];
  $nomedono = $interpretacao['nomeusuario'];
  $descricaousuario = $interpretacao['descricaousuario'];
  $valorinterpretacao = $interpretacao['valor'];
  $path = $interpretacao['pathh'];
  $codigouser = $interpretacao['codigousuario'];
  $codigointerpretacao = $interpretacao['codigo'];
}
foreach($mysqli->query("SELECT cd_imagem AS conferefoto FROM tb_usuario WHERE cd_usuario = '$codigouser'") as $confere_foto_dono){
  $dono_sem_foto = $confere_foto_dono['conferefoto'];
}
if(empty($dono_sem_foto)){
  $imagemdono = "imgs/user.jpeg";
}
else{
  foreach($mysqli->query("SELECT i.path AS path FROM tb_imagem AS i JOIN tb_usuario AS u ON i.cd_imagem = u.cd_imagem WHERE u.cd_usuario = '$codigouser'") as $pegaimagem){
    $imagemdono = $pegaimagem['path'];
  }
}


  if(isset($_SESSION['usuario'])){
    $session = $_SESSION['usuario'];

    foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
      $semfoto = $conferefoto['confere'];
    }

    if(empty($semfoto)){
      foreach($mysqli->query("SELECT u.nm_usuario AS nome, u.sg_especialidade AS especialidade FROM tb_usuario AS u WHERE cd_usuario = '$session'") as $usuarios){
        $nomeusuario = $usuarios['nome'];
        $imagemusuario = "imgs/user.jpeg";
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
    <script src="js/script.js" defer></script>
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>

</head>
<body>
  
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
              echo
              '<div class="action">
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

    <div class="container">
        <div class="contact-box">
          <div class="left">
            <div class="imagem-produto">
              <img src="<?php echo $path ?>" style="width: 425px; height: 570px;">
            </div>
          </div>
          <div class="right">
            <div class="info-produto">
              <div class="espec-produto">
                <h1 style="color: var(--color-headings);"><?php echo $nomeinterpretacao?></h1>
                <h3 style="color: rgb(34, 211, 211);">R$ <?php echo $valorinterpretacao ?></h3>
                <h4>
                  <a href="#popup1" style="color: var(--color-headings);">
                    <?php echo $nomedono ?>
                  </a>
                </h4>
                <div id="popup1" class="overlay">
                  <div class="popup">
                    <h3><?php echo $nomedono ?></h3>
                    <a class="close" href="#">&times;</a>
                    <div class="content">
                      <img src="<?php echo $imagemdono ?>" alt="foto de usuário" style="width: 200px; height: 200px;border-radius: 190px;">
                      <br>
                      <br>
                      <h4><?php echo $descricaousuario ?></h4>
                      <form action="userpreview.php" method="get">
                        <input type="text" style="display: none;" name="u" value="<?php echo $codigouser?>">
                        <input type="submit" class="btnpart" value="Visualizar Perfil"></input>
                      </form>
                    </div>
                  </div>
                </div>              
              </div>
              <div class="avalia-produto">
                <!-- em breve: rate por estrela -->
              </div>
              <h4 style="color: var(--color-text);">Descrição:</h4>
              <p style="color: var(--color-text);"><?php echo $descricaointerpretacao?></p>
              <h5 style="color: var(--color-text);">Quantidade: <?php echo $quantidadeinterpretacao ?></h5>
              <a href="#">
                <button type="button" class="btnpart" style="width: 200px;">Comprar</button>
              </a>
              <a href="<?php echo 'https://localhost/stringmusic/mercadopag/controllers/CarrinhoController.php?action=add&product=' . $nomeinterpretacao . '&price=' . $valorinterpretacao ?>">
                <button type="button" class="btnpart" style="width: 200px;">Adicionar ao Carrinho <i class="fas fa-shopping-cart"></i></button>
              </a>
            </div>
          </div>      
       </div>   
    </div>

  <h3>Produtos do mesmo autor</h3>
  <main class="grid">
  <?php

    $sql = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, s.qt_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
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

    $sql2 = "SELECT s.cd_servico, s.nm_servico, s.ds_servico, s.vl_servico, i.path FROM tb_servico AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
    $query2 = $mysqli->query($sql2);

    while ($dados = $query2->fetch_array()){ 
      echo '<article>
      <img src="';  echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
      <div class="text">
        <h3>';  echo $dados['nm_servico']; echo '</h3>
        <p>'; echo $dados['ds_servico']; echo '</p>
        <p> R$'; echo $dados['vl_servico']; echo '</p>
        <form method="get" action="prodserv.php">
            <input type="text" name="s" style="display: none;" value="'; echo $dados['nm_servico']; echo '">
            <input type="submit" class="btnpart" value="Comprar">
          </form>
      </div>
    </article>';

    }

    ?>
  </main>
  </div>

  <section id="comments">
        <div class="comments-heading">
            <span>Análises</span>
            <h1>Compradores avaliaram:</h1>
        </div>

        <div class="comments-box-container">
            <!-- caixa 1-->
            <div class="comments-box">
                <!-- topo-->
                <div class="box-top">
                    <!--perfil-->
                    <div class="profile">
                        <!-- img-->
                        <div class="profile-img">
                            <img src="imgs/user.png" alt="foto de usuário">
                        </div>
                        <!-- nome e username-->
                        <div class="name-user">
                            <strong>Singed</strong> <!-- nome de quem comentou-->
                            <span>Químico Louco</span> <!--ocupação (musico visitante etc)-->
                        </div>
                    </div>
                    <!-- review-->
                    <div class="reviews">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> <!-- FAS preenche-->
                        <i class="far fa-star"></i> <!-- FAR não preenche-->
                    </div>
                </div>
                <!-- comentário-->
                <div class="user-comment">
                    <p>To shake or not to shake, on my way, how'd taste?</p> <!-- comentário-->
                </div>
            </div>
            <!-- caixa 2-->
            <div class="comments-box">
                <!-- topo-->
                <div class="box-top">
                    <!--perfil-->
                    <div class="profile">
                        <!-- img-->
                        <div class="profile-img">
                            <img src="imgs/user.png" alt="foto de usuário">
                        </div>
                        <!-- nome e username-->
                        <div class="name-user">
                            <strong>Singed</strong> <!-- nome de quem comentou-->
                            <span>Químico Louco</span> <!--ocupação (musico visitante etc)-->
                        </div>
                    </div>
                    <!-- review-->
                    <div class="reviews">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i> <!-- FAS preenche-->
                        <i class="far fa-star"></i> <!-- FAR não preenche-->
                    </div>
                </div>
                <!-- comentário-->
                <div class="user-comment">
                    <p>To shake or not to shake, on my way, how'd taste?</p> <!-- comentário-->
                </div>
            </div>
  </section>
</body>
</html>