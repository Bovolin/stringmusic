<?php
include ("conexao.php");
include ("verifica_login.php");

if(isset($_SESSION['usuario'])){
  $session = $_SESSION['usuario'];

  foreach($mysqli->query("SELECT cd_imagem AS confere FROM tb_usuario WHERE cd_usuario = '$session'") as $conferefoto){
    $semfoto = $conferefoto['confere'];
  }

  if(empty($semfoto)){
    foreach($mysqli->query("SELECT u.cd_usuario AS codigo, u.nm_usuario AS nome, u.sg_especialidade AS especialidade FROM tb_usuario AS u WHERE cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $codigousuario = $usuarios['codigo'];
      $imagemusuario = "imgs/user.png";
      if($usuarios['especialidade'] == "m") $especialidadeusuario = "Músico";
      elseif($usuarios['especialidade'] == "c") $especialidadeusuario = "Compositor";
      elseif($usuarios['especialidade'] == "v") $especialidadeusuario = "Visitante";      
    }
  }
  else{
    foreach($mysqli->query("SELECT u.cd_usuario AS codigo, u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
      $nomeusuario = $usuarios['nome'];
      $imagemusuario = $usuarios['path'];
      $codigousuario = $usuarios['codigo'];
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
    <script src="https://kit.fontawesome.com/036a924fd6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>
</head>
<?php
if(isset($_SESSION['alterado'])) $onload = "alterado";
elseif(isset($_SESSION['removido'])) $onload = "removido";

if(isset($_SESSION['alterado'])):
?>
<script>
    function alterado(){
        Swal.fire({
            icon: 'success',
            text: 'Produto alterado com sucesso!'
        })
    }
</script>
<?php
endif;
unset($_SESSION['alterado']);

if(isset($_SESSION['removido'])):
?>
<script>
  function removido(){
    Swal.fire({
      icon: 'success',
      text: 'Produto excluído com sucesso!'
    })
  }
</script>
<?php
endif;
unset($_SESSION['removido']);
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
        <?php
        $busca_prod = "SELECT COUNT(s.cd_interpretacao) AS codigo FROM tb_interpretacao AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
        $busca_prod = $mysqli->query($busca_prod);
        $busca_prod = $busca_prod->fetch_assoc();
        $confirma_prod = $busca_prod['codigo'];
        if($confirma_prod == 0){
            echo '<h2>Você não possui produtos! Publique o seu primeiro produto.</h2>';
            echo '<a href="adicionarprod.php" style="text-decoration: none;"><h5>Adicionar Produto <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        }
        else{
        ?>
        <div class="prod">
            <h2>Meus Produtos</h2>

        <?php
        if(isset($_SESSION['usuario']))
            echo '<a href="adicionarprod.php" style="text-decoration: none;"><h5>Adicionar Produto <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        ?>
            <main class="grid">

            <?php

                $meusprods = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.ds_interpretacao, s.vl_interpretacao, s.qt_interpretacao, i.path FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
                $query = $mysqli->query($meusprods);

                while($dados = $query->fetch_array()){
                    echo 
                    '<article>
                        <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
                        <div class="text">
                            <h3>'; echo $dados['nm_interpretacao']; echo '</h3>
                            <p>'; echo $dados['ds_interpretacao']; echo '</p>
                            <p>R$'; echo $dados['vl_interpretacao']; echo '</p>
                            <p>Quantidade: '; echo $dados['qt_interpretacao']; echo '</p>
                            <form action="view_prod.php" method="get">
                              <input type="text" style="display: none;" name="p" value="'; echo $dados['cd_interpretacao']; echo '">
                              <button class="btnpart">Visualizar</button>
                            </form>
                        </div>
                    </article>';
                }

            ?>

            </main>
        </div>
        <?php }
        $busca_serv = "SELECT COUNT(s.cd_servico) AS codigoserv FROM tb_servico AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
        $busca_serv = $mysqli->query($busca_serv);
        $busca_serv = $busca_serv->fetch_assoc();
        $confirma_serv = $busca_serv['codigoserv'];
        if($confirma_serv == 0){
            echo '<h2>Você não possui serviços! Publique seu primeiro serviço.</h2>';
            echo '<a href="adicionarserv.php" style="text-decoration: none;"><h5>Adicionar Serviço <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        }
        else{
        ?>
        <div class="serv">
            <h2>Meus Serviços</h2>
            <?php
            if(isset($_SESSION['usuario']))
                echo '<a href="adicionarserv.php" style="text-decoration: none;"><h5>Adicionar Serviço <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
            ?>
            <main class="grid">
                <?php

                    $meusserv = "SELECT s.cd_servico, s.nm_servico, s.ds_servico, s.vl_servico, i.path FROM tb_servico AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario as u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
                    $queryserv = $mysqli->query($meusserv);

                    while($dados = $queryserv->fetch_array()){
                        echo
                        '<article>
                            <img src="'; echo $dados['path']; echo '" alt="" style="width: 130px; height: 175px;">
                            <div class="text">
                                <h3>'; echo $dados['nm_servico']; echo '</h3>
                                <p>'; echo $dados['ds_servico']; echo '</p>
                                <p>R$'; echo $dados['vl_servico']; echo '</p>
                                <form method="get" action="view_serv.php">
                                    <input type="text" name="s" style="display: none;" value="'; echo $dados['cd_servico']; echo '">
                                    <button class="btnpart">Visualizar</button>
                                </form>
                            </div>
                        </article>';
                    }

                ?>
            </main>
        </div>
        <?php } ?>
    </div>

    
    <style>
        .prod{
            margin-bottom: 20px;
        }
        .serv{
            margin-top: 10px;
        }
    </style>

    <!-- Menu navbar -> script interno -->
  <script>
            function menuAlterna(){
              const trocaMenu = document.querySelector('.menu');
              trocaMenu.classList.toggle('active');
            }
  </script>
          
  

</body>