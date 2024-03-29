<?php
include ("conexao.php");
include ("verifica_login.php");

if(isset($_SESSION['usuario'])){
  $session = $_SESSION['usuario'];

  foreach($mysqli->query("SELECT u.cd_usuario AS codigo, u.nm_usuario AS nome, u.sg_especialidade AS especialidade, i.path AS path FROM tb_usuario AS u JOIN tb_imagem AS i ON i.cd_imagem = u.cd_imagem WHERE cd_usuario = '$session'") as $usuarios){
    $nomeusuario = $usuarios['nome'];
    $imagemusuario = $usuarios['path'];
    $codigousuario = $usuarios['codigo'];
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
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="favicon/ms-icon-310x310.png" />
    <!--SWAL-->
    <script src="js/swal.js"></script>
    <script src="js/clipboard.min.js"></script>
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
<header>
    <a href="index.php" class="logo"><img src="logo/padrão.png" class="nav-logo" alt="Logo"></a>

    <input type="checkbox" id="menu-bar">
    <label for="menu-bar" class="fas fa-bars"></label>

    <nav class="navbar">
        <a href="index.php">Início</a>
        <a href="loja.php">Loja</a>
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
  <?php
        $busca_prod = "SELECT COUNT(s.cd_interpretacao) AS codigo FROM tb_interpretacao AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
        $busca_prod = $mysqli->query($busca_prod);
        $busca_prod = $busca_prod->fetch_assoc();
        $confirma_prod = $busca_prod['codigo'];
        if($confirma_prod == 0){
            echo '<h3 class="heading">Você não <span>possui partituras</span></h3>';
            echo '<a href="adicionarprod.php" style="text-decoration: none;"><h5 style="font-size: 15px;">Adicionar Produto <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        }
        else{
        ?>
        <h3 class="heading">Suas <span>partituras</span></h3>

          
        <?php
        if(isset($_SESSION['usuario']))
            echo '<a href="adicionarprod.php" style="text-decoration: none;"><h5 style="font-size: 15px">Adicionar Partitura <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';?>
          <div class="box-container">
            <?php
            $meusprods = "SELECT s.cd_interpretacao, s.nm_interpretacao, s.vl_interpretacao, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_interpretacao = s.cd_interpretacao) as feedback FROM tb_interpretacao AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
                $query = $mysqli->query($meusprods);

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
                    if(strlen($dados['nm_interpretacao']) > 14)
                      echo str_replace(substr($dados['nm_interpretacao'], 11), '...', $dados['nm_interpretacao']);
                    else
                      echo $dados['nm_interpretacao'];
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
                    <form method="get" action="view_prod.php">
                      <input type="text" name="p" style="display: none;" value="'; echo $dados['cd_interpretacao']; echo '">
                      <input type="submit" class="btnpart" value="Visualizar">
                    </form>
                </div>';
                }

            ?>
          </div>
          
    <?php }?>
</section>
        
<section class="product" id="product">
  <?php
      $busca_serv = "SELECT COUNT(s.cd_servico) AS codigoserv FROM tb_servico AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
        $busca_serv = $mysqli->query($busca_serv);
        $busca_serv = $busca_serv->fetch_assoc();
        $confirma_serv = $busca_serv['codigoserv'];
        if($confirma_serv == 0){
          echo '<h3 class="heading">Você não <span>possui serviços</span></h3>';
          echo '<a href="adicionarserv.php" style="text-decoration: none;"><h5 style="font-size: 15px;">Adicionar Serviço <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
      }
      else{
        ?>
        <h3 class="heading">Seus <span>serviços</span></h3>
          
            <?php
        if(isset($_SESSION['usuario']))
            echo '<a href="adicionarserv.php" style="text-decoration: none;"><h5 style="font-size: 15px">Adicionar Serviço <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        ?>
        <div class="box-container">
            <?php
                $meusserv = "SELECT s.cd_servico, s.nm_servico, s.vl_servico, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_servico = s.cd_servico) as feedback FROM tb_servico AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario as u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
                $queryserv = $mysqli->query($meusserv);
                while($dados = $queryserv->fetch_array()){
                  echo '<div class="box">
                  <div class="icons">
                      <a href="#" class="fas fa-share"></a>
                      <a href="#" class="fas fa-copy"></a>
                  </div>
                  <img src="'; echo $dados['path']; echo '" alt="">
                  <h3>'; 
                    if(strlen($dados['nm_servico']) > 14)
                        echo str_replace(substr($dados['nm_servico'], 11), '...', $dados['nm_servico']);
                    else
                        echo $dados['nm_servico'];
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
                  echo '<div class="price"> R$'; echo $dados['vl_servico'];  echo '</div>
                  <form method="get" action="view_serv.php">
                    <input type="text" name="s" style="display: none;" value="'; echo $dados['cd_servico']; echo '">
                    <input type="submit" class="btnpart" value="Visualizar">
                  </form>
              </div>';
                }
            ?>
      </div>
  <?php } ?>
</section>

<section class="product" id="product">
<?php
      $busca_inst = "SELECT COUNT(s.cd_instrumento) AS codigoinst FROM tb_instrumento AS s JOIN tb_usuario AS u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
      $busca_inst = $mysqli->query($busca_inst);
      $busca_inst = $busca_inst->fetch_assoc();
      $confirma_inst = $busca_inst['codigoinst'];
      if($confirma_inst == 0){
        echo '<h3 class="heading">Você não <span>possui Instrumento</span></h3>';
        echo '<a href="adicionarinst.php" style="text-decoration: none;"><h5 style="font-size: 15px;">Adicionar Instrumento <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
      }
      else{
        ?>
        <h3 class="heading">Seus <span>instrumentos</span></h3>
            <?php
        if(isset($_SESSION['usuario']))
            echo '<a href="adicionarinst.php" style="text-decoration: none;"><h5 style="font-size: 15px">Adicionar Instrumento <i class="fa fa-plus" aria-hidden="true"></h5></i></a>';
        ?>
        <div class="box-container">
            <?php
                $meusserv = "SELECT s.cd_instrumento, s.nm_instrumento, s.vl_instrumento, i.path, (SELECT format(avg(f.qt_feedback), 1) from tb_feedback as f where f.cd_instrumento = s.cd_instrumento) as feedback FROM tb_instrumento AS s JOIN tb_imagem AS i ON i.cd_imagem = s.cd_imagem JOIN tb_usuario as u ON u.cd_usuario = s.cd_usuario WHERE s.cd_usuario = '$codigousuario' AND s.nm_inativo = 0";
                $queryserv = $mysqli->query($meusserv);
                while($dados = $queryserv->fetch_array()){
                  echo '<div class="box">
                  <div class="icons">
                      <a href="#" class="fas fa-share"></a>
                      <a href="#" class="fas fa-copy"></a>
                  </div>
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
                  <form method="get" action="view_inst.php">
                    <input type="text" name="s" style="display: none;" value="'; echo $dados['cd_instrumento']; echo '">
                    <input type="submit" class="btnpart" value="Visualizar">
                  </form>
              </div>';
                }
            ?>
      </div>
  <?php } ?>
</section>


    <!-- Menu navbar -> script interno -->
    <script>
      function menuAlterna(){
        const trocaMenu = document.querySelector('.menu');
        trocaMenu.classList.toggle('active');
      }
    </script>
          
  

  </body>
</html>