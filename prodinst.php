<?php
session_start();
include ("conexao.php");

$c = $_GET['i'];

foreach($mysqli->query("SELECT i.cd_instrumento AS codigo, i.cd_usuario AS codigousuario, i.nm_instrumento AS instrumento, i.ds_instrumento AS descricao, i.vl_instrumento AS valor, u.nm_usuario AS nomeusuario, u.ds_usuario AS descricaousuario, im.path AS pathh FROM tb_instrumento AS i JOIN tb_usuario AS u ON u.cd_usuario = i.cd_usuario JOIN tb_imagem AS im ON im.cd_imagem = i.cd_imagem WHERE i.nm_instrumento = '$c'") as $instrumento){
  $nomeinterpretacao = $instrumento['instrumento'];
  $descricaointerpretacao = $instrumento['descricao'];
  $nomedono = $instrumento['nomeusuario'];
  $descricaousuario = $instrumento['descricaousuario'];
  $valorinterpretacao = $instrumento['valor'];
  $path = $instrumento['pathh'];
  $codigouser = $instrumento['codigousuario'];
  $codigointerpretacao = $instrumento['codigo'];
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
                  <div class="popup" style="background: var(--bg-panel)">
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
              <p style="display:none" id="codigo_serv"><?php echo $codigointerpretacao ?></p>
              <a href="<?php echo 'https://localhost/stringmusic/mercadopag/controllers/CarrinhoController.php?action=add&product='.$nomeinterpretacao.'&price='.$valorinterpretacao.'&code='.$codigointerpretacao ?>">
                <button type="button" class="btnpart" style="width: 200px;">Adicionar ao Carrinho <i class="fas fa-shopping-cart"></i></button>
              </a>
            </div>
          </div>
            
            
        </div>   
    </div>

  <h3>Produtos do mesmo autor</h3>
  <main class="grid">
  <?php

    $sql = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
    $query = $mysqli->query($sql);

    while($dados = $query->fetch_array()){
      echo '<article>
        <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
        <div class="text">
            <h3>'; echo $dados['nm_interpretacao']; echo '</h3>
            <p>'; echo $dados['ds_interpretacao']; echo '</p>
            <p>R$'; echo $dados['vl_interpretacao']; echo '</p>
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

    $sql3 = "SELECT s.cd_instrumento, s.nm_instrumento, s.ds_instrumento, s.vl_instrumento, i.path FROM tb_instrumento AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
    $query3 = $mysqli->query($sql3);

    while($dados = $query3->fetch_array()){
    echo '<article>
    <img src="';  echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
    <div class="text">
        <h3>';  echo $dados['nm_instrumento']; echo '</h3>
        <p>'; echo $dados['ds_instrumento']; echo '</p>
        <p> R$'; echo $dados['vl_instrumento']; echo '</p>
        <form method="get" action="prodserv.php">
            <input type="text" name="s" style="display: none;" value="'; echo $dados['nm_instrumento']; echo '">
            <input type="submit" class="btnpart" value="Comprar">
        </form>
    </div>
    </article>';
    }

    ?>
  </main>

  <!-- Menu navbar -> script interno -->
  <script>
            function menuAlterna(){
              const trocaMenu = document.querySelector('.menu');
              trocaMenu.classList.toggle('active');
            }
  </script>

  <!-- fiquei com preguiça de criar um .css e coloquei aqui xD-->
  <style>

    .box{
      width: 40%;
      margin: 0 auto;
      background: rgba(255,255,255,0.2);
      padding: 35px;
      border: 2px solid #fff;
      border-radius: 20px/50px;
      background-clip: padding-box;
      text-align: center;
    }

    .button{
      font-size: 1em;
      padding: 10px;
      color: black;
      border: 2px solid black;
      border-radius: 20px/50px;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s ease-out;
    }
    .button:hover{
      background: #3770db;
    }

    .overlay{
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0, 0, 0, 0.7);
      transition: opacity 500ms;
      visibility: hidden;
      opacity: 0;
    }
    .overlay:target{
      visibility: visible;
      opacity: 1;
    }

    .popup{
      margin: 70px auto;
      padding: 20px;
      background: #fff;
      border-radius: 5px;
      width: 30%;
      position: relative;
      transition: all 5s ease-in-out;
    }

    .popup h2{
      margin-top: 0;
      color: #333;
      font-family: Tahoma, Arial, sans-serif;
    }
    .popup .close{
      position: absolute;
      top: 20px;
      right: 30px;
      transition: all 200ms;
      font-size: 30px;
      font-weight: bold;
      text-decoration: none;
      color: #333;
    }
    .popup .close:hover{
      color: #3770db;
    }
    .popup .content{
      max-height: 30%;
      overflow: auto;
    }

    @media screen and (max-width: 700px){
      .box{
        width: 70%;
      }
      .popup{
        width: 70%;
      }
    }

  </style>
          
  

</body>
</html>