<?php
session_start();
include ("conexao.php");

$c = $_GET['s'];

foreach($mysqli->query("SELECT i.cd_servico AS codigo, i.cd_usuario AS codigousuario, i.nm_servico AS servico, i.ds_servico AS descricao, i.vl_servico AS valor, u.nm_usuario AS nomeusuario, u.ds_usuario AS descricaousuario, im.path AS pathh FROM tb_servico AS i JOIN tb_usuario AS u ON u.cd_usuario = i.cd_usuario JOIN tb_imagem AS im ON im.cd_imagem = i.cd_imagem WHERE i.nm_servico = '$c'") as $servico){
  $nomeinterpretacao = $servico['servico'];
  $descricaointerpretacao = $servico['descricao'];
  $nomedono = $servico['nomeusuario'];
  $descricaousuario = $servico['descricaousuario'];
  $valorinterpretacao = $servico['valor'];
  $path = $servico['pathh'];
  $codigouser = $servico['codigousuario'];
  $codigointerpretacao = $servico['codigo'];
}
foreach($mysqli->query("SELECT i.path AS path FROM tb_imagem AS i JOIN tb_usuario AS u ON i.cd_imagem = u.cd_imagem WHERE u.cd_usuario = '$codigouser'") as $pegaimagem){
  $imagemdono = $pegaimagem['path'];
}

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
<?php
  if(isset($_SESSION['inserir_feedback'])) $swal = 'inserir_feedback';
  if(isset($_SESSION['inserir_feedback'])):
?>
<script>
  function inserir_feedback(){
    Swal.fire({
      icon: 'success',
      title: 'Obrigado pelo feedback!'
    })
  }
</script>
<?php
  endif;
  unset($_SESSION['inserir_feedback']);
?>
<body onload="<?php echo $swal?>()">
  
<header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <select name="dropdown" id="dropdown" onchange="javascript: abreJanela(this.value)">
            <option value="loja.php">Loja</option>
            <option value="instrumentos.php">Instrumentos</option>
            <option value="interpretacoes.php">Partituras</option>
            <option value="servico.php" selected>Serviços</option>
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

    <section class="product" id="product">
    <h1 class="heading">Produtos do <span>mesmo autor</span></h1>
    <div class="box-container">
  <?php

    $sql = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.vl_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
    $query = $mysqli->query($sql);

    while($dados = $query->fetch_array()){
      echo '<div class="boxx">
                    <div class="icons">
                        <a class="fas fa-share"></a>
                        <button class="btn'; echo $dados['nm_interpretacao']; echo'" data-clipboard-text="https://localhost/stringmusic/produto.php?p='; echo $dados['nm_interpretacao']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';
                        ?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $dados['nm_interpretacao']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo '
                    <img src="'; echo $dados['path']; echo '" alt="">
                    <h3>'; 
                    if(strlen($dados['nm_interpretacao']) > 14){
                        echo str_replace(substr($dados['nm_interpretacao'], 11), '...', $dados['nm_interpretacao']);
                    }
                    else{
                        echo $dados['nm_interpretacao'];
                    } 
                    echo '</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="price"> R$'; echo $dados['vl_interpretacao'];  echo '</div>
                    <form method="get" action="produto.php" style="display: inline-block;">
                        <input type="text" name="p" style="display: none;" value="'; echo $dados['nm_interpretacao']; echo '">
                        <input type="submit" class="btnpart" value="Comprar">
                    </form>
                </div>';
    }

    $sql2 = "SELECT s.cd_servico, s.nm_servico, s.vl_servico, i.path FROM tb_servico AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
    $query2 = $mysqli->query($sql2);

    while ($dados = $query2->fetch_array()){ 
      echo '<div class="boxx">
                        <div class="icons">
                        <a class="fas fa-share"></a>
                        <button class="btn'; echo $dados['nm_servico']; echo'" data-clipboard-text="https://localhost/stringmusic/prodserv.php?s='; echo $dados['nm_servico']; echo '"><a class="fas fa-copy"></a></button>
                        </div>';
                        ?>
                        <script>
                            var button = document.getElementsByClassName("btn<?php echo $dados['nm_servico']?>");
                            new ClipboardJS(button);
                        </script>
                        <?php echo '
                    <img src="'; echo $dados['path']; echo '" alt="">
                    <h3>'; 
                    if(strlen($dados['nm_servico']) > 14){
                        echo str_replace(substr($dados['nm_servico'], 11), '...', $dados['nm_servico']);
                    }
                    else{
                        echo $dados['nm_servico'];
                    } 
                    echo '</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="price"> R$'; echo $dados['vl_servico'];  echo '</div>
                    <form method="get" action="prodserv.php" style="display: inline-block;">
                        <input type="text" name="s" style="display: none;" value="'; echo $dados['nm_servico']; echo '">
                        <input type="submit" class="btnpart" value="Comprar">
                    </form>
                </div>';

    }

    $sql3 = "SELECT s.cd_instrumento, s.nm_instrumento, s.vl_instrumento, i.path FROM tb_instrumento AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigouser'";
    $query3 = $mysqli->query($sql3);

    while($dados = $query3->fetch_array()){
      echo '<div class="boxx">
      <div class="icons">
      <a class="fas fa-share"></a>
      <button class="btn'; echo $dados['nm_instrumento']; echo'" data-clipboard-text="https://localhost/stringmusic/prodinst.php?i='; echo $dados['nm_instrumento']; echo '"><a class="fas fa-copy"></a></button>
      </div>';
      ?>
      <script>
          var button = document.getElementsByClassName("btn<?php echo $dados['nm_instrumento']?>");
          new ClipboardJS(button);
      </script>
      <?php echo '
      <img src="'; echo $dados['path']; echo '" alt="">
      <h3>'; 
      if(strlen($dados['nm_instrumento']) > 14){
          echo str_replace(substr($dados['nm_instrumento'], 11), '...', $dados['nm_instrumento']);
      }
      else{
          echo $dados['nm_instrumento'];
      } 
      echo '</h3>
      <div class="stars">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star-half-alt"></i>
      </div>
      <div class="price"> R$'; echo $dados['vl_instrumento'];  echo '</div>
      <form method="get" action="prodinst.php" style="display: inline-block;">
          <input type="text" name="i" style="display: none;" value="'; echo $dados['nm_instrumento']; echo '">
          <input type="submit" class="btnpart" value="Comprar">
      </form>
    </div>';
    }

    ?>
    </div>
  </section>

  <section id="comments">
        <div class="comments-heading">
            <span>Análises</span>
            <h1>Compradores avaliaram:</h1>
        </div>

        <?php
        if(isset($session)){
          foreach($mysqli->query("SELECT count(co.cd_compra) as comprado from tb_compra as co join tb_carrinho as c on c.cd_carrinho = co.cd_carrinho join tb_usuario as u on u.cd_usuario = c.cd_usuario join tb_servico as i on i.cd_servico = c.cd_servico where u.cd_usuario = '$session' and c.nm_inativo = 1 and i.nm_servico = '$nomeinterpretacao'") as $compra){
            $comprado = $compra['comprado'];
          }
          if($comprado != '0'){
          ?>
          <button type="button" class="btnpart" onclick="visivel()"> Dar opinião </button>
          <div class="star-widget" id="div-start-widget" style="display:none">
            <div class="star-review">
                <input type="radio" name="rate" value="5" id="rate-5" onclick="star()">
                <label for="rate-5" class="fas fa-star"></label>
                <input type="radio" name="rate" value="4" id="rate-4" onclick="star()">
                <label for="rate-4" class="fas fa-star"></label>
                <input type="radio" name="rate" value="3" id="rate-3" onclick="star()">
                <label for="rate-3" class="fas fa-star"></label>
                <input type="radio" name="rate" value="2"  id="rate-2" onclick="star()">
                <label for="rate-2" class="fas fa-star"></label>
                <input type="radio" name="rate" value="1"  id="rate-1" onclick="star()">
                <label for="rate-1" class="fas fa-star"></label>
            </div>
            <form action="feedback.php" method="post" class="form_feedback">
              <header style="display: none;"></header>
              <br>
              <div class="textarea">
                <textarea cols="30" name="comment" placeholder="Deixe seu feedback sobre o produto!!"></textarea>
              </div>
              <div id="btn_opinion">,
                <input type="hidden" name="nm_prod" value="<?php echo $nomeinterpretacao ?>">
                <input type="hidden" name="input-star" id="input-star">
                <button class="btnpart" type="submit">Enviar</button>
              </div>
            </form>
          </div>
        </div>
        <?php } } ?>
        

        <div class="comments-box-container">
          <?php
            foreach($mysqli->query("SELECT COUNT(cd_feedback) AS feedback FROM tb_feedback WHERE cd_servico = '$codigointerpretacao'") as $feedback){
              $count_feedback = $feedback['feedback'];
            }
            if($count_feedback != 0){
              //Comentários
              $sql_feedback ="SELECT f.nm_feedback as feedback, f.qt_feedback as estrelas, u.nm_usuario as usuario, u.sg_especialidade as especialidade, i.path as imagem from tb_feedback as f join tb_usuario as u on u.cd_usuario = f.cd_usuario join tb_imagem as i on i.cd_imagem = u.cd_imagem where f.cd_servico = '$codigointerpretacao'";
              $query_feedback = $mysqli->query($sql_feedback);
              
              while($dados_fb = $query_feedback->fetch_array()){
                echo 
                '<!-- caixa 1-->
                <div class="comments-box">
                  <!-- topo-->
                  <div class="box-top">
                        <!--perfil-->
                        <div class="profile">
                            <!-- img-->
                            <div class="profile-img">
                                <img src="'; echo $dados_fb['imagem']; echo '" alt="foto de usuário">
                            </div>
                            <!-- nome e username-->
                            <div class="name-user">
                                <strong>'; 
                                if(strlen($dados_fb['usuario']) > 14){
                                  echo str_replace(substr($dados_fb['usuario'], 11), '...', $dados_fb['usuario']);
                                }
                                else{
                                  echo $dados_fb['usuario'];
                                } 
                                echo '</strong> <!-- nome de quem comentou-->
                                <span>'; 
                                if($dados_fb['especialidade'] == 'v') echo 'Visitante';
                                elseif($dados_fb['especialidade'] == 'm') echo 'Músico';
                                elseif($dados_fb['especialidade'] == 'c') echo 'Compositor';
                                echo '</span> <!--ocupação (musico visitante etc)-->
                            </div>
                        </div>
                        <!-- review-->
                        <div class="reviews">';
                          if($dados_fb['estrelas'] == 1){
                            echo 
                            '<i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>';
                          }
                          elseif($dados_fb['estrelas'] == 2){
                            echo 
                            '<i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>';
                          }
                          elseif($dados_fb['estrelas'] == 3){
                            echo 
                            '<i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>';
                          }
                          elseif($dados_fb['estrelas'] == 4){
                            echo 
                            '<i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>';
                          }
                          if($dados_fb['estrelas'] == 5){
                            echo 
                            '<i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>';
                          }
                        echo '</div>
                    </div> <!-- FAS preenche e FAR não preenche-->
                    <!-- comentário-->
                    <div class="user-comment">
                        <p>'; echo $dados_fb['feedback']; echo '</p> <!-- comentário-->
                    </div>
                </div>';
              }
            }
            else{
              echo'
              <div class="comments-heading">
                <span>Este produto não possui avaliações</span>
              </div>';
            }
            
          ?>
  </section>

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